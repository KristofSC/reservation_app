<?php

namespace AppBundle\Form;


use AppBundle\Entity\Patient;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'required' => false
                ])
            ->add('phonenumber', TextType::class,
                [
                    'label' => 'Telefonszám:',
                    'required' => false
                ])
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'A két jelszónak egyeznie kell!',
                    'first_options' =>
                        [ 'label' => 'Jelszó' ],
                    'second_options' =>
                        [ 'label' => 'Jelszó megerősítése']
                ]
            )
            ->add('registrate', SubmitType::class,
                [
                    'label' => 'Regisztrálok'
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( [
            'data_class' => Patient::class,
                ]
        );
    }
}