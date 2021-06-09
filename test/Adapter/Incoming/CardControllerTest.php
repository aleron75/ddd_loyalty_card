<?php declare(strict_types=1);

namespace Ddd\Loyalty\Adapter\Incoming;

use Ddd\Loyalty\Application\ApplicationInterface;
use Ddd\Loyalty\Application\Command\DeleteCard as DeleteCardCommand;
use Ddd\Loyalty\Application\Command\GetCardByCustomer as GetCardByCustomerCommand;
use Ddd\Loyalty\Application\Command\RegisterCard as RegisterCardCommand;
use Ddd\Loyalty\Domain\Model\Card\Card;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\HTTP\PhpEnvironment\Request;

/**
 * @magentoDbIsolation enabled
 * @magentoCache config disabled
 * @magentoCache block_html disabled
 * @magentoCache full_page disabled
 */
final class CardControllerTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @test
     */
    public function it_correctly_invokes_getCardByCustomer(): void
    {
        $customerId = 1;
        $this->login($customerId);

        $application = $this->createMock(ApplicationInterface::class);
        $application->expects($this->once())
            ->method('getCardByCustomer')
            ->with(new GetCardByCustomerCommand($customerId))
            ->willReturn((Card::fromState((string)$customerId, '0123456789')));

        $this->_objectManager->addSharedInstance($application, \Ddd\Loyalty\Application\Application::class);

        $this->getRequest()->setMethod(Request::METHOD_GET);
        $this->dispatch('loyalty/card/index');
    }

    /**
     * @test
     */
    public function it_correctly_invokes_registerCard(): void
    {
        $cardNumber = '0123456789';
        $customerId = 1;
        $this->login($customerId);

        $application = $this->createMock(ApplicationInterface::class);
        $application->expects($this->once())
            ->method('registerCard')
            ->with(new RegisterCardCommand($customerId, $cardNumber));

        $this->_objectManager->addSharedInstance($application, \Ddd\Loyalty\Application\Application::class);

        $this->getRequest()->setMethod(Request::METHOD_POST);
        $this->getRequest()->setPostValue([
            'card_number' => $cardNumber,
            'form_key' => $this->_objectManager->get(FormKey::class)->getFormKey(),
        ]);

        $this->dispatch('loyalty/card/save');
    }

    /**
     * @test
     */
    public function it_correctly_invokes_deleteCardByCustomer(): void
    {
        $customerId = 1;
        $this->login($customerId);

        $application = $this->createMock(ApplicationInterface::class);
        $application->expects($this->once())
            ->method('deleteCardByCustomer')
            ->with(new DeleteCardCommand($customerId));

        $this->_objectManager->addSharedInstance($application, \Ddd\Loyalty\Application\Application::class);

        $this->getRequest()->setMethod(Request::METHOD_POST);
        $this->getRequest()->setPostValue([
            'customer_id' => $customerId,
            'form_key' => $this->_objectManager->get(FormKey::class)->getFormKey(),
        ]);

        $this->dispatch('loyalty/card/delete');
    }

    protected function login($customerId)
    {
        $session = $this->_objectManager->get(CustomerSession::class);
        $session->loginById($customerId);
    }
}
