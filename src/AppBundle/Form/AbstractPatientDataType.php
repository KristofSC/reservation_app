<?php

namespace AppBundle\Form;

use AppBundle\Validator\Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractPatientDataType extends AbstractType
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
            ->add('SSNumber', TextType::class,
                [
                    'label' => 'TAJ kártya száma: ',
                    'constraints' =>
                        [
                            new Constraints\SSNumber([
                                'message' => "A formátum a következő legyen: xxx-xxx-xxx!"
                            ])
                        ]
                ])
            ->add('phoneNumber', TextType::class,
                [
                    'required' => false,
                    'label' => 'Telefonszám: '
                ])
            ->add('email', EmailType::class,
                [
                    'required' => true,
                    'label' => 'E-mail cím: '
                ])
            ->setMethod('POST')
            ->setAction('patient_form')
            ->add('submitButton', SubmitType::class,
                [
                    'label' => 'Lefoglalom!',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]);
    }

}