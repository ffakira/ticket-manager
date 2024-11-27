<?php

namespace App\Form\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EventEndDateConstraint extends Constraint {
    public string $message = 'End date must be greater than start date';

    public function getTargets(): string {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string {
        return EventEndDateConstraintValidator::class;
    }
}
