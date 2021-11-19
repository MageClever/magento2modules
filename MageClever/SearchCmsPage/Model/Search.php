<?php

namespace MageClever\SearchCmsPage\Model;

//use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverFactory;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverInterface;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Search\Api\SearchInterface;
use Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverFactory;

class Search
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var CollectionFactory
     */
    private $cmsCollectionFactory;
    /**
     * @var SearchCriteriaResolverFactory
     */
    private $searchCriteriaResolverFactory;
    /**
     * @var SearchInterface
     */
    private $search;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var array
     */
    protected $searchResult;

    /**
     * Search constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchCriteriaResolverFactory $searchCriteriaResolverFactory
     * @param FilterBuilder $filterBuilder
     * @param SearchInterface $search
     * @param CollectionFactory $cmsCollectionFactory
     */
    public function __construct(
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        SearchCriteriaResolverFactory $searchCriteriaResolverFactory,
        FilterBuilder                 $filterBuilder,
        SearchInterface               $search,
        CollectionFactory             $cmsCollectionFactory
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cmsCollectionFactory = $cmsCollectionFactory;
        $this->searchCriteriaResolverFactory = $searchCriteriaResolverFactory;
        $this->search = $search;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @param $query
     * @return DocumentInterface[]
     */
    public function search($query)
    {
        $searchCriteria = $this->getSearchCriteriaResolver($query)->resolve();
        $this->searchResult = $this->search->search($searchCriteria);
        return $this->searchResult->getItems();
    }

    /**
     * @param $query
     * @return SearchCriteriaResolverInterface
     */
    private function getSearchCriteriaResolver($query): SearchCriteriaResolverInterface
    {
        $this->filterBuilder->setField('search_term');
        $this->filterBuilder->setValue($query);
        $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());

        return $this->searchCriteriaResolverFactory->create(
            [
                'builder' => $this->searchCriteriaBuilder,
                'collection' => $this->cmsCollectionFactory->create(),
                'searchRequestName' => 'mageclever_cms_search_container',
                'orders' => ['_score' => 'DESC'],
                'currentPage' => 1,
                'size' => 100
            ]
        );
    }


}
