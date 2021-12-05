<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const XML_CONFIG_PATH_IS_ENABLED = 'color_mapping/general/is_enabled';

    const XML_CONFIG_PATH_PARENT_COLOR = 'color_mapping/declare/parent_color';

    const XML_CONFIG_PATH_CHILD_COLOR = 'color_mapping/declare/child_color';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context               $context,
        ScopeConfigInterface  $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $currentStoreId = $this->_storeManager->getStore()->getId();
        $isEnabled = $this->_scopeConfig->getValue(self::XML_CONFIG_PATH_IS_ENABLED, ScopeInterface::SCOPE_STORE, $currentStoreId);
        if (!$isEnabled) {
            return false;
        }
        return true;
    }

    public function getDeclaredParentColor()
    {
        if (!$this->isEnabled()) {
            return '';
        }
        $currentStoreId = $this->_storeManager->getStore()->getId();
        return $this->_scopeConfig->getValue(self::XML_CONFIG_PATH_PARENT_COLOR, ScopeInterface::SCOPE_STORE, $currentStoreId);
    }

    public function getDeclaredChildColor()
    {
        if (!$this->isEnabled()) {
            return '';
        }
        $currentStoreId = $this->_storeManager->getStore()->getId();
        return $this->_scopeConfig->getValue(self::XML_CONFIG_PATH_CHILD_COLOR, ScopeInterface::SCOPE_STORE, $currentStoreId);
    }
}

