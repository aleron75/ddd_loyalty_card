<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

use RuntimeException;

final class CouldNotFindCard extends RuntimeException
{
    public static function withNumber(CardNumber $cardNumber): self
    {
        return new self(sprintf('Could not find card %s', $cardNumber->asString()));
    }

    public static function withCustomer(CustomerId $customerId): self
    {
        return new self(sprintf('Could not find card for customer %d', $customerId->asInt()));
    }
}
