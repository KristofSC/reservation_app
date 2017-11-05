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
        $builder->add('surgeryName',TextType::class,
                    [
                      'label' => 'Rendelő neve: ',
                      'attr' => [
                          'disabled' => true
                      ]
                ])
                ->add('dateDay', DateTimeType::class,
                    [
                      'label' => 'Foglalás ideje: ',
                      'attr' => [
                          'disabled' => true
                      ]
            ] )
                ->add('SSNumber', TextType::class,
                    [
                      'label' => 'TAJ kártya száma: ',
                    ])
                ->add('phoneNumber', TextType::class,
                    [
                       'label' => 'Telefonszám: '
                    ])
                ->add('email', EmailType::class,
                    [
                        'label' => 'E-mail cím: '
                    ])
                ->add('submitButton', SubmitType::class,
                    [
                        'label' => 'Lefoglalom!'
                    ]);
    }

}