<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Patient;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DeleteAnyReservationVoter extends Voter
{
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if($attribute === static::DELETE){
            return true;
        }
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if($token->getUser() instanceof Patient){
            if($user->getId() === $subject->getPatient()->getId()){
                return true;
            }
        }

    }

}