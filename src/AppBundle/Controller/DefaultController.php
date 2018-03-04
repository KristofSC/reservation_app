<?php

namespace AppBundle\Controller;

use AppBundle\Breadcrumb\BreadcrumbBuilder;
use AppBundle\Entity\Patient;
use AppBundle\Factory\PatientFactory;
use AppBundle\Factory\ReservationFactory;
use AppBundle\Form\LoginType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SurgeryAndDateFormType;
use AppBundle\Manager\PatientManager;
use AppBundle\Manager\ReservationManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ReservationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function loginAction(): Response
    {

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $loginForm = $this->createForm(LoginType::class);

        return $this->render('AppBundle::loginForm.html.twig', [
            'loginForm' => $loginForm->createView(),
            'error' => $error,
            'lastUserName' => $lastUsername

        ]);

    }

    public function logoutAction()
    {
        return $this->redirectToRoute('login');
    }

    public function registrationAction(Request $request): Response
    {
        $patient = new Patient();
        $registrationForm = $this->createForm(RegistrationType::class, $patient);

        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $this->getPatientManager()->save($patient);

            return $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess(
                $patient,
                $request,
                $this->get('app.security.login_form_authenticator'),
                'main'
            );

        }

        return $this->render('AppBundle::registrationForm.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    public function indexAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), $request->get('_route'));

        $surgeryAndDateForm = $this->createForm(SurgeryAndDateFormType::class, $this->createSurgeryChoices());

        $session = new Session();

        $session->set('numb', 1);

        $surgeryAndDateForm->handleRequest($request);

        if ($surgeryAndDateForm->isSubmitted() && $surgeryAndDateForm->isValid()) {
            $day = $this->getReservationManager()->findReservedDays($surgeryAndDateForm->get('reservation_date')->getNormData(), $surgeryAndDateForm->get('surgery')->getNormData());


            return $this->redirectToRoute('reservation_table');
        }


        $dateLimit = $this->getDateLimit();


        return $this->render('AppBundle::index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'form' => $surgeryAndDateForm->createView(),
            'breadcrumbs' => $breadcrumbs,
            'dayLimit' => $dateLimit,
        ]);
    }

    protected function getBreadcrumbBuilder(): BreadcrumbBuilder
    {
        return $this->get('app.breadcrumb.builder');
    }

    protected function getBreadcrumbs(): array
    {
        return $this->container->getParameter('app.breadcrumb');
    }

    protected function getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

    protected function getDateLimit(): int
    {
        return $this->container->getParameter('app.dateLimit');
    }

    public function tableContentAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), $request->get('_route'));

        $reservationRepository = $this->getReservationManager();

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');

        $get = $request->getSession()->set('selected', $selectedSurgery);

        $dateTimeObject = new \DateTime($selectedDate);

        $reservedDays = $reservationRepository->findReservedDays($dateTimeObject, $selectedSurgery);

        $reservedHours = [];
        foreach ($reservedDays->getResult() as $reservedDay) {
            $reservedHours[$reservedDay->getHour()] = $reservedDay;
        }


        return $this->render('AppBundle::timeTable.html.twig', [
            'reserved' => $reservedHours,
            'breadcrumbs' => $breadcrumbs,
            'sess' => $get
        ]);

    }

    protected function getReservationManager(): ReservationManager
    {
        return $this->get('app.reservation.manager');
    }

    public function patientFormAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), $request->get('_route'));

        $patientForm = $this->createForm(ReservationType::class);

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');
        $selectedHour = $request->get('hour');


        $patientForm->get('surgeryName')->setData($selectedSurgery);
        $patientForm->get('reservationDate')->setData($selectedDate);
        $patientForm->get('reservationHour')->setData($selectedHour);

        $patientForm->handleRequest($request);

        if ($patientForm->isSubmitted() && $patientForm->isValid()) {

            $reservationDatas = $request->request->all();

            $em = $this->getDoctrine()->getManager();

            $firstName = $reservationDatas['patient_data']['firstName'];
            $lastName = $reservationDatas['patient_data']['lastName'];
            $SSNumber = $reservationDatas['patient_data']['SSNumber'];
            $phoneNumber = $reservationDatas['patient_data']['phoneNumber'];
            $email = $reservationDatas['patient_data']['email'];

            $patientFactory = $this->get('app.patient.factory');

            $patientObject = $patientFactory->create($firstName, $lastName, $SSNumber, $phoneNumber, $email);

            $em->persist($patientObject);
            $em->flush();

            $selectedSurgery = $reservationDatas['patient_data']['surgeryName'];
            $selectedDate = $reservationDatas['patient_data']['reservationDate'];
            $selectedHour = $reservationDatas['patient_data']['reservationHour'];

            $randomcode = $this->getRandomCode();

            $dateTimeObject = new \DateTime($selectedDate);

            $reservationObject = $this->getReservationFactory()->create($dateTimeObject, $selectedHour, $selectedSurgery, $patientObject, $randomcode);

            $em->persist($reservationObject);
            $em->flush();

            return $this->render('AppBundle::reservationSuccess.html.twig', [
                'patientData' => $patientForm->getData(),
                'breadcrumbs' => $breadcrumbs,
                'code' => $reservationObject->getCode()
            ]);
        }

        return $this->render('AppBundle::patientForm.html.twig', [
            'patientForm' => $patientForm->createView(),
            'breadcrumbs' => $breadcrumbs,
            'sess' => $get = $request->getSession()->get('selected')
        ]);
    }

    protected function getRandomCode(): string
    {
        $nowTime = new \DateTime();
        $nowTimeStamp = $nowTime->getTimestamp();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);

        return substr($shuffled, 0, 5) . $nowTimeStamp;
    }

    protected function getReservationFactory(): ReservationFactory
    {
        return $this->get('app.reservation.factory');
    }

    public function codeSearchResultAction(Request $request): Response
    {
        $reservationRepository = $this->getReservationManager();

        $searchInput = trim($request->get('searchInput'));

        $reservationObject = $reservationRepository->findOneBy(['code' => $searchInput]);

        return $this->render('AppBundle::codeSearchResult.html.twig', [
            'reservation' => $reservationObject
        ]);
    }

    protected function getPatientManager(): PatientManager
    {
        return $this->get('app.patient.manager');
    }

    protected function getPatientFactory(): PatientFactory
    {
        return $this->get('app.patient.factory');
    }

    protected function createSurgeryChoices()
    {
        return ['choices' => $this->getSurgeries()];
    }

}
