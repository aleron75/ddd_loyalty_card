<?php declare(strict_types=1);

namespace Ddd\Loyalty\UseCase\Support;

use Ddd\Loyalty\Application\Application;
use Ddd\Loyalty\Application\ApplicationInterface;
use Ddd\Loyalty\Domain\Model\Card\CardNumber;
use Ddd\Loyalty\Domain\Model\Card\CustomerId;

final class UseCaseTestServiceContainer
{
    private CustomerId $currentCustomerId;

    private CustomerId $anotherCustomerId;

    private ?InMemoryCardRepository $cardRepository;

    private CardNumber $registeredCardNumber;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function application(): ApplicationInterface
    {
        return new Application($this->cardRepository());
    }

    private function cardRepository(): InMemoryCardRepository
    {
        return $this->cardRepository ?? $this->cardRepository = new InMemoryCardRepository();
    }

    public function setCurrentCustomer(int $customerId)
    {
        $this->currentCustomerId = CustomerId::fromInt($customerId);
        $this->anotherCustomerId = CustomerId::fromInt($customerId + 1);
    }

    public function currentCustomerId(): int
    {
        return $this->currentCustomerId->asInt();
    }

    public function anotherCustomerId(): int
    {
        return $this->anotherCustomerId->asInt();
    }

    public function setRegisteredCard(string $cardNumber)
    {
        $this->registeredCardNumber = CardNumber::fromString($cardNumber);
    }

    public function registeredCardNumber(): string
    {
        return $this->registeredCardNumber->asString();
    }
}
