<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PatientDataType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

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

        //$repository->getEntitiesForDay();

        $selectForm = $this->createForm(PatientDataType::class);

        $selectedSurgery = $request->get('surgery');
        $selectedDate = $request->get('date');
        $selectedHour = $request->get('hour');

        $selectForm->get('surgeryName')->setData($selectedSurgery);
        $selectForm->get('dateDay')->setData(new \DateTime($selectedDate));





        // adatbázisból kikérni a maiakat
        // végigjárod
        // a $reserved tömbbe az óra alapján csinálsz egy értéket


        return $this->render('AppBundle::timeTable.html.twig',[
            'selectForm' => $selectForm->createView()
        ]);
    }

    public function  getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

}
