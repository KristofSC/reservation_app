<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Patient;

class PatientManager extends BaseManager
{
    public function doSaveEntity(Patient $entity)
    {
        $this->save($entity);
    }

}