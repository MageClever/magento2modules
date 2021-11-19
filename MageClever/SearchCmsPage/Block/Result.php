<?php

namespace MageClever\SearchCmsPage\Block;

use Magento\Framework\View\Element\Template;
use MageClever\SearchCmsPage\Model\Search;
use Magento\Search\Model\QueryFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Cms\Model\ResourceModel\Page\Collection;

class Result extends Template
{
    /**
     * @var Search
     */
    protected $_search;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Template\Context $context
     * @param Search $search
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context  $context,
        Search            $search,
        CollectionFactory $collectionFactory,
        array             $data = []
    )
    {
        $this->_search = $search;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection|void
     */
    public function getCmsPageCollection()
    {
        $query = $this->getRequest()->getParam(QueryFactory::QUERY_VAR_NAME);
        $searchResult = $this->_search->search($query);

        if (empty($searchResult)) {
            return;
        }

        $ids = [];
        foreach ($searchResult as $_item) {
            $ids[] = (int)$_item->getId();
        }

        /* @var  $collection Collection */
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('page_id', ['in' => $ids]);
        return $collection;
    }
}
