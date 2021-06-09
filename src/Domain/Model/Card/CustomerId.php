<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

use InvalidArgumentException;

final class CustomerId
{
    private int $id;

    private function __construct(int $id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('invalid customer id');
        }

        $this->id = $id;
    }

    public static function fromInt(int $int)
    {
        return new self($int);
    }

    public function asInt(): int
    {
        return $this->id;
    }

    // TODO cover with a unit test
    public function equals(CustomerId $id): bool
    {
        return $this->id ===$id->asInt();
    }
}
