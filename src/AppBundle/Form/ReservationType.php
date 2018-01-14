<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ReservationType extends AbstractReservationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
        ->setMethod('POST')
        ->setAction('patient-form')
        ->add('submitButton', SubmitType::class,
            [
                'label' => 'Lefoglalom!',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }
}