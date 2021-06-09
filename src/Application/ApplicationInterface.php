<?php declare(strict_types=1);

namespace Ddd\Loyalty\Application;

use Ddd\Loyalty\Application\Command\DeleteCard as DeleteCardCommand;
use Ddd\Loyalty\Application\Command\GetCardByCustomer as GetCardByCustomerCommand;
use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;
use Ddd\Loyalty\Domain\Model\Card\Card;
use Ddd\Loyalty\Domain\Model\Card\CouldNotDeleteCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;

interface ApplicationInterface
{
    /**
     * @throws CouldNotRegisterCard
     */
    public function registerCard(RegisterCardCommand $command): void;

    /**
     * @throws CouldNotFindCard
     */
    public function getCardByCustomer(GetCardByCustomerCommand $command): Card;

    /**
     * @throws CouldNotDeleteCard
     */
    public function deleteCardByCustomer(DeleteCardCommand $command): void;
}
