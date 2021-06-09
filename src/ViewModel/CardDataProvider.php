<?php declare(strict_types=1);

namespace Ddd\Loyalty\ViewModel;

use Ddd\Loyalty\Application\ApplicationInterface;
use Ddd\Loyalty\Application\Command\GetCardByCustomer as GetCardByCustomerCommand;
use Ddd\Loyalty\Domain\Model\Card\CouldNotFindCard;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CardDataProvider implements ArgumentInterface
{
    private CurrentCustomer $currentCustomer;

    private ApplicationInterface $application;

    public function __construct(
        CurrentCustomer $currentCustomer,
        ApplicationInterface $application
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->application = $application;
    }

    public function getCardNumber(): string
    {
        $command = new GetCardByCustomerCommand((int)$this->currentCustomer->getCustomerId());
        // TODO validate command?
        try {
            return $this->application->getCardByCustomer($command)->cardNumber()->asString();
        } catch (CouldNotFindCard $exception) {
            return '';
        }
    }
}
