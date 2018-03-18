<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Reservation;
use AppBundle\Repository\ReservationRepository;

class ReservationManager extends BaseManager
{
    /**
     * @var ReservationRepository
     */
    protected $repository;

    public function findReservedDays(\DateTime $dateTime, string $surgery): array
    {
        $query = $this->repository->createReservationDayQuery($dateTime, $surgery);

        return $query->execute();
    }

    public function doSaveEntity(Reservation $entity)
    {
        $this->save($entity);
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function removeReservationByHour(int $id, int $hour)
    {
        $this->repository->removeReservationByHour($id, $hour);
    }

}