<?php

namespace AppBundle\Entity;

/**
 * Patient
 */
class Patient extends Entity
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
    private $ss_number;

    /**
     * @var string
     */
    private $phone_number;

    /**
     * @var string
     */
    private $email;

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
    public function setSsNumber($ss_number)
    {
        $this->ss_number = $ss_number;

        return $this;
    }

    /**
     * Get ssNumber
     *
     * @return int
     */
    public function getSsNumber()
    {
        return $this->ss_number;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     */
    public function setPhoneNumber(string $phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

