<?php

namespace AppBundle\Form;

use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PatientDataType extends AbstractType
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
                    'label' => 'Vezetéknév: ',
                ])
                ->add('firstName', TextType::class,
                [
                    'label' => 'Keresztnév: ',
                ])
                ->add('SSNumber', TextType::class,
                    [
                      'label' => 'TAJ kártya száma: ',
                    ])
                ->add('phoneNumber', TextType::class,
                    [
                        'required' => false,
                       'label' => 'Telefonszám: '
                    ])
                ->add('email', EmailType::class,
                    [
                        'label' => 'E-mail cím: '
                    ])
                ->setMethod('POST')
                ->setAction('reservation_success')
                ->add('submitButton', SubmitType::class,
                    [
                        'label' => 'Lefoglalom!',
                        'attr' => [
                            'class' => 'btn btn-success'
                        ]
                    ]);
    }

}