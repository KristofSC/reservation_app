<?php

namespace AppBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DeleteAnyReservationVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        dump('túúúúúl');
        die;
    }

}