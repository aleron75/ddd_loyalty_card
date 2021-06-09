<?php declare(strict_types=1);

namespace Ddd\Loyalty\Controller\Card;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @inheridoc
     */
    public function execute()
    {
        $page = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $page;
    }
}
