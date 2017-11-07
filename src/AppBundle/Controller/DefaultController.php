<?php

namespace AppBundle\Controller;

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


        $selectForm = $this->createForm(PatientDataType::class);

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');

        $selectForm->get('surgeryName')->setData($selectedSurgery);
        $dateTimeObject = new \DateTime($selectedDate);
        $selectForm->get('dateDay')->setData($dateTimeObject);

        if($request->query->has('hour')){

            $em = $this->getDoctrine()->getManager();

            $reservationObject = new Reservation();
            $selectedHour = $request->get('hour');

            $reservationObject->setDay($dateTimeObject);
            $reservationObject->setHour($selectedHour);
            $reservationObject->setSurgery($selectedSurgery);


            $em->persist($reservationObject);
            $em->flush();


            return $this->render('AppBundle::patientForm.html.twig',[
                'selectForm' => $selectForm->createView()
            ]);
        }

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


    public function  getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

}
