<?php declare(strict_types=1);

namespace Ddd\Loyalty\Application\Command;

use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;

final class RegisterCardValidator
{
    public function validate(RegisterCardCommand $command): array
    {
        $errors = [];

        try {
            $command->customerId();
        } catch (\InvalidArgumentException $exception) {
            $errors[] = 'Invalid customer id';
        }

        try {
            $command->cardNumber();
        } catch (\InvalidArgumentException $exception) {
            $errors[] = 'Invalid card number';
        }

        return $errors;
    }
}
