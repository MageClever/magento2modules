<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;

class ProductTypeId implements ArrayInterface
{
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $_productTypeConfig;


    /**
     * @param ConfigInterface $productTypeConfig
     */
    public function __construct(
        ConfigInterface $productTypeConfig
    )
    {
        $this->_productTypeConfig = $productTypeConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $productTypes = $this->_productTypeConfig->getAll();
        $data = [];
        foreach ($productTypes as $typeId => $type) {
            $data[] = [
                'value' => $typeId,
                'label' => $type['label']
            ];
        }
        return $data;
    }

}

