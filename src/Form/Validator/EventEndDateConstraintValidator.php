<?php

namespace App\Form\Validator;

use App\Entity\Event;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EventEndDateConstraintValidator extends ConstraintValidator {
    public string $message = 'End date must be greater than start date';

    public function validate($value, Constraint $constraint): void {
        if (!$constraint instanceof EventEndDateConstraint) {
            throw new UnexpectedTypeException($constraint, EventEndDateConstraint::class);
        }

        if (!$value instanceof Event) {
            throw new UnexpectedValueException($value, Event::class);
        }

        $startDate = $value->getStartDate();
        $endDate = $value->getEndDate();
        $startTime = $value->getStartTime();
        $endTime = $value->getEndTime();

        if ($endDate <= $startDate) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('endDate')
                ->addViolation();
        }

        if ($endDate == $startDate && $endTime <= $startTime) {
            $this->context
                ->buildViolation('End time must be greater than start time, if the dates are the same')
                ->atPath('endTime')
                ->addViolation();
        }
    }
}
