<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Controller\Adminhtml\ColorRelationship;

use MageClever\ColorMapping\Helper\Data as DataHelper;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    protected $productFactory;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context                   $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Catalog\Model\ProductFactory                 $productFactory,
        DataHelper                                            $dataHelper
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->productFactory = $productFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('colorrelationship_id');

            $model = $this->_objectManager->create(\MageClever\ColorMapping\Model\ColorRelationship::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Colorrelationship no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $data = $this->_reBuildData($data);
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Colorrelationship.'));
                $this->dataPersistor->clear('mageclever_colormapping_colorrelationship');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['colorrelationship_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage(__('The relationship for this parent color already exist. Please change to other parent color.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the color relationship.'));
            }

            $this->dataPersistor->set('mageclever_colormapping_colorrelationship', $data);
            return $resultRedirect->setPath('*/*/edit', ['colorrelationship_id' => $this->getRequest()->getParam('colorrelationship_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $currentData
     * @return array
     */
    private function _reBuildData($currentData)
    {
        if (empty($currentData['parent_color_option_id']) || empty($currentData['child_color_option_id'])) {
            return [];
        }

        $parentColorValue = $currentData['parent_color_option_id'];
        $childColorValue = implode(',', $currentData['child_color_option_id']);

        $targetParentAttrCode = $this->_dataHelper->getDeclaredParentColor();
        $targetChildAttrCode = $this->_dataHelper->getDeclaredChildColor();

        if (!$targetParentAttrCode || !$targetChildAttrCode) {
            return [];
        }

        $parentAttr = $this->productFactory->create()->getResource()->getAttribute($targetParentAttrCode);
        $childAttr = $this->productFactory->create()->getResource()->getAttribute($targetChildAttrCode);

        $parentColorLabel = '';
        if ($parentAttr && $parentAttr->usesSource()) {
            $parentColorLabel = $parentAttr->getSource()->getOptionText($parentColorValue);
        }
        $childAttrLabel = '';
        if ($childAttr && $childAttr->usesSource()) {
            $childLabel = [];
            foreach ($currentData['child_color_option_id'] as $_optId) {
                $childLabel[] = $childAttr->getSource()->getOptionText($_optId);
            }
            if (!empty($childLabel)) {
                $childAttrLabel = implode(',', $childLabel);
            }
        }

        $targetData = [
            'parent_color_option_id' => $parentColorValue,
            'parent_color_option_text' => $parentColorLabel,
            'child_color_option_id' => $childColorValue,
            'child_color_option_text' => $childAttrLabel
        ];
        $id = !empty($currentData['colorrelationship_id']) ? $currentData['colorrelationship_id'] : '';
        if ($id) {
            $targetData['colorrelationship_id'] = $id;
        }
        return $targetData;
    }
}

