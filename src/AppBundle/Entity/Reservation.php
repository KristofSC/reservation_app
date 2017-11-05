<?php

namespace AppBundle\Entity;

/**
 * ReservationDay
 */
class Reservation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $reservation_day;

    /**
     * @var int
     */
    private $hour;

    /**
     * @var Patient
     */
    private $patient;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDay(\DateTime $reservation_day): void
    {
        $this->reservation_day = $reservation_day;
    }

    public function getDay(): \DateTime
    {
        return $this->reservation_day;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function setHour($hour): void
    {
        $this->hour = $hour;
    }

    public function getPatient(): Patient
    {
        return $this->patient;
    }

    public function setPatient($patient): void
    {
        $this->patient = $patient;
    }

}