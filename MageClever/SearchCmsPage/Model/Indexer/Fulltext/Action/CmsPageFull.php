<?php

namespace MageClever\SearchCmsPage\Model\Indexer\Fulltext\Action;

use Generator;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as CmsPageCollectionFactory;

class CmsPageFull
{
    /**
     * @var CmsPageCollectionFactory
     */
    protected $_cmsPageCollectionFactory;

    /**
     * @param CmsPageCollectionFactory $cmsPageCollectionFactory
     */
    public function __construct(
        CmsPageCollectionFactory $cmsPageCollectionFactory
    )
    {
        $this->_cmsPageCollectionFactory = $cmsPageCollectionFactory;
    }

    /**
     * @param $storeId
     * @param null $pageIds
     * @return Generator
     */
    public function rebuildStoreIndex($storeId, $pageIds = null)
    {
        if ($pageIds !== null) {
            $pageIds = array_unique($pageIds);
        }
        $targetStoreIds = [0];
        if ($storeId) {
            $targetStoreIds[] = $storeId;
        }
        $collection = $this->_cmsPageCollectionFactory->create();
        $collection->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('store_id', ['in' => $targetStoreIds]);

        if (!empty($pageIds)) {
            $collection->addFieldToFilter('page_id', ['in' => $pageIds]);
        }
        /* @var $_page Page */
        foreach ($collection as $_page) {
            $data = $_page->getData();
            if (!empty($data['page_id'])) {
                $data['page_id'] = (int)$data['page_id'];
            }
            yield $_page->getId() => $data;
        }
    }
}
