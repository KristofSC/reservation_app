<?php

namespace AppBundle\Form;

use AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surgeryName', HiddenType::class,
                [
                    'label' => 'Rendelő: ',

                ])
            ->add('reservationDate', HiddenType::class,
                [
                    'label' => 'Foglalás napja: ',
                ])
            ->add('reservationHour', HiddenType::class,
                [
                    'label' => 'Óra: ',
                ])
            ->add('lastName', TextType::class,
                [
                    'required' => true,
                    'label' => 'Vezetéknév: ',
                ])
            ->add('firstName', TextType::class,
                [
                    'required' => true,
                    'label' => 'Keresztnév: ',
                ])

            ->add('email', EmailType::class,
                [
                    'required' => true,
                    'label' => 'E-mail cím: ',
                    'constraints' =>
                        [
                            new Assert\Email(([
                                'message' => "E-mail cím nem megfelelő!"
                            ]))
                        ]
                ])
            ->add('submitButton', SubmitType::class,
                [
                    'label' => 'Lefoglalom!',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]);
    }

}