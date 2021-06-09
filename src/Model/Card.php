<?php declare(strict_types=1);

namespace Ddd\Loyalty\Model;

use Magento\Framework\Model\AbstractModel;

final class Card extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ddd_card_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Card::class);
    }
}
