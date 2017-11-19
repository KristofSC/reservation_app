<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Patient;

class PatientFactory
{
    public function create(string $firstname, string $lastname, string $SSNumber, string $phoneNumber, string $email)
    {
        $patientObject = new Patient();

        $patientObject->setFirstname($firstname);
        $patientObject->setLastname($lastname);
        $patientObject->setSsNumber($SSNumber);
        $patientObject->setPhoneNumber($phoneNumber);
        $patientObject->setEmail($email);

        return $patientObject;
    }

}