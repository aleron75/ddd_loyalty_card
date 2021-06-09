<?php declare(strict_types=1);

namespace Ddd\Loyalty\Unit\Domain\Model\Card;

use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use PHPUnit\Framework\TestCase;

final class CardNumberTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidCardNumber
     */
    public function it_does_not_accept_an_invalid_card_number(string $cardNumber): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('card number');

        CardNumber::fromString($cardNumber);
    }

    public function invalidCardNumber(): array
    {
        return [
            'an empty string' => [''],
            'less that ten digits' => ['123456789'],
            'more than ten digits' => ['123456789123'],
            'invalid chars' => ['123a567b90'],
        ];
    }

    /**
     * @test
     * @dataProvider validCardNumber
     */
    public function it_accepts_a_valid_card_number(string $cardNumber): void
    {
        self::assertSame($cardNumber, CardNumber::fromString($cardNumber)->asString());
    }

    public function validCardNumber(): array
    {
        return [
            'ten digits' => ['0123456789'],
            'all zeroes' => ['0000000000'],
        ];
    }
}
