<?php

namespace AppBundle\Entity;

/**
 * Patient
 */
class Patient
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var int
     */
    private $ssNumber;

    /**
     * @var Reservation
     */
    private $reservations;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Patient
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Patient
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set ssNumber
     *
     * @param integer $ssNumber
     *
     * @return Patient
     */
    public function setSsNumber($ssNumber)
    {
        $this->ssNumber = $ssNumber;

        return $this;
    }

    /**
     * Get ssNumber
     *
     * @return int
     */
    public function getSsNumber()
    {
        return $this->ssNumber;
    }

    /**
     * @return Reservation
     */
    public function getReservations(): Reservation
    {
        return $this->reservations;
    }

    /**
     * @param Reservation $reservations
     */
    public function setReservations(Reservation $reservations)
    {
        $this->reservations = $reservations;
    }


}

