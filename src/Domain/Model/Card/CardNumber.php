<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

use InvalidArgumentException;

final class CardNumber
{
    private string $number;

    private function __construct(string $number)
    {
        if (0 === \preg_match('/^\d{10}$/', $number)) {
            throw new InvalidArgumentException('invalid card number');
        }

        $this->number = $number;
    }

    public static function fromString(string $string)
    {
        return new self($string);
    }

    public function asString()
    {
        return $this->number;
    }

    // TODO cover with a unit test
    public function equals(CardNumber $number): bool
    {
        return $this->number === $number->asString();
    }
}
