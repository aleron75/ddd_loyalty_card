<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

final class Card
{
    private CustomerId $customerId;

    private CardNumber $cardNumber;

    private function __construct()
    {
    }

    public static function create(CustomerId $customerId, CardNumber $cardNumber): self
    {
        $card = new self();
        $card->customerId = $customerId;
        $card->cardNumber = $cardNumber;
        return $card;
    }

    public static function fromState(string $customerId, string $cardNumber): self
    {
        return self::create(
            CustomerId::fromInt((int)$customerId),
            CardNumber::fromString($cardNumber)
        );
    }

    public function cardNumber(): CardNumber
    {
        return $this->cardNumber;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    // TODO cover with a unit test
    public function equals(Card $card): bool
    {
        return $this->customerId->equals($card->customerId()) && $this->cardNumber->equals($card->cardNumber());
    }
}
