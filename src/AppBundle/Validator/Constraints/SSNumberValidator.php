<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class SSNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {

        if(!preg_match("/\d{3}-\d{3}-\d{3}/", $value)){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}