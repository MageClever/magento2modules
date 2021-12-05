<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Observer\Backend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use MageClever\ColorMapping\Model\SwatchExtra;
use MageClever\ColorMapping\Helper\Data as DataHelper;

class AddExtraSwatchType implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    protected DataHelper $_dataHelper;

    /**
     * @param DataHelper $dataHelper
     */
    public function __construct(DataHelper $dataHelper)
    {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->_dataHelper->isEnabled()) {
            return;
        }

        $response = $observer->getEvent()->getResponse();
        $types = $response->getTypes();
        $types[] = [
            'value' => SwatchExtra::SWATCH_MULTISELECT_TYPE_VISUAL_ATTRIBUTE_FRONTEND_INPUT,
            'label' => __('Visual Swatch - Multiselect'),
            'hide_fields' => [
                'is_unique',
                'is_required',
                'frontend_class',
                '_scope',
                '_default_value',
            ],
        ];
        $types[] = [
            'value' => SwatchExtra::SWATCH_MULTISELECT_TYPE_TEXTUAL_ATTRIBUTE_FRONTEND_INPUT,
            'label' => __('Text Swatch - Multiselect'),
            'hide_fields' => [
                'is_unique',
                'is_required',
                'frontend_class',
                '_scope',
                '_default_value',
            ],
        ];

        $response->setTypes($types);
    }
}
