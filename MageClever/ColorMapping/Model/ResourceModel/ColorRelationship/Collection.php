<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Model\ResourceModel\ColorRelationship;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'colorrelationship_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MageClever\ColorMapping\Model\ColorRelationship::class,
            \MageClever\ColorMapping\Model\ResourceModel\ColorRelationship::class
        );
    }
}

