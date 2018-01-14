<?php

namespace AppBundle\Manager;

use AppBundle\Repository\ReservationRepository;

class ReservationManager extends BaseManager
{
    /**
     * @var ReservationRepository
     */
    protected $repository;

    public function findReservedDays(\DateTime $dateTime, string $surgery)
    {
        return $this->repository->createReservationDayQuery($dateTime, $surgery);
    }

}