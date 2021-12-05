<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Controller\Adminhtml\ColorRelationship;

use MageClever\ColorMapping\Helper\Data as DataHelper;

class Edit extends \MageClever\ColorMapping\Controller\Adminhtml\ColorRelationship
{

    protected $resultPageFactory;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context        $context,
        \Magento\Framework\Registry                $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DataHelper                                 $dataHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $isEnabled = $this->_dataHelper->isEnabled();
        if (!$isEnabled) {
            $this->messageManager->addNoticeMessage('Function is disabled. Please go to system config and enable it');
        }

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('colorrelationship_id');
        $model = $this->_objectManager->create(\MageClever\ColorMapping\Model\ColorRelationship::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Colorrelationship no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('mageclever_colormapping_colorrelationship', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Color Relationship') : __('New Color Relationship'),
            $id ? __('Edit Color Relationship') : __('New Color Relationship')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Color Relationships'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Color Relationship %1', $model->getId()) : __('New Color Relationship'));
        return $resultPage;
    }
}

