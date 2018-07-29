<?php

namespace AppBundle\Controller;

use AppBundle\Breadcrumb\BreadcrumbBuilder;
use AppBundle\Email\EmailSender;
use AppBundle\Entity\Patient;
use AppBundle\Factory\PatientFactory;
use AppBundle\Factory\ReservationFactory;
use AppBundle\Form\DateRangeForm;
use AppBundle\Form\LoginType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SurgeryAndDateFormType;
use AppBundle\Manager\PatientManager;
use AppBundle\Manager\ReservationManager;
use AppBundle\Provider\DateProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
            $this->getPatientManager()->doSaveEntity($patient);

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

    public function indexAction(): Response
    {
        if($this->getUser() instanceof Patient){
            return $this->render('AppBundle::ajaxView.html.twig', [
                'firstname' => $this->getUser()->getFirstname(),
                'lastname' => $this->getUser()->getLastname(),
            ]);
        }

        return $this->redirectToRoute('admin', ['page' => null]);
    }

    public function adminTableAction(Request $request, $page = null): Response
    {
        $dateRangeForm = $this->createForm(DateRangeForm::class, $this->createSurgeryChoices());

        $dateRangeForm->handleRequest($request);

        if ($dateRangeForm->isSubmitted() && $dateRangeForm->isValid()) {

            $from = $dateRangeForm['from']->getData();
            $to = $dateRangeForm['to']->getData();

            $reservations = $this->getReservationManager()->findByDatePeriod($from, $to, ['currentPage' => 1, 'pageSize' => 2]);

            dump($reservations);die;

            return $this->render('AppBundle::adminTable.html.twig', [
                'dateRangeForm' => $dateRangeForm->createView(),
                'reservations' => $reservations,
                'resultNumber' => count($reservations),
                'fromDate' => $from,
                'toDate' => $to
            ]);
        }

        return $this->render('AppBundle::adminTable.html.twig', [
            'dateRangeForm' => $dateRangeForm->createView(),
        ]);
    }

    public function paginatorAction(Request $request)
    {
        $page = $request->query->get('page');
        $fromDate = $request->query->get('fromDate');
        $toDate = $request->query->get('toDate');

        $fromDateObj = new \DateTime($fromDate);
        $toDateObj = new \DateTime($toDate);

        $reservations = $this->getReservationManager()->findByDatePeriod($fromDateObj, $toDateObj, ['currentPage' => $page, 'pageSize' => 2,]);

        list($beginDay, $endDay) = $this->getDateProvider()->calculateBeginAndEndOfSearchDay($fromDateObj, $toDateObj, 'last monday', 'next saturday');

        $template = $this->renderView(
            'AppBundle::adminAjaxTable.html.twig',
            [
                'page' => $page,
                'reservations' => $reservations,
                'beginDay' => $beginDay,
                'endDay' => $endDay
            ]
        );

        return new Response($template);
    }

    public function ajaxRouterAction(Request $request, $template): Response
    {
        switch ($template) {
            case 'surgery-date':
                $renderedTemplate = $this->forward('AppBundle:Default:surgeryDate');
                return new Response($renderedTemplate->getContent());
                break;
            case 'reservation-table':
                $renderedTemplate = $this->forward('AppBundle:Default:tableContent', [],
                    [
                        'surgery' => $request->query->get('surgery'),
                        'date' => $request->query->get('date')
                    ]);
                return new Response($renderedTemplate->getContent());
                break;
            case 'summary':
                $renderedTemplate = $this->forward('AppBundle:Default:summary', [],
                    [
                        'hour' => $request->query->get('hour')
                    ]);
                return new Response($renderedTemplate->getContent());
                break;
            case 'reservation-success':
                $renderedTemplate = $this->forward('AppBundle:Default:reservationSuccess');
                return new Response($renderedTemplate->getContent());
                break;
            case 'delete-reservation':
                $renderedTemplate = $this->forward('AppBundle:Default:deleteReservation', [],
                    [
                        'id' => $request->query->get('id'),
                        'hour' => $request->query->get('hour')
                    ]);
                return new Response($renderedTemplate->getContent());
                break;
        }

    }


    public function surgeryDateAction(Request $request): Response
    {

        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), 'surgery_date');

        $surgeryAndDateForm = $this->createForm(SurgeryAndDateFormType::class, $this->createSurgeryChoices());

        $dateLimit = $this->getDateLimit();


        return $this->render('AppBundle::surgeryDate.html.twig', [
            'form' => $surgeryAndDateForm->createView(),
            'breadcrumbs' => $breadcrumbs,
            'dayLimit' => $dateLimit,
        ]);
    }

    public function tableContentAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), 'reservation_table');

        if($request->query->get('surgery') !== null && $request->query->get('date') !== null){
            $surgery = $request->query->get('surgery');
            $date = $request->query->get('date');
            $dateTime = new \DateTime($date);

            $session = new Session();

            $session->set('surgery', $surgery);
            $session->set('date', $date);

            $reservedDays = $this->getReservationManager()->findReservedDays($dateTime, $surgery);

            $session->set('reserved', $reservedDays);
        }

        if($request->getSession()->has('hour')){
            $hour = $request->getSession()->get('hour');
        }

        $reservedHours = [];
        foreach ($request->getSession()->get('reserved') as $reservedDay) {
            $reservedHours[$reservedDay->getHour()] = $reservedDay;
        }

        return $this->render('AppBundle::timeTable.html.twig', [
            'reserved' => $reservedHours,
            'breadcrumbs' => $breadcrumbs,
            'surgery' => array_search($request->getSession()->get('surgery'), $this->getSurgeries()),
            'date' =>  $request->getSession()->get('date'),
            'hour' => $hour ?? null
        ]);

    }

    public function deleteReservationAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), 'reservation_table');

        $id = $request->query->get('id');
        $hour = $request->query->get('hour');

        $this->getReservationManager()->removeReservationByHour($id, $hour);

        $surgery = $request->getSession()->get('surgery');
        $date = $request->getSession()->get('date');

        $dateTime = new \DateTime($date);

        $reservedDays = $this->getReservationManager()->findReservedDays($dateTime, $surgery);

        $request->getSession()->set('reserved', $reservedDays);


        if($request->getSession()->has('hour')){
            $hour = $request->getSession()->get('hour');
        }

        $reservedHours = [];
        foreach ($request->getSession()->get('reserved') as $reservedDay) {
            $reservedHours[$reservedDay->getHour()] = $reservedDay;
        }


        return $this->render('AppBundle::timeTable.html.twig', [
            'reserved' => $reservedHours,
            'breadcrumbs' => $breadcrumbs,
            'surgery' => $request->getSession()->get('surgery'),
            'date' =>  $request->getSession()->get('date'),
            'hour' => $hour ?? null
        ]);


    }

    public function summaryAction(Request $request): Response
    {
        $breadcrumbs = $this->getBreadcrumbBuilder()->addItemList($this->getBreadcrumbs(), 'summary_page');

        $request->getSession()->set('hour', $request->query->get('hour'));

        $dayOfWeek = $this->getDayTranslatedDayOfWeek(date("l", strtotime($request->getSession()->get('date'))));

        return $this->render('AppBundle::summary.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'surgery' => $surgery = array_search($request->getSession()->get('surgery'), $this->getSurgeries()),
            'date' => $request->getSession()->get('date'),
            'hour' => $request->getSession()->get('hour'),
            'dayOfWeek' => $dayOfWeek
        ]);
    }

    public function reservationSuccessAction(Request $request): Response
    {
        $surgery = $request->getSession()->get('surgery');
        $date = new \DateTime($request->getSession()->get('date'));
        $hour = $request->getSession()->get('hour');
        $code = $this->getRandomCode();

        $reservation = $this->getReservationFactory()->create($date, $hour, $surgery, $this->getUser(), $code);

        $this->getReservationManager()->doSaveEntity($reservation);

        $sender = $this->getEmailSender();

        $renderedView = $this->renderView(
            'AppBundle::successEmail.html.twig',
            [
                'lastname' => $this->getUser()->getLastname(),
                'firstname' => $this->getUser()->getFirstName(),
                'date' => $date->format('Y-m-d'),
                'hour' => $hour
            ]
        );

        $sender->send(
            'scytha87@gmail.com',
            'scytha87@gmail.com',
            'Sikeres időpont foglalás',
            $renderedView);


        return $this->render('AppBundle::reservationSuccess.html.twig', [
            'firstName' => $this->getUser()->getFirstname(),
            'lastName' => $this->getUser()->getLastname(),
            'code' => $code
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

    protected function getReservationManager(): ReservationManager
    {
        return $this->get('app.reservation.manager');
    }

    protected function getRandomCode(): string
    {
        $nowTime = new \DateTime();
        $nowTimeStamp = $nowTime->getTimestamp();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);

        return substr($shuffled, 0, 3) . $nowTimeStamp;
    }

    protected function getReservationFactory(): ReservationFactory
    {
        return $this->get('app.reservation.factory');
    }

    public function codeSearchResultAction(Request $request): Response
    {
        $searchInput = trim($request->get('searchInput'));

        $reservationObject = $this->getReservationManager()->findOneBy(['code' => $searchInput]);

        return $this->render('AppBundle::codeSearchResult.html.twig', [
            'reservation' => $reservationObject,
            'surgery' => array_search($reservationObject->getSurgery(), $this->getSurgeries())
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

    protected function createSurgeryChoices(): array
    {
        return ['choices' => ['Válassz rendelőt' => $this->getSurgeries()]];
    }

    protected function getDayTranslatedDayOfWeek(string $daysOfWeek): string
    {
            switch ($daysOfWeek){
                case 'Monday':
                    return 'Hétfő';
                case 'Tuesday':
                    return 'Kedd';
                case 'Wednesday':
                    return 'Szerda';
                case 'Thursday':
                    return 'Csütörtök';
                case 'Friday':
                    return 'Péntek';
                case 'Saturday':
                    return 'Szombat';
                case 'Sunday':
                    return 'Vasárnap';
        }
    }

    protected function getEmailSender(): EmailSender
    {
        return $this->container->get('app.email.sender');
    }

    protected function getDateProvider(): DateProvider
    {
        return $this->container->get('app.date.provider');
    }

}
