<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Model\ResourceModel;

class ColorRelationship extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageclever_colormapping_colorrelationship', 'colorrelationship_id');
    }
}

