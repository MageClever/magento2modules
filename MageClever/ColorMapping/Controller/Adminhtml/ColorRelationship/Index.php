<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Controller\Adminhtml\ColorRelationship;

use MageClever\ColorMapping\Helper\Data as DataHelper;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context        $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DataHelper                                 $dataHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $isEnabled = $this->_dataHelper->isEnabled();
        if (!$isEnabled) {
            $this->messageManager->addNoticeMessage('Function is disabled. Please go to system config and enable it');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Color Relationship"));
        return $resultPage;
    }
}

