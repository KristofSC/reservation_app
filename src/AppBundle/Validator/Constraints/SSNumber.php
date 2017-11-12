<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SSNumber extends Constraint
{
    public $message = "Nem megfelelő formátum!";
}