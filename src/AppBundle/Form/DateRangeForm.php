<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class DateRangeForm extends AbstractDateRangeFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('surgery', ChoiceType::class,
        [
            'label' => 'Válassz rendelőt',
            'choices' => $options['data']['choices']
        ]);
    }

}