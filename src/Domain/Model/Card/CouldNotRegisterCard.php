<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

use RuntimeException;

final class CouldNotRegisterCard extends RuntimeException
{
    public static function becauseItIsAlreadyAssociatedToAnotherCustomer(CardNumber $cardNumber): self
    {
        return new self(
            sprintf(
                'Could not register card %s because it is already associated to another customer',
                $cardNumber->asString()
            )
        );
    }

    public static function becauseTheCustomerHasAlreadyRegisteredAnotherCard(CustomerId $customerId)
    {
        return new self(
            sprintf(
                'Could not register a new card for customer %d because has already associated another card',
                $customerId->asInt()
            )
        );
    }

    public static function becauseOfException(\Throwable $exception)
    {
        return new self(
            sprintf(
                'Could not register a new card: %s',
                $exception->getMessage()
            )
        );
    }
}
