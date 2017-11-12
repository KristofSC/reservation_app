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

        $reservationRepository = $this->getDoctrine()->getRepository('AppBundle:Reservation');

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');

        $dateTimeObject = new \DateTime($selectedDate);


        $reservedDays = $reservationRepository->findReservedDays($dateTimeObject, $selectedSurgery);

        $reservedHours = [];
        if($reservedDays = $reservedDays->getResult()) {
            foreach ($reservedDays as $reservedDay)
            {
                $reservedHours[$reservedDay->getHour()] = $reservedDay;
            }
        }

        return $this->render('AppBundle::timeTable.html.twig',[
                'reserved' => $reservedHours
        ]);

}

    public function patientFormAction(Request $request): Response
    {
        $patientForm = $this->createForm(PatientDataType::class);
        $validator = $this->get('validator');

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');
        $selectedHour = $request->get('hour');

        $patientForm->get('surgeryName')->setData($selectedSurgery);
        $patientForm->get('reservationDate')->setData($selectedDate);
        $patientForm->get('reservationHour')->setData($selectedHour);

        $patientForm->handleRequest($request);

        if($patientForm->isSubmitted() && $patientForm->isValid()){

            return $this->render('AppBundle::reservationSuccess.html.twig',[
                'patientData' => $patientForm->getData()
            ]);
        }

        return $this->render('AppBundle::patientForm.html.twig',[
            'patientForm' => $patientForm->createView()
        ]);
    }

    public function reservationSuccessAction(Request $request): Response
    {
        $reservationDatas = $request->request->all();

        $em = $this->getDoctrine()->getManager();

        $firstName = $reservationDatas['patient_data']['firstName'];
        $lastName = $reservationDatas['patient_data']['lastName'];
        $SSNumber = $reservationDatas['patient_data']['SSNumber'];
        $phoneNumber = $reservationDatas['patient_data']['phoneNumber'];
        $email = $reservationDatas['patient_data']['email'];

        $patientObject = new Patient();

        $patientObject->setFirstname($firstName);
        $patientObject->setLastname($lastName);
        $patientObject->setSsNumber($SSNumber);
        //$patientObject->setPhoneNumber();
        //$patientObject->setEmail();

        $em->persist($patientObject);
        $em->flush();

        $selectedSurgery = $reservationDatas['patient_data']['surgeryName'];
        $selectedDate = $reservationDatas['patient_data']['reservationDate'];
        $selectedHour = $reservationDatas['patient_data']['reservationHour'];

        $dateTimeObject = new \DateTime($selectedDate);

        $reservationObject = new Reservation();

        $em = $this->getDoctrine()->getManager();

        $reservationObject->setDay($dateTimeObject);
        $reservationObject->setHour($selectedHour);
        $reservationObject->setSurgery($selectedSurgery);
        $reservationObject->setPatient($patientObject);

        $em->persist($reservationObject);
        $em->flush();

        return $this->render('AppBundle::reservationSuccess.html.twig',[
            'datas' => $reservationDatas
        ]);
    }

    public function  getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

}
