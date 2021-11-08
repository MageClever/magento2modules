<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Plugin\Magento\Elasticsearch7\Model\Client;

use MageClever\HideProductByType\Helper\Data;
use Magento\Elasticsearch7\Model\Client\Elasticsearch as ElasticsearchClient;
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
     * @param ElasticsearchClient $subject
     * @param array $query
     * @return array[]
     */
    public function beforeQuery(ElasticsearchClient $subject, array $query): array
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

