<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Plugin\Amasty\ElasticSearch\Model\Client;

use Amasty\ElasticSearch\Model\Client\Elasticsearch as AmastyElasticsearchClient;
use MageClever\HideProductByType\Helper\Data;
use Magento\Framework\App\Request\Http;

class Elasticsearch
{
    /**
     * @var Data
     */
    protected Data $_helper;

    /**
     * @var Http
     */
    protected Http $_request;

    /**
     * @param Data $data
     * @param Http $request
     */
    public function __construct(
        Data $data,
        Http $request
    )
    {
        $this->_helper = $data;
        $this->_request = $request;
    }

    /**
     * @param AmastyElasticsearchClient $subject
     * @param $query
     * @return array[]
     */
    public function beforeSearch(AmastyElasticsearchClient $subject, $query): array
    {
        $isEnabledHideProduct = $this->_helper->isEnabledHideProduct();
        $hideProductTypeConfig = $this->_helper->getConfigHideProductType();
        $hideInPage = $this->_helper->getHideProductInPage();

        if (!$isEnabledHideProduct || empty($hideProductTypeConfig) || empty($hideInPage)) {
            return [$query];
        }

        $fullActionName = $this->_request->getFullActionName();
        if (!in_array($fullActionName, $hideInPage)) {
            return [$query];
        }

        foreach ($hideProductTypeConfig as $prdTypeId) {
            $query['body']['query']['bool']['must_not'][] = [
                'match' => [
                    'product_type_id' => $prdTypeId
                ],
            ];
        }
        return [$query];
    }
}

