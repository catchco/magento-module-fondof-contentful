<?php

/**
 * Mapping adminhtml controller
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Adminhtml_Fondof_Contentful_MappingController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('fondof_contentful/adminhtml_mapping'));
        $this->renderLayout();
    }

    /**
     * Export csv action
     */
    public function exportCsvAction()
    {
        $fileName = 'fondof_contentful_mapping.csv';
        $content = $this->getLayout()->createBlock('fondof_contentful/adminhtml_mapping_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export excel action
     */
    public function exportExcelAction()
    {
        $fileName = 'fondof_contentful_mapping.xml';
        $content = $this->getLayout()->createBlock('fondof_contentful/adminhtml_mapping_grid')->getExcel();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Mass delete action
     */
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!is_array($ids)) {
            $this->_getSession()->addError($this->__('Please select Mapping(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getSingleton('fondof_contentful/mapping')->load($id);
                    $model->delete();
                }

                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been deleted.', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('fondof_contentful')->__('An error occurred while mass deleting items. Please review log and try again.')
                );
                Mage::logException($e);
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('fondof_contentful/mapping');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('fondof_contentful')->__('This mapping no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('current_model', $model);


        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('fondof_contentful/adminhtml_mapping_edit'));
        $this->renderLayout();
    }

    /**
     * New action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Save action
     */
    public function saveAction()
    {
        $redirectBack = $this->getRequest()->getParam('back', false);
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('fondof_contentful/mapping');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->_getSession()->addError(
                        Mage::helper('fondof_contentful')->__('This mapping no longer exists.')
                    );
                    $this->_redirect('*/*/index');
                    return;
                }
            }

            try {
                $model->addData($data);
                $this->_getSession()->setFormData($data);
                $model->save();
                $this->_getSession()->setFormData(false);
                $this->_getSession()->addSuccess(
                    Mage::helper('fondof_contentful')->__('The mapping has been saved.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            } catch (Exception $e) {
                $this->_getSession()->addError(Mage::helper('fondof_contentful')->__('Unable to save the mapping.'));
                $redirectBack = true;
                Mage::logException($e);
            }

            if ($redirectBack) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('fondof_contentful/mapping');
                $model->load($id);

                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('fondof_contentful')->__('Unable to find a mapping to delete.'));
                }

                $model->delete();

                $this->_getSession()->addSuccess(
                    Mage::helper('fondof_contentful')->__('The mapping has been deleted.')
                );

                $this->_redirect('*/*/index');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('fondof_contentful')->__('An error occurred while deleting mapping data. Please review log and try again.')
                );
                Mage::logException($e);
            }

            $this->_redirect('*/*/edit', array('id' => $id));
            return;
        }

        $this->_getSession()->addError(
            Mage::helper('fondof_contentful')->__('Unable to find a mapping to delete.')
        );

        $this->_redirect('*/*/index');
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('contentful/mapping');
    }
}
