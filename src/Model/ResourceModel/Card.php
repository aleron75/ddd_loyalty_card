<?php declare(strict_types=1);

namespace Ddd\Loyalty\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

final class Card extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ddd_card_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('ddd_card', 'card_id');
        #$this->_useIsObjectNew = true;
    }
}
