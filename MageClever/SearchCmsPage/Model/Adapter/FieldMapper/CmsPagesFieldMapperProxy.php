<?php

declare(strict_types=1);

namespace MageClever\SearchCmsPage\Model\Adapter\FieldMapper;

use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

class CmsPagesFieldMapperProxy implements FieldMapperInterface
{
    /**
     * @param string $attributeCode
     * @param array $context
     * @return string
     */
    public function getFieldName($attributeCode, $context = [])
    {
        return $attributeCode;
    }

    /**
     * @param array $context
     * @return array
     */
    public function getAllAttributesTypes($context = [])
    {
        return [];
    }
}
