<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractDateRangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', DateTimeType::class,
                [
                    'label' => 'Dátum -tól ',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'datepicker_range'],

                ])
            ->add('to', DateTimeType::class,
                [
                    'label' => 'Dátum -ig: ',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'datepicker_range'],

                ])
            ->add('list', SubmitType::class,
                [
                    'label' => 'Listázz!',
                ]);
    }


}