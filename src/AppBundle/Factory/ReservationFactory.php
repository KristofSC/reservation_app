<?php

namespace AppBundle\Factory;


use AppBundle\Entity\Patient;
use AppBundle\Entity\Reservation;

class ReservationFactory
{
    public function create(\DateTime $dateTimeObject, int $selectedHour, string $selectedSurgery, ?Patient $patientObject, string $randomCode)
    {
        $reservationObject = new Reservation();

        $reservationObject->setDay($dateTimeObject);
        $reservationObject->setHour($selectedHour);
        $reservationObject->setPatient($patientObject);

        return $reservationObject;
    }

}