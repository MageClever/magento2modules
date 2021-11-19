<?php

namespace MageClever\SearchCmsPage\Model\Indexer;

use InvalidArgumentException;
use MageClever\SearchCmsPage\Model\Indexer\Fulltext\Action\CmsPageFull;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Indexer\Model\ProcessManager;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Store\Model\StoreDimensionProvider;
use MageClever\SearchCmsPage\Model\Indexer\Fulltext\Action\CmsPageFullFactory;

class CmsPageFulltext implements IndexerActionInterface, MviewActionInterface, DimensionalIndexerInterface
{
    const INDEXER_ID = 'mageclever_cms_pages_indexer';

    /**
     * @var DimensionProviderInterface
     */
    private $dimensionProvider;

    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * @var IndexerHandlerFactory
     */
    private $indexerHandlerFactory;

    /**
     * @var array index structure
     */
    protected array $data;

    /**
     * @var CmsPageFull
     */
    private $fullAction;

    /**
     * @param DimensionProviderInterface $dimensionProvider
     * @param IndexerHandlerFactory $indexerHandlerFactory
     * @param CmsPageFullFactory $cmsPageFullActionFactory
     * @param array $data
     * @param ProcessManager $processManager
     */
    public function __construct(
        DimensionProviderInterface $dimensionProvider,
        IndexerHandlerFactory      $indexerHandlerFactory,
        CmsPageFullFactory         $cmsPageFullActionFactory,
        array                      $data,
        ProcessManager             $processManager
    )
    {
        $this->dimensionProvider = $dimensionProvider;
        $this->data = $data;
        $this->fullAction = $cmsPageFullActionFactory->create(['data' => $data]);
        $this->processManager = $processManager;
        $this->indexerHandlerFactory = $indexerHandlerFactory;
    }

    /**
     * @param int[] $entityIds
     */
    public function execute($entityIds)
    {
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new \ArrayIterator($entityIds));
        }
    }

    public function executeByDimensions(array $dimensions, \Traversable $entityIds = null)
    {
        if (count($dimensions) > 1 || !isset($dimensions[StoreDimensionProvider::DIMENSION_NAME])) {
            throw new InvalidArgumentException('Indexer "' . self::INDEXER_ID . '" support only Store dimension');
        }
        $storeId = $dimensions[StoreDimensionProvider::DIMENSION_NAME]->getValue();
        $saveHandler = $this->indexerHandlerFactory->create(
            [
                'data' => $this->data,
            ]
        );
        if (null === $entityIds) {
            $saveHandler->cleanIndex($dimensions);
            $saveHandler->saveIndex($dimensions, $this->fullAction->rebuildStoreIndex($storeId));
        } else {
            $saveHandler->deleteIndex($dimensions, $entityIds);
            $pageIds = iterator_to_array($entityIds);
            $saveHandler->saveIndex($dimensions, $this->fullAction->rebuildStoreIndex($storeId, $pageIds));
        }
    }

    public function executeFull()
    {
        $userFunctions = [];
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $userFunctions[] = function () use ($dimension) {
                $this->executeByDimensions($dimension);
            };
        }
        $this->processManager->execute($userFunctions);
    }

    /**
     * @param array $ids
     */
    public function executeList(array $ids)
    {
        $this->execute($ids);
    }

    /**
     * @param int $id
     */
    public function executeRow($id)
    {
        $this->execute([$id]);
    }
}
