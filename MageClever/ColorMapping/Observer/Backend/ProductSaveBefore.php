<?php

namespace MageClever\ColorMapping\Observer\Backend;

use MageClever\ColorMapping\Helper\Data as DataHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageClever\ColorMapping\Model\ResourceModel\ColorRelationship\CollectionFactory as ColorRelationshipCollectionFactory;

class ProductSaveBefore implements ObserverInterface
{
    /**
     * @var ColorRelationshipCollectionFactory
     */
    protected ColorRelationshipCollectionFactory $_colorRelationshipCollectionFactory;

    /**
     * @var DataHelper
     */
    protected DataHelper $_dataHelper;

    /**
     * @param ColorRelationshipCollectionFactory $colorRelationshipCollectionFactory
     * @param DataHelper $dataHelper
     */
    public function __construct(
        ColorRelationshipCollectionFactory $colorRelationshipCollectionFactory,
        DataHelper                         $dataHelper
    )
    {
        $this->_colorRelationshipCollectionFactory = $colorRelationshipCollectionFactory;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $isEnabled = $this->_dataHelper->isEnabled();
        if (!$isEnabled) {
            return;
        }
        $targetParentAttrCode = $this->_dataHelper->getDeclaredParentColor();
        $targetChildAttrCode = $this->_dataHelper->getDeclaredChildColor();

        if (!$targetParentAttrCode || !$targetChildAttrCode) {
            return;
        }

        /* @var $product Product */
        $product = $observer->getEvent()->getProduct();
        $productType = $product->getTypeId();
        $productColor = [];
        if ($productType == Configurable::TYPE_CODE) {
            $childrenProduct = $product->getTypeInstance()->getUsedProducts($product);
            /* @var $_childPrd Product */
            foreach ($childrenProduct as $_childPrd) {
                if (!$_childPrd->getData($targetChildAttrCode)) {
                    continue;
                }
                $productColor[] = $_childPrd->getData($targetChildAttrCode);
            }
        }
        if ($productType == Type::TYPE_SIMPLE) {
            $productColor[] = $product->getData($targetChildAttrCode);
        }

        if (empty($productColor)) {
            return;
        }

        $colorRelationshipCollection = $this->_colorRelationshipCollectionFactory->create();
        $colorRelationshipCollection->addFieldToSelect(['parent_color_option_id', 'child_color_option_id']);
        if (!$colorRelationshipCollection->getSize()) {
            return;
        }

        $targetParentColor = [];
        foreach ($colorRelationshipCollection->getData() as $_colorRelationship) {
            if (empty($_colorRelationship['child_color_option_id']) || empty($_colorRelationship['parent_color_option_id'])) {
                continue;
            }
            $colorOfParent = explode(',', $_colorRelationship['child_color_option_id']);
            $scanning = array_intersect($productColor, $colorOfParent);
            if (count($scanning) == 0) {
                continue;
            }
            $targetParentColor[] = $_colorRelationship['parent_color_option_id'];
        }
        if (empty($targetParentColor)) {
            return;
        }

        $product->setData($targetParentAttrCode, $targetParentColor);
    }
}
