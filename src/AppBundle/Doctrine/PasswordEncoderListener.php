<?php

namespace AppBundle\Doctrine;


use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Patient;

class PasswordEncoderListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if(!$entity instanceof Patient){
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {

    }

    private function encodePassword(Patient $user)
    {
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

    }

}