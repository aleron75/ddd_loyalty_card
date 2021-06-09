<?php declare(strict_types=1);

namespace Ddd\Loyalty\Application;

use Ddd\Loyalty\Application\Command\DeleteCard as DeleteCardCommand;
use Ddd\Loyalty\Application\Command\GetCardByCustomer as GetCardByCustomerCommand;
use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;
use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CardRepositoryInterface;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;

final class Application implements ApplicationInterface
{
    private CardRepositoryInterface $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * @inheridoc
     */
    public function registerCard(RegisterCardCommand $command): void
    {
        $card = Card::create($command->customerId(), $command->cardNumber());

        try {
            $cardByNumber = $this->cardRepository->getByCardNumber($card->cardNumber());

            if (false === $cardByNumber->customerId()->equals($card->customerId())) {
                throw CouldNotRegisterCard::becauseItIsAlreadyAssociatedToAnotherCustomer($card->cardNumber());
            }
        } catch (CouldNotFindCard $exception) {
            // intentionally left blank: if a card with given number does not exist, we can always save it
        }

        try {
            $cardByCustomer = $this->cardRepository->getByCustomer($card->customerId());

            if (false === $cardByCustomer->cardNumber()->equals($card->cardNumber())) {
                throw CouldNotRegisterCard::becauseTheCustomerHasAlreadyRegisteredAnotherCard($card->customerId());
            }
        } catch (CouldNotFindCard $exception) {
            // intentionally left blank: if a card with given customer does not exist, we can always save it
        }

        $this->cardRepository->save($card);
    }

    /**
     * @inheritDoc
     */
    public function getCardByCustomer(GetCardByCustomerCommand $command): Card
    {
        return $this->cardRepository->getByCustomer($command->customerId());
    }

    /**
     * @inheritDoc
     */
    public function deleteCardByCustomer(DeleteCardCommand $command): void
    {
        $this->cardRepository->deleteByCustomer($command->customerId());
    }
}
