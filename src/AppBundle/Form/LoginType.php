<?php


namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractPatientDataType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('login', SubmitType::class,
            [
                'label' => 'Belépés',
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ]
        )
            ;
    }

}