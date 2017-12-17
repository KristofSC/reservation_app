<?php

namespace AppBundle\Manager;

use AppBundle\Repository\ReservationRepository;

class ReservationManager extends ReservationRepository
{
    public function findReservedDays(\DateTime $dateTime, string $surgery)
    {
        return $this->createReservationDayQuery($dateTime, $surgery);
    }

}