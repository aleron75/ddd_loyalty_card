<?php declare(strict_types=1);

namespace Ddd\Loyalty\Model\ResourceModel\Card;

use Ddd\Loyalty\Model\Card;
use Ddd\Loyalty\Model\ResourceModel\Card as CardResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ddd_card_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Card::class, CardResource::class);
    }
}
