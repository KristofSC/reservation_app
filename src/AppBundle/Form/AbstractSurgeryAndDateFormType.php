<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AbstractSurgeryAndDateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('surgery', ChoiceType::class,
            [
                'label' => 'Válassz rendelőt',
                'choices' => $options['data']['choices']
            ]
        )
            ->add('reservation_date', DateType::class,
                [
                    'widget' => 'single_text',
                    'attr' => ['class' => 'datepicker'],
                    'html5' => false,
                    'label' => 'Válassz dátumot'
                ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Rögzítés'
                ]);

    }
}