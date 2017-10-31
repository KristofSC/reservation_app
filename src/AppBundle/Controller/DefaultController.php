<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SelectCategoryType;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $selectForm = $this->createForm(SelectCategoryType::class);

        $surgeries = $this->getSurgeries();

        // replace this example code with whatever you need
        return $this->render('AppBundle::index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'selectForm' => $selectForm->createView(),
            'surgeries' => $surgeries
        ]);
    }

    public function tableContentAction(Request $request): Response
    {

        $date = $request->get('date');

        $reservations = $this->getDoctrine()->getRepository('AppBundle:ReservationDay')->findAll();

        $repository = $this->getDoctrine()->getRepository('AppBundle:ReservationDay');

        $repository->getEntitiesForDay();


        $reserved = [];


        // adatbázisból kikérni a maiakat
        // végigjárod
        // a $reserved tömbbe az óra alapján csinálsz egy értéket
        $reserved[12] = true;
        $reserved[13] = true;

        return $this->render('AppBundle::timeTable.html.twig',[
            'reserved' => $reserved
        ]);
    }

    public function  getSurgeries(): array
    {
        return $this->container->getParameter('app.surgeries');
    }

}


