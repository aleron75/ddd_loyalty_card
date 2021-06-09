<?php declare(strict_types=1);

namespace Ddd\Loyalty\Application\Command;

use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use Ddd\Loyalty\Domain\Model\Card\CustomerId;

final class RegisterCard
{
    private int $customerId;
    private string $cardNumber;

    public function __construct(int $customerId, string $cardNumber)
    {
        $this->customerId = $customerId;
        $this->cardNumber = $cardNumber;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromInt($this->customerId);
    }

    public function cardNumber(): CardNumber
    {
        return CardNumber::fromString($this->cardNumber);
    }
}
