<?php

namespace MageClever\ColorMapping\Plugin\Catalog\Controller\Adminhtml\Product\Attribute;

use Magento\Catalog\Controller\Adminhtml\Product\Attribute;
use Magento\Framework\App\RequestInterface;
use Magento\Swatches\Model\Swatch;
use MageClever\ColorMapping\Model\SwatchExtra;

class Save
{
    /**
     * @param Attribute\Save $subject
     * @param RequestInterface $request
     * @return RequestInterface[]
     */
    public function beforeDispatch(Attribute\Save $subject, RequestInterface $request): array
    {
        $data = $request->getPostValue();
        if (isset($data['frontend_input'])) {
            switch ($data['frontend_input']) {
                case SwatchExtra::SWATCH_MULTISELECT_TYPE_VISUAL_ATTRIBUTE_FRONTEND_INPUT:
                    $data[Swatch::SWATCH_INPUT_TYPE_KEY] = Swatch::SWATCH_INPUT_TYPE_VISUAL;
                    $data['frontend_input'] = 'multiselect';
                    $request->setPostValue($data);
                    break;
                case SwatchExtra::SWATCH_MULTISELECT_TYPE_TEXTUAL_ATTRIBUTE_FRONTEND_INPUT:
                    $data[Swatch::SWATCH_INPUT_TYPE_KEY] = Swatch::SWATCH_INPUT_TYPE_TEXT;
                    $data['use_product_image_for_swatch'] = 0;
                    $data['frontend_input'] = 'multiselect';
                    $request->setPostValue($data);
                    break;
            }
        }
        return [$request];
    }
}
