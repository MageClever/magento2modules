<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Model;

use MageClever\ColorMapping\Api\ColorRelationshipRepositoryInterface;
use MageClever\ColorMapping\Api\Data\ColorRelationshipInterfaceFactory;
use MageClever\ColorMapping\Api\Data\ColorRelationshipSearchResultsInterfaceFactory;
use MageClever\ColorMapping\Model\ResourceModel\ColorRelationship as ResourceColorRelationship;
use MageClever\ColorMapping\Model\ResourceModel\ColorRelationship\CollectionFactory as ColorRelationshipCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class ColorRelationshipRepository implements ColorRelationshipRepositoryInterface
{

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $dataObjectProcessor;

    private $collectionProcessor;

    private $storeManager;

    protected $dataColorRelationshipFactory;

    protected $searchResultsFactory;

    protected $colorRelationshipFactory;

    protected $colorRelationshipCollectionFactory;


    /**
     * @param ResourceColorRelationship $resource
     * @param ColorRelationshipFactory $colorRelationshipFactory
     * @param ColorRelationshipInterfaceFactory $dataColorRelationshipFactory
     * @param ColorRelationshipCollectionFactory $colorRelationshipCollectionFactory
     * @param ColorRelationshipSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceColorRelationship $resource,
        ColorRelationshipFactory $colorRelationshipFactory,
        ColorRelationshipInterfaceFactory $dataColorRelationshipFactory,
        ColorRelationshipCollectionFactory $colorRelationshipCollectionFactory,
        ColorRelationshipSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->colorRelationshipFactory = $colorRelationshipFactory;
        $this->colorRelationshipCollectionFactory = $colorRelationshipCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataColorRelationshipFactory = $dataColorRelationshipFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
    ) {
        /* if (empty($colorRelationship->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $colorRelationship->setStoreId($storeId);
        } */
        
        $colorRelationshipData = $this->extensibleDataObjectConverter->toNestedArray(
            $colorRelationship,
            [],
            \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface::class
        );
        
        $colorRelationshipModel = $this->colorRelationshipFactory->create()->setData($colorRelationshipData);
        
        try {
            $this->resource->save($colorRelationshipModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the colorRelationship: %1',
                $exception->getMessage()
            ));
        }
        return $colorRelationshipModel;
    }

    /**
     * {@inheritdoc}
     */
    public function get($colorRelationshipId)
    {
        $colorRelationship = $this->colorRelationshipFactory->create();
        $this->resource->load($colorRelationship, $colorRelationshipId);
        if (!$colorRelationship->getId()) {
            throw new NoSuchEntityException(__('ColorRelationship with id "%1" does not exist.', $colorRelationshipId));
        }
        return $colorRelationship;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->colorRelationshipCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
    ) {
        try {
            $colorRelationshipModel = $this->colorRelationshipFactory->create();
            $this->resource->load($colorRelationshipModel, $colorRelationship->getColorrelationshipId());
            $this->resource->delete($colorRelationshipModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ColorRelationship: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($colorRelationshipId)
    {
        return $this->delete($this->get($colorRelationshipId));
    }
}

