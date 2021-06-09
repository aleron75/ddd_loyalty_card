<?php declare(strict_types=1);

namespace Ddd\Loyalty\Unit\Domain\Model\Card;

use Ddd\Loyalty\Domain\Model\Card\CustomerId;
use PHPUnit\Framework\TestCase;

final class CustomerIdTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidCustomerId
     */
    public function it_does_not_accept_invalid_customer_id(int $customerId)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('customer id');
        CustomerId::fromInt($customerId);
    }

    public function invalidCustomerId(): array
    {
        return [
            'zero' => [0],
            'negative number' => [-10],
        ];
    }

    /**
     * @test
     * @dataProvider validCustomerId
     */
    public function it_accepts_valid_customer_id(int $customerId): void
    {
        self::assertSame($customerId, CustomerId::fromInt($customerId)->asInt());
    }

    public function validCustomerId(): array
    {
        return [
            'positive number' => [1],
            'another positive number' => [42],
        ];
    }
}
