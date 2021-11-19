<?php

namespace MageClever\SearchCmsPage\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use MageClever\SearchCmsPage\Model\Indexer\CmsPageFulltext;

class CmsPageSaveAfter implements ObserverInterface
{
    /**
     * @var IndexerRegistry
     */
    protected $_indexerRegistry;

    /**
     * @param $indexerRegistry
     */
    public function __construct(
        IndexerRegistry $indexerRegistry
    )
    {
        $this->_indexerRegistry = $indexerRegistry;
    }

    public function execute(Observer $observer)
    {
        $cmsPage = $observer->getEvent()->getObject();
        $targetId = $cmsPage->getId();
        $this->getIndexer()->reindexRow($targetId);
    }

    /**
     * @return IndexerInterface
     */
    public function getIndexer()
    {
        return $this->_indexerRegistry->get(CmsPageFulltext::INDEXER_ID);
    }
}
