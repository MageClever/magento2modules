<?php

namespace MageClever\ColorMapping\Observer\Backend;

use MageClever\ColorMapping\Model\ColorRelationship;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as TypeConfigurable;
use Magento\Framework\App\ResourceConnection;
use MageClever\ColorMapping\Helper\Data as DataHelper;

class ColorRelationshipSaveAfter implements ObserverInterface
{
    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $_productCollectionFactory;

    /**
     * @var Configurable
     */
    protected Configurable $_configurableProductType;

    /**
     * @var Visibility
     */
    protected Visibility $_productVisibility;

    /**
     * @var Status
     */
    protected Status $_productStatus;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $_resourceConnection;

    /**
     * @var Attribute
     */
    protected Attribute $_eavAttribute;

    /**
     * @var DataHelper
     */
    protected DataHelper $_dataHelper;

    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param Configurable $configurableProductType
     * @param Status $productStatus
     * @param Visibility $productVisibility
     * @param ResourceConnection $resourceConnection
     * @param Attribute $eavAttribute
     * @param DataHelper $dataHelper
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        Configurable             $configurableProductType,
        Status                   $productStatus,
        Visibility               $productVisibility,
        ResourceConnection       $resourceConnection,
        Attribute                $eavAttribute,
        DataHelper               $dataHelper
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_configurableProductType = $configurableProductType;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        $this->_resourceConnection = $resourceConnection;
        $this->_eavAttribute = $eavAttribute;
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

        /* @var $colorRelationship ColorRelationship */
        $colorRelationship = $observer->getEvent()->getData('color_relationship');
        $parentColorOptionId = $colorRelationship->getData('parent_color_option_id');

        $oldColorOptionId = $colorRelationship->getOrigData('child_color_option_id') ? explode(',', $colorRelationship->getOrigData('child_color_option_id')) : [];
        $newColorOptionId = $colorRelationship->getData('child_color_option_id') ? explode(',', $colorRelationship->getData('child_color_option_id')) : [];
        sort($oldColorOptionId);
        sort($newColorOptionId);

        if ($oldColorOptionId == $newColorOptionId) { /* Skip if nothing change */
            return;
        }

        $targetParentAttrCode = $this->_dataHelper->getDeclaredParentColor();
        $targetChildAttrCode = $this->_dataHelper->getDeclaredChildColor();

        if (!$targetParentAttrCode || !$targetChildAttrCode) {
            return;
        }

        $productCollection = $this->_productCollectionFactory->create();
        $productCollection
            ->addAttributeToSelect([$targetChildAttrCode, $targetParentAttrCode])
            ->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()])
            ->setVisibility($this->_productVisibility->getVisibleInSiteIds())
            ->addFieldToFilter('type_id', ['in' => [Type::TYPE_SIMPLE, TypeConfigurable::TYPE_CODE]]);

        if (!$productCollection->getSize()) {
            return;
        }
        $attributeId = $this->_eavAttribute->getIdByCode(Product::ENTITY, $targetParentAttrCode);
        $connection = $this->_resourceConnection->getConnection();
        $tableName = $connection->getTableName('catalog_product_entity_varchar');

        try {
            /* @var Product $_product */
            $insertPrdParentClr = [];
            $deletePrdParentClr = [];

            foreach ($productCollection as $_product) {
                $productType = $_product->getTypeId();
                $productId = $_product->getId();
                $productParentColor = $_product->getData($targetParentAttrCode) ? explode(',', $_product->getData($targetParentAttrCode)) : [];


                /* CHECKING FOR CONFIGURABLE PRODUCT */
                if ($productType == TypeConfigurable::TYPE_CODE) {
                    $childrenProduct = $_product->getTypeInstance()->getUsedProducts($_product);
                    /* @var $_childPrd Product */
                    $currentProdColorOptionId = [];
                    foreach ($childrenProduct as $_childPrd) {
                        if (!$_childPrd->getData($targetChildAttrCode)) {
                            continue;
                        }
                        $currentProdColorOptionId[] = $_childPrd->getData($targetChildAttrCode);
                    }
                    $scanning = array_intersect($currentProdColorOptionId, $newColorOptionId);
                    if (!count($scanning)) { /* If product color(s) NOT in new color(s) value of parent */
                        if (($key = array_search($parentColorOptionId, $productParentColor)) !== false) {
                            unset($productParentColor[$key]);
                        }
                    } else { /* If product color(s) in new color(s) value of parent */
                        if (!in_array($parentColorOptionId, $productParentColor)) {
                            $productParentColor[] = $parentColorOptionId;
                        }
                    }
                }
                /* END CHECKING FOR CONFIGURABLE PRODUCT */

                /* CHECKING FOR SIMPLE PRODUCT */
                if ($productType == Type::TYPE_SIMPLE) {
                    $parentProductIds = $this->_configurableProductType->getParentIdsByChild($productId);
                    if (!empty($parentProductIds)) {
                        continue; /* Ignore if product has parents */
                    }

                    $currentProdColorOptionId = $_product->getData($targetChildAttrCode);
                    if (!in_array($currentProdColorOptionId, $newColorOptionId)) { /* If product color(s) NOT in new color(s) value of parent */
                        if (($key = array_search($parentColorOptionId, $productParentColor)) !== false) {
                            unset($productParentColor[$key]);
                        }
                    } else { /* If product color(s) in new color(s) value of parent */
                        if (!in_array($parentColorOptionId, $productParentColor)) {
                            $productParentColor[] = $parentColorOptionId;
                        }
                    }
                }
                /* END CHECKING FOR SIMPLE PRODUCT */

                if (empty($productParentColor)) { /* Prepare delete data */
                    $deletePrdParentClr[] = $productId;
                } else {
                    $insertPrdParentClr[] = [
                        'attribute_id' => $attributeId,
                        'entity_id' => $productId,
                        'store_id' => 0,
                        'value' => implode(',', $productParentColor)];
                }
            }
        } catch (\Exception $e) {

        }

        /* Going delete unnecessary records */
        if (!empty($deletePrdParentClr)) {
            $whereConditions = [
                $connection->quoteInto('attribute_id = ?', $attributeId),
                $connection->quoteInto('entity_id IN (?)', $deletePrdParentClr)
            ];
            $connection->delete($tableName, $whereConditions);
        }

        /* Going add/update records */
        if (!empty($insertPrdParentClr)) {
            try {
                $connection->insertOnDuplicate($tableName, $insertPrdParentClr);
            } catch (\Exception $e) {

            }
        }
    }

}
