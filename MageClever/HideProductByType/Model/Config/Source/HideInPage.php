<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class HideInPage implements ArrayInterface
{

    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'catalogsearch_result_index', 'label' => __('Search result page')],
            ['value' => 'catalog_category_view', 'label' => __('Product listing page')],
            ['value' => 'catalog_product_view', 'label' => __('Product detail page')]
        ];
    }

}

