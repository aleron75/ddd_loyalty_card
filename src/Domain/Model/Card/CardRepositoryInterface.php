<?php declare(strict_types=1);

namespace Ddd\Loyalty\Domain\Model\Card;

interface CardRepositoryInterface
{
    /**
     * @throws CouldNotRegisterCard when the card is already associated to another customer
     */
    public function save(Card $card): void;

    /**
     * @throws CouldNotFindCard when the card with given number is not found in the repository
     */
    public function getByCardNumber(CardNumber $cardNumber): Card;

    /**
     * @throws CouldNotFindCard when the card with given customer is not found in the repository
     */
    public function getByCustomer(CustomerId $customerId): Card;

    /**
     * @throws CouldNotDeleteCard when the card of given customer cannot be deleted
     */
    public function deleteByCustomer(CustomerId $customerId): void;
}
