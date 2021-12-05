<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class ProductAttributes implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * @param CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        CollectionFactory $attributeCollectionFactory
    )
    {
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $attributeData = [];
        $collection = $this->_attributeCollectionFactory->create();
        foreach ($collection as $_attr) {
            $attributeData[] = [
                'value' => $_attr->getAttributeCode(),
                'label' => $_attr->getStoreLabel()
            ];
        }
        return $attributeData;
    }

}

