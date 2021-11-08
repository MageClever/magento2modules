<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const XML_CONFIG_PATH_IS_ENABLED = 'hide_product_by_type/general/is_enabled';

    const XML_CONFIG_PATH_HIDE_PRODUCT_TYPE = 'hide_product_by_type/detail_config/product_type_id';

    const XML_CONFIG_PATH_HIDE_IN_PAGE = 'hide_product_by_type/detail_config/hide_in_page';

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var int
     */
    protected int $_currentStoreId;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context               $context,
        StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        $this->_currentStoreId = (int)$storeManager->getStore()->getId();
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabledHideProduct(): bool
    {
        $isEnabled = $this->scopeConfig->getValue(self::XML_CONFIG_PATH_IS_ENABLED, ScopeInterface::SCOPE_STORE, $this->_currentStoreId);
        if (!$isEnabled) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getConfigHideProductType(): array
    {
        $hideProductType = $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HIDE_PRODUCT_TYPE, ScopeInterface::SCOPE_STORE, $this->_currentStoreId);
        if (!$hideProductType) {
            return [];
        }
        return explode(',', $hideProductType);
    }

    /**
     * @return array
     */
    public function getHideProductInPage(): array
    {
        $hideProductInPage = $this->scopeConfig->getValue(self::XML_CONFIG_PATH_HIDE_IN_PAGE, ScopeInterface::SCOPE_STORE, $this->_currentStoreId);
        if (!$hideProductInPage) {
            return [];
        }
        return explode(',', $hideProductInPage);
    }
}

