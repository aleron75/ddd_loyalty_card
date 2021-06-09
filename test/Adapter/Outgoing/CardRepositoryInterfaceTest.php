<?php declare(strict_types=1);

namespace Ddd\Loyalty\Adapter\Outgoing;

use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use Ddd\Loyalty\Domain\Model\Card\CardRepositoryInterface;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;
use Ddd\Loyalty\Domain\Model\Card\CustomerId;
use Ddd\Loyalty\Model\CardRepository;
use Ddd\Loyalty\UseCase\Support\InMemoryCardRepository;
use Generator;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoDbIsolation enabled
 */
final class CardRepositoryInterfaceTest extends TestCase
{
    /**
     * @test
     * @dataProvider cards
     */
    public function it_can_save_a_card_and_load_it_by_number(Card $card): void
    {
        /** @var CardRepositoryInterface $cardRepository */
        foreach ($this->cardRepositories() as $cardRepository) {
            $cardRepository->save($card);
            $cardByNumber = $cardRepository->getByCardNumber($card->cardNumber());
            self::assertTrue($card->equals($cardByNumber));
        }
    }

    /**
     * @test
     * @dataProvider cards
     */
    public function it_can_save_a_card_and_load_it_by_customer(Card $card): void
    {
        /** @var CardRepositoryInterface $cardRepository */
        foreach ($this->cardRepositories() as $cardRepository) {
            $cardRepository->save($card);
            $cardByCustomer = $cardRepository->getByCustomer($card->customerId());
            self::assertTrue($card->equals($cardByCustomer));
        }
    }

    /**
     * @test
     * @dataProvider cards
     */
    public function it_cannot_save_a_card_already_associated_to_another_customer(Card $card): void
    {
        /** @var CardRepositoryInterface $cardRepository */
        foreach ($this->cardRepositories() as $cardRepository) {
            $cardRepository->save($card);
            $anotherCard = Card::fromState(
                (string)($card->customerId()->asInt() + 1),
                $card->cardNumber()->asString()
            );
            // Exception expectations in loops don't work well, that's why we use a flag
            $exceptionOccurred = false;
            try {
                $cardRepository->save($anotherCard);
            } catch (CouldNotRegisterCard $exception)  {
                $exceptionOccurred = true;
            }
            self::assertTrue($exceptionOccurred);
        }
    }

    /**
     * @test
     * @dataProvider cards
     */
    public function it_can_delete_a_card_by_customer(Card $card): void
    {
        /** @var CardRepositoryInterface $cardRepository */
        foreach ($this->cardRepositories() as $cardRepository) {
            $cardRepository->save($card);
            $cardByCustomer = $cardRepository->getByCustomer($card->customerId());
            $cardRepository->deleteByCustomer($cardByCustomer->customerId());

            // Exception expectations in loops don't work well, that's why we use a flag
            $exceptionOccurred = false;
            try {
                $cardRepository->getByCustomer($card->customerId());
            } catch (CouldNotFindCard $exception)  {
                $exceptionOccurred = true;
            }
            self::assertTrue($exceptionOccurred);
        }
    }

    public function cards(): array
    {
        return [
            'a valid card' => [Card::create(CustomerId::fromInt(1), CardNumber::fromString('0123456789'))]
        ];
    }

    private function cardRepositories(): Generator
    {
        yield new InMemoryCardRepository();
        yield Bootstrap::getObjectManager()->get(CardRepository::class);
    }
}
