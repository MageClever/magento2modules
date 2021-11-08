<?php
declare(strict_types=1);

namespace MageClever\HideProductByType\Plugin\Magento\Catalog\Controller\Product;

use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\ProductFactory;
use MageClever\HideProductByType\Helper\Data;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\ForwardFactory;

class View
{
    /**
     * @var RequestInterface
     */
    protected RequestInterface $_request;

    /**
     * @var ProductFactory
     */
    protected ProductFactory $_productFactory;

    /**
     * @var Data
     */
    protected Data $_helper;

    /**
     * @var Http
     */
    protected Http $_http;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $_resultForwardFactory;

    /**
     * @param RequestInterface $request
     * @param ProductFactory $productFactory
     * @param Data $helper
     * @param Http $http
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        RequestInterface $request,
        ProductFactory   $productFactory,
        Data             $helper,
        Http             $http,
        ForwardFactory   $resultForwardFactory
    )
    {
        $this->_request = $request;
        $this->_productFactory = $productFactory;
        $this->_helper = $helper;
        $this->_http = $http;
        $this->_resultForwardFactory = $resultForwardFactory;
    }

    public function beforeExecute()
    {
        $productId = (int)$this->_request->getParam('id');
        if (!$productId) {
            return;
        }

        $isEnabledHideProduct = $this->_helper->isEnabledHideProduct();
        $hideProductTypeConfig = $this->_helper->getConfigHideProductType();
        $hideInPage = $this->_helper->getHideProductInPage();

        if (!$isEnabledHideProduct || empty($hideProductTypeConfig) || empty($hideInPage)) {
            return;
        }

        $fullActionName = $this->_http->getFullActionName();
        if (!in_array($fullActionName, $hideInPage)) {
            return;
        }

        $targetProduct = $this->_productFactory->create()->load($productId);
        $productType = $targetProduct->getTypeId();

        if (!in_array($productType, $hideProductTypeConfig)) {
            return;
        }

        $resultForward = $this->_resultForwardFactory->create();
        $resultForward->forward('noroute');
    }
}
