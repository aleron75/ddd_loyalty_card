<?php declare(strict_types=1);

namespace Ddd\Loyalty\UseCase\Support;

use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use Ddd\Loyalty\Domain\Model\Card\CardRepositoryInterface;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;
use Ddd\Loyalty\Domain\Model\Card\CustomerId;

final class InMemoryCardRepository implements CardRepositoryInterface
{
    private array $cards = [];

    /**
     * @inheritDoc
     */
    public function save(Card $card): void
    {
        try {
            $alreadyRegisteredCard = $this->getByCardNumber($card->cardNumber());
            if (!$alreadyRegisteredCard->customerId()->equals($card->customerId())) {
                throw CouldNotRegisterCard::becauseItIsAlreadyAssociatedToAnotherCustomer($alreadyRegisteredCard->cardNumber());
            }
        } catch (CouldNotFindCard $exception) {
            // intentionally left blank
        }
        $this->cards[$card->customerId()->asInt()] = $card;
    }

    /**
     * @inheritDoc
     */
    public function getByCardNumber(CardNumber $cardNumber): Card
    {
        $result = array_filter($this->cards, function ($card) use ($cardNumber) {
            return $card->cardNumber()->equals($cardNumber);
        });
        if(count($result)) {
            return reset($result);
        }

        throw CouldNotFindCard::withNumber($cardNumber);
    }

    /**
     * @inheritDoc
     */
    public function getByCustomer(CustomerId $customerId): Card
    {
        if (false === isset($this->cards[$customerId->asInt()])) {
            throw CouldNotFindCard::withCustomer($customerId);
        }
        return $this->cards[$customerId->asInt()];
    }

    /**
     * @inheritDoc
     */
    public function deleteByCustomer(CustomerId $customerId): void
    {
        unset($this->cards[$customerId->asInt()]);
    }
}
