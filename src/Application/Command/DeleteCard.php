<?php declare(strict_types=1);

namespace Ddd\Loyalty\Application\Command;

use Ddd\Loyalty\Domain\Model\Card\CustomerId;

final class DeleteCard
{
    private int $customerId;

    public function __construct(int $customerId)
    {
        $this->customerId = $customerId;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromInt($this->customerId);
    }
}
