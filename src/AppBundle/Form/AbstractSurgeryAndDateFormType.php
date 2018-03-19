<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractSurgeryAndDateFormType extends AbstractType
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
                    'label' => 'Válassz dátumot',
                ])
            ->add('submit', ButtonType::class,
                [
                    'label' => 'Rögzítés',
                ]);

    }
}