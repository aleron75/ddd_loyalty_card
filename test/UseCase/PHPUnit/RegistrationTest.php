<?php declare(strict_types=1);

namespace Ddd\Loyalty\UseCase\PHPUnit;

use Ddd\Loyalty\Application\Command\DeleteCard as DeleteCardCommand;
use Ddd\Loyalty\Application\Command\GetCardByCustomer as GetCardByCustomerCommand;
use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;
use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;
use Ddd\Loyalty\UseCase\Support\UseCaseTestServiceContainer;
use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    private UseCaseTestServiceContainer $container;

    /**
     * @before
     */
    public function given_a_registered_customer(): void
    {
        $this->container = UseCaseTestServiceContainer::create();
        $this->container->setCurrentCustomer(1);
    }

    /**
     * Scenario
     *
     * @test
     */
    public function the_customer_cannot_register_an_already_registered_card(): void
    {
        $this->given_a_card_that_is_already_registered_by_another_customer();
        $command = $this->when_the_customer_registers_the_card();
        $this->then_its_impossible_to_register_the_card($command);
    }

    private function given_a_card_that_is_already_registered_by_another_customer(): void
    {
        $this->container->setRegisteredCard('0123456789');
        $command = new RegisterCardCommand(
            $this->container->anotherCustomerId(),
            $this->container->registeredCardNumber()
        );
        $this->container->application()->registerCard($command);
    }

    private function when_the_customer_registers_the_card(): RegisterCardCommand
    {
        return new RegisterCardCommand($this->container->currentCustomerId(), $this->container->registeredCardNumber());
    }

    private function then_its_impossible_to_register_the_card(RegisterCardCommand $command)
    {
        $this->expectException(CouldNotRegisterCard::class);
        $this->expectExceptionMessage('another customer');
        $this->container->application()->registerCard($command);
    }

    /**
     * Scenario
     *
     * @test
     */
    public function the_customer_cannot_register_more_than_one_card(): void
    {
        $this->given_the_customer_has_already_registered_a_card();
        $command = $this->when_the_customer_registers_another_card_number();
        $this->then_its_impossible_to_register_another_card($command);
    }

    private function given_the_customer_has_already_registered_a_card(): void
    {
        $this->container->setRegisteredCard('0123456789');
        $command = new RegisterCardCommand(
            $this->container->currentCustomerId(),
            $this->container->registeredCardNumber()
        );
        $this->container->application()->registerCard($command);
    }

    private function when_the_customer_registers_another_card_number(): RegisterCardCommand
    {
        return new RegisterCardCommand($this->container->currentCustomerId(), '9876543210');
    }

    private function then_its_impossible_to_register_another_card(RegisterCardCommand $command): void
    {
        $this->expectException(CouldNotRegisterCard::class);
        $this->expectExceptionMessage('another card');
        $this->container->application()->registerCard($command);
    }

    /**
     * Scenario
     *
     * @test
     */
    public function the_customer_can_register_an_unregistered_card(): void
    {
        $this->given_the_customer_doesnt_have_a_registered_card();
        $command = $this->when_the_customer_registers_an_unregistered_card();
        $this->then_the_card_is_registered_and_associated_to_the_customer($command);
    }

    private function given_the_customer_doesnt_have_a_registered_card(): void
    {
        // nothing to do, by default the customer doesn't have a card associated
    }

    private function when_the_customer_registers_an_unregistered_card(): RegisterCardCommand
    {
        return new RegisterCardCommand($this->container->currentCustomerId(), '0123456789');
    }

    private function then_the_card_is_registered_and_associated_to_the_customer(RegisterCardCommand $registerCard
    ): void {
        $this->container->application()->registerCard($registerCard);

        $command = new GetCardByCustomerCommand($registerCard->customerId()->asInt());
        $card = $this->container->application()->getCardByCustomer($command);

        self::assertTrue($registerCard->cardNumber()->equals($card->cardNumber()));
        self::assertTrue($registerCard->customerId()->equals($card->customerId()));
    }

    /**
     * Scenario
     *
     * @test
     */
    public function the_customer_can_delete_a_registered_card(): void
    {
        $card = $this->given_the_customer_has_a_registered_card();
        $this->when_the_customer_deletes_the_card($card);
        $this->then_the_card_is_deleted($card);
    }

    private function given_the_customer_has_a_registered_card(): Card
    {
        $command = new RegisterCardCommand($this->container->currentCustomerId(),'0123456789');
        $this->container->application()->registerCard($command);
        return Card::fromState(
            (string)$command->customerId()->asInt(),
            $command->cardNumber()->asString()
        );
    }

    private function when_the_customer_deletes_the_card(Card $card)
    {
        $command = new DeleteCardCommand($card->customerId()->asInt());
        $this->container->application()->deleteCardByCustomer($command);
    }

    private function then_the_card_is_deleted(Card $card): void
    {
        $this->expectException(CouldNotFindCard::class);
        $this->expectExceptionMessage(sprintf('for customer %d', $card->customerId()->asInt()));
        $command = new GetCardByCustomerCommand($card->customerId()->asInt());
        $this->container->application()->getCardByCustomer($command);
    }
}
