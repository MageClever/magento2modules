<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Plugin\Amasty\ElasticSearch\Model\Indexer\Data\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Amasty\ElasticSearch\Model\Indexer\Data\Product\ProductDataMapper as AmastyProductDataMapper;

class ProductDataMapper
{
    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $_collectionFactory;

    /**
     * @param ProductCollectionFactory $collectionFactory
     */
    public function __construct(ProductCollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @param AmastyProductDataMapper $subject
     * @param $result
     * @param array $documentData
     * @param $storeId
     * @param array $context
     * @return mixed
     */
    public function afterMap(AmastyProductDataMapper $subject, $result, array $documentData, $storeId, array $context = [])
    {
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToSelect('type_id')
            ->addFieldToFilter('entity_id', ['in' => array_keys($result)]);
        $productTypeId = [];
        foreach ($collection as $_product) {
            $productTypeId[$_product->getId()] = $_product->getData('type_id');
        }

        foreach ($result as $_productId => $_document) {
            if (empty($productTypeId[$_productId])) {
                continue;
            }
            $result[$_productId]['product_type_id'] = $productTypeId[$_productId];
        }

        return $result;
    }
}

