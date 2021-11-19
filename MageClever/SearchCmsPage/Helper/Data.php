<?php
declare(strict_types=1);

namespace MageClever\SearchCmsPage\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }
}

