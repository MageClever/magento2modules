<?php

declare(strict_types=1);

namespace MageClever\HideProductByType\Model\Adapter\DataMapper;

use Magento\AdvancedSearch\Model\Adapter\DataMapper\AdditionalFieldsProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class CustomDataProvider implements AdditionalFieldsProviderInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $productIds, $storeId): array
    {
        $productTypeId = $this->getProductTypeIds($productIds);
        $fields = [];
        foreach ($productIds as $productId) {
            if (empty($productTypeId[$productId])) {
                continue;
            }
            $data = $productTypeId[$productId];
            $fields[$productId] = ["product_type_id" => $data];
        }
        return $fields;
    }

    /**
     * @param $productIds
     * @return array
     */
    private function getProductTypeIds($productIds): array
    {
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToSelect('type_id')
            ->addFieldToFilter('entity_id', ['in' => $productIds]);
        $productTypeId = [];
        foreach ($collection as $_product) {
            $productTypeId[$_product->getId()] = $_product->getData('type_id');
        }
        return $productTypeId;
    }
}
