<?php

namespace MageClever\ColorMapping\Observer\Backend;

use MageClever\ColorMapping\Helper\Data as DataHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ColorRelationshipDeleteAfter implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    protected DataHelper $_dataHelper;

    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $_productCollectionFactory;

    /**
     * @var Attribute
     */
    protected Attribute $_eavAttribute;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $_resourceConnection;

    /**
     * @param DataHelper $dataHelper
     * @param ProductCollectionFactory $productCollectionFactory
     * @param Attribute $eavAttribute
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        DataHelper               $dataHelper,
        ProductCollectionFactory $productCollectionFactory,
        Attribute                $eavAttribute,
        ResourceConnection       $resourceConnection
    )
    {
        $this->_dataHelper = $dataHelper;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_eavAttribute = $eavAttribute;
        $this->_resourceConnection = $resourceConnection;
    }

    public function execute(Observer $observer)
    {
        $colorRelationship = $observer->getEvent()->getData('color_relationship');
        $parentColorOptionId = $colorRelationship->getData('parent_color_option_id');

        $targetParentAttrCode = $this->_dataHelper->getDeclaredParentColor();
        $targetChildAttrCode = $this->_dataHelper->getDeclaredChildColor();

        if (!$targetParentAttrCode || !$targetChildAttrCode) {
            return;
        }

        $attributeId = $this->_eavAttribute->getIdByCode(Product::ENTITY, $targetParentAttrCode);

        $productCollection = $this->_productCollectionFactory->create();
        $productCollection
            ->addFieldToSelect($targetParentAttrCode)
            ->addAttributeToFilter($targetParentAttrCode, ['finset' => $parentColorOptionId]);
        if (!$productCollection->getSize()) {
            return;
        }

        $targetData = [];
        /* @var $_product \Magento\Catalog\Model\Product */
        foreach ($productCollection as $_product) {
            $currentParentColor = explode(',', $_product->getData($targetParentAttrCode));
            if (($key = array_search($parentColorOptionId, $currentParentColor)) !== false) {
                unset($currentParentColor[$key]);
                $newParentColor = implode(',', $currentParentColor);
                $targetData[] = [
                    'attribute_id' => $attributeId,
                    'entity_id' => $_product->getId(),
                    'store_id' => 0,
                    'value' => $newParentColor
                ];
            }
        }
        if (!empty($targetData)) {
            try {
                $connection = $this->_resourceConnection->getConnection();
                $tableName = $connection->getTableName('catalog_product_entity_varchar');
                $connection->insertOnDuplicate($tableName, $targetData);
            } catch (\Exception $e) {

            }
        }
    }
}
