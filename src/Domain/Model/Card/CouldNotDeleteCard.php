<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

use RuntimeException;

final class CouldNotDeleteCard extends RuntimeException
{
    public static function withCustomer(CustomerId $customerId): self
    {
        return new self(sprintf('Could not delete card of customer %d', $customerId->asInt()));
    }

    public static function becauseOfException(\Throwable $exception)
    {
        return new self(
            sprintf(
                'Could not delete card: %s',
                $exception->getMessage()
            )
        );
    }
}
