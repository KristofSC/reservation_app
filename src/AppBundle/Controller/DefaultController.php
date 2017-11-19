<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PatientDataType;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $surgeries = $this->getSurgeries();

        return $this->render('AppBundle::index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'surgeries' => $surgeries
        ]);
    }

    public function tableContentAction(Request $request): Response
    {
        // breadcrumbhoz:
        //$valami = $this->get('router')->generate(str_replace('/', '', $request->getPathInfo()));

        $reservationRepository = $this->getDoctrine()->getRepository('AppBundle:Reservation');

       $reservationRepository->findOneBy(['code' => '2RsTWkh4qZ']);


        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');

        $dateTimeObject = new \DateTime($selectedDate);

        $reservedDays = $reservationRepository->findReservedDays($dateTimeObject, $selectedSurgery);

            $reservedHours = [];
            foreach ($reservedDays->getResult() as $reservedDay)
            {
                $reservedHours[$reservedDay->getHour()] = $reservedDay;
            }


        return $this->render('AppBundle::timeTable.html.twig',[
                'reserved' => $reservedHours
        ]);

}

    public function patientFormAction(Request $request): Response
    {
        $patientForm = $this->createForm(PatientDataType::class);

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');
        $selectedHour = $request->get('hour');

        $patientForm->get('surgeryName')->setData($selectedSurgery);
        $patientForm->get('reservationDate')->setData($selectedDate);
        $patientForm->get('reservationHour')->setData($selectedHour);

        $patientForm->handleRequest($request);

        if($patientForm->isSubmitted() && $patientForm->isValid()){

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

            return $this->render('AppBundle::reservationSuccess.html.twig',[
                'patientData' => $patientForm->getData(),
                'code' => $reservationObject->getCode()
            ]);
        }

        return $this->render('AppBundle::patientForm.html.twig',[
            'patientForm' => $patientForm->createView()
        ]);
    }


    public function  getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

    public function getPatientFactory()
    {
        return $this->get('app.patient.factory');
    }

    public function getReservationFactory()
    {
        return $this->get('app.reservation.factory');
    }

    protected function getRandomCode()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);

        return substr($shuffled, 0, 10);
    }

}
