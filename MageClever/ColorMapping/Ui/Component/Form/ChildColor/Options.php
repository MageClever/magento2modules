<?php

namespace MageClever\ColorMapping\Ui\Component\Form\ChildColor;

use MageClever\ColorMapping\Helper\Data as DataHelper;
use Magento\Eav\Model\Config;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

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
        return $this->getChildColorOptionsTree();
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function getChildColorOptionsTree()
    {
        if ($this->_colorOptions === null) {
            $data = [];
            $targetAttrCode = $this->_dataHelper->getDeclaredChildColor();
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
