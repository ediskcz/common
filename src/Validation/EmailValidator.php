<?php

namespace Edisk\Common\Validation;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class EmailValidator
{
    public static function validate(string $email, bool $strict = false): ConstraintViolationListInterface|bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate(
            $email,
            [
                new Email(
                    [
                        'mode' => $strict ? Email::VALIDATION_MODE_STRICT : Email::VALIDATION_MODE_LOOSE,
                    ]
                ),
            ]
        );
        $valid = 0 === count($violations);

        return $valid ?: $violations;
    }
}
