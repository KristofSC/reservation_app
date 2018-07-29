<?php

namespace AppBundle\Entity;

/**
 * ReservationDay
 */
class Reservation extends Entity
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
     * @var string
     */
    private $description;

    /**
     * @var Patient
     */
    private $patient;


    public function getId(): int
    {
        return $this->id;
    }

    public function getDay(): \DateTime
    {
        return $this->reservation_day;
    }

    public function setDay(\DateTime $reservation_day): void
    {
        $this->reservation_day = $reservation_day;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function setHour(int $hour): void
    {
        $this->hour = $hour;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}