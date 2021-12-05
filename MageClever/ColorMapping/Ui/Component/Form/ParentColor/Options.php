<?php

namespace MageClever\ColorMapping\Ui\Component\Form\ParentColor;

use Magento\Eav\Model\Config;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use MageClever\ColorMapping\Helper\Data as DataHelper;

class Options implements OptionSourceInterface
{
    /**
     * @var Config
     */
    protected Config $_eavConfig;

    /**
     * @var null
     */
    protected $_colorOptions = null;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * @param Config $eavConfig
     * @param DataHelper $dataHelper
     */
    public function __construct(
        Config     $eavConfig,
        DataHelper $dataHelper
    )
    {
        $this->_eavConfig = $eavConfig;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getParentColorOptionsTree();
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function getParentColorOptionsTree()
    {
        if ($this->_colorOptions === null) {
            $data = [
                [
                    'value' => '',
                    'label' => 'Please select'
                ]
            ];

            $targetAttrCode = $this->_dataHelper->getDeclaredParentColor();
            if (!$targetAttrCode) {
                return $data;
            }
            $targetAttr = $this->_eavConfig->getAttribute('catalog_product', $targetAttrCode);
            $options = $targetAttr->getSource()->getAllOptions();

            foreach ($options as $option) {
                if (empty($option['value'])) {
                    continue;
                }
                $data[] = $option;
            }
            $this->_colorOptions = $data;
        }
        return $this->_colorOptions;
    }
}
