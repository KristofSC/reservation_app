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
    private $surgery;

    /**
     * @var string
     */
    private $code;

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

    public function getSurgery(): string
    {
        return $this->surgery;
    }

    public function setSurgery(string $surgery): void
    {
        $this->surgery = $surgery;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getPatient(): Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): void
    {
        $this->patient = $patient;
    }



}