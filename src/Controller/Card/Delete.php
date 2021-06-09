<?php declare(strict_types=1);

namespace Ddd\Loyalty\Controller\Card;

use Ddd\Loyalty\Application\ApplicationInterface;
use Ddd\Loyalty\Application\Command\DeleteCard as DeleteCardCommand;
use Ddd\Loyalty\Domain\Model\Card\CouldNotDeleteCard;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action implements HttpPostActionInterface
{
    private CurrentCustomer $currentCustomer;

    private ApplicationInterface $application;

    public function __construct(
        Context $context,
        CurrentCustomer $currentCustomer,
        ApplicationInterface $application
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->application = $application;
        parent::__construct($context);
    }

    /**
     * @inheridoc
     */
    public function execute()
    {
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $command = new DeleteCardCommand((int)$this->currentCustomer->getCustomerId());

        try {
            $this->application->deleteCardByCustomer($command);
            $this->messageManager->addSuccessMessage(__('The card was successfully deleted.'));
        } catch (CouldNotDeleteCard $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
        }

        return $redirectResult->setPath('loyalty/card/index');
    }
}
