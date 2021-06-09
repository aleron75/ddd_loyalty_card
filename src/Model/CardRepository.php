<?php declare(strict_types=1);

namespace Ddd\Loyalty\Model;

use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use Ddd\Loyalty\Domain\Model\Card\CardRepositoryInterface;
use Ddd\Loyalty\Domain\Model\Card\CouldNotDeleteCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;
use Ddd\Loyalty\Domain\Model\Card\CustomerId;
use Ddd\Loyalty\Model\ResourceModel\Card\CollectionFactory;
use Exception;

final class CardRepository implements CardRepositoryInterface
{
    private ResourceModel\Card $cardResource;

    private ResourceModel\Card\CollectionFactory $cardCollectionFactory;

    private CardFactory $cardFactory;

    public function __construct(
        ResourceModel\Card $cardResource,
        CollectionFactory $cardCollectionFactory,
        CardFactory $cardFactory
    ) {
        $this->cardResource = $cardResource;
        $this->cardCollectionFactory = $cardCollectionFactory;
        $this->cardFactory = $cardFactory;
    }

    /**
     * @inheirtdoc
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

        $cardModel = $this->cardFactory->create();
        $cardModel->setData(
            ['customer_id' => $card->customerId()->asInt(), 'number' => $card->cardNumber()->asString()]
        );
        try {
            $this->cardResource->save($cardModel);
        } catch (Exception $exception) {
            throw CouldNotRegisterCard::becauseOfException($exception);
        }
    }

    /**
     * @inheirtdoc
     */
    public function getByCardNumber(CardNumber $cardNumber): Card
    {
        $cardModel = $this->cardCollectionFactory->create()->addFieldToFilter(
            'number',
            $cardNumber->asString()
        )->getFirstItem();

        if ($cardNumber->asString() !== (string)$cardModel->getData('number')) {
            throw CouldNotFindCard::withNumber($cardNumber);
        }

        return Card::fromState($cardModel->getData('customer_id'), $cardModel->getData('number'));
    }

    /**
     * @inheirtdoc
     */
    public function getByCustomer(CustomerId $customerId): Card
    {
        $cardModel = $this->cardCollectionFactory->create()->addFieldToFilter(
            'customer_id',
            $customerId->asInt()
        )->getFirstItem();

        if ($customerId->asInt() !== (int)$cardModel->getData('customer_id')) {
            throw CouldNotFindCard::withCustomer($customerId);
        }

        return Card::fromState($cardModel->getData('customer_id'), $cardModel->getData('number'));
    }

    /**
     * @inheritDoc
     */
    public function deleteByCustomer(CustomerId $customerId): void
    {
        $cardModel = $this->cardCollectionFactory->create()->addFieldToFilter(
            'customer_id',
            $customerId->asInt()
        )->getFirstItem();
        try {
            $this->cardResource->delete($cardModel);
        } catch (Exception $exception) {
            throw CouldNotDeleteCard::becauseOfException($exception);
        }
    }
}
