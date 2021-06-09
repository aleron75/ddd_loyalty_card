<?php declare(strict_types=1);

namespace Ddd\Loyalty\Controller\Card;

use Ddd\Loyalty\Application\ApplicationInterface;
use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;
use Ddd\Loyalty\Application\Command\RegisterCardValidator as RegisterCardCommandValidator;
use Ddd\Loyalty\Domain\Model\Card\CouldNotRegisterCard;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Save extends Action implements HttpPostActionInterface
{
    private CurrentCustomer $currentCustomer;

    private ApplicationInterface $application;

    private RegisterCardCommandValidator $registerCardCommandValidator;

    public function __construct(
        Context $context,
        CurrentCustomer $currentCustomer,
        ApplicationInterface $application,
        RegisterCardCommandValidator $registerCardCommandValidator
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->application = $application;
        $this->registerCardCommandValidator = $registerCardCommandValidator;
        parent::__construct($context);
    }

    /**
     * @inheridoc
     */
    public function execute()
    {
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $cardNumber = $this->getRequest()->getParam('card_number');
        $command = new RegisterCardCommand((int)$this->currentCustomer->getCustomerId(), $cardNumber);

        $errors = $this->registerCardCommandValidator->validate($command);
        if (count($errors) === 0) {
            try {
                $this->application->registerCard($command);
                $this->messageManager->addSuccessMessage(__('The card number was updated.'));
            } catch (CouldNotRegisterCard $exception) {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
            }
        }

        foreach ($errors as $error) {
            $this->messageManager->addErrorMessage(__($error));
        }

        return $redirectResult->setPath('loyalty/card/index');
    }
}
