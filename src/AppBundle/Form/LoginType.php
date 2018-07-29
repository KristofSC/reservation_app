<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
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
                ->add('email', EmailType::class,
                [
                    'label' => 'E-mail cím: ',
                ])
                ->add('password', PasswordType::class,
                    [
                        'label' => 'Jelszó',
                    ]
                )
            ;
    }

}