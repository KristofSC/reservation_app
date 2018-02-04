<?php

namespace AppBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractPatientDataType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('firstname', TextType::class,
                [
                    'label' => 'Keresztnév'
                ])
            ->add('lastname', TextType::class,
                [
                    'label' => 'Vezetéknév'
                ])
            ->add('address', TextType::class,
                [
                    'label' => 'Lakcím',
                ])
            ->add('phonenumber', TextType::class,
                [
                    'label' => 'Telefonszám:'
                ])
            ->add('passwordconfirm', PasswordType::class,
                [
                    'label' => 'Jelszó újra:',
                ]
            )
            ->add('registrate', SubmitType::class,
                [
                    'label' => 'Regisztrálok'
                ]);
    }
}