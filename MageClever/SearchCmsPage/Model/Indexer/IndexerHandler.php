<?php

namespace MageClever\SearchCmsPage\Model\Indexer;

use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\Elasticsearch\Model\Adapter\Elasticsearch as ElasticsearchAdapter;
use Magento\Elasticsearch\Model\Adapter\Index\IndexNameResolver;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Indexer\IndexStructureInterface;
use Magento\Framework\Indexer\SaveHandler\Batch;

class IndexerHandler extends \Magento\Elasticsearch\Model\Indexer\IndexerHandler
{
    private const DEPLOYMENT_CONFIG_INDEXER_BATCHES = 'indexer/batch_size/';

    /**
     * @var ScopeResolverInterface
     */
    private $scopeResolver;

    /**
     * @var DeploymentConfig|mixed
     */
    private $deploymentConfig;

    /**
     * @var Batch
     */
    private $batch;

    /**
     * @var ElasticsearchAdapter
     */
    private $adapter;

    /**
     * @var IndexNameResolver
     */
    private $indexNameResolver;

    /**
     * @var array
     */
    private $data;

    private $batchSize;

    /**
     * @param IndexStructureInterface $indexStructure
     * @param ElasticsearchAdapter $adapter
     * @param IndexNameResolver $indexNameResolver
     * @param Batch $batch
     * @param ScopeResolverInterface $scopeResolver
     * @param array $data
     * @param int $batchSize
     * @param DeploymentConfig|null $deploymentConfig
     */
    public function __construct(
        IndexStructureInterface $indexStructure,
        ElasticsearchAdapter    $adapter,
        IndexNameResolver       $indexNameResolver,
        Batch                   $batch, ScopeResolverInterface $scopeResolver,
        array                   $data = [],
                                $batchSize = \Magento\Elasticsearch\Model\Indexer\IndexerHandler::DEFAULT_BATCH_SIZE,
        ?DeploymentConfig       $deploymentConfig = null
    )
    {
        $this->scopeResolver = $scopeResolver;
        $this->deploymentConfig = $deploymentConfig ?: ObjectManager::getInstance()->get(DeploymentConfig::class);
        $this->batch = $batch;
        $this->adapter = $adapter;
        $this->indexNameResolver = $indexNameResolver;
        $this->data = $data;

        parent::__construct($indexStructure, $adapter, $indexNameResolver, $batch, $scopeResolver, $data, $batchSize, $deploymentConfig);
    }


    /**
     * @inheritdoc
     */
    public function saveIndex($dimensions, \Traversable $documents)
    {
        $dimension = current($dimensions);
        $scopeId = $this->scopeResolver->getScope($dimension->getValue())->getId();

        $this->batchSize = $this->deploymentConfig->get(
                self::DEPLOYMENT_CONFIG_INDEXER_BATCHES . Fulltext::INDEXER_ID . '/elastic_save'
            ) ?? $this->batchSize;

        foreach ($this->batch->getItems($documents, $this->batchSize) as $documentsBatch) {
            $this->adapter->addDocs($documentsBatch, $scopeId, $this->getIndexerId());
        }
        $this->adapter->updateAlias($scopeId, $this->getIndexerId());
        return $this;
    }

    private function getIndexerId()
    {
        return $this->indexNameResolver->getIndexMapping($this->data['indexer_id']);
    }

}
