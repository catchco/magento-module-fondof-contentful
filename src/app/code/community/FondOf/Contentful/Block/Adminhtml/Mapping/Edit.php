<?php

/**
 * Admin form container
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Adminhtml_Mapping_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize form container
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'mapping_id';
        $this->_blockGroup = 'fondof_contentful';
        $this->_controller = 'adminhtml_mapping';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', $this->_getHelper()->__('Save %s', $this->_getModelTitle()));
        $this->_addButton('saveandcontinue', array(
            'label' => $this->_getHelper()->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Retrieve helper
     *
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        return Mage::helper('fondof_contentful');
    }

    /**
     * Retrieve model
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _getModel()
    {
        return Mage::registry('current_model');
    }

    /**
     * Retrieve model title
     *
     * @return string
     */
    protected function _getModelTitle()
    {
        return 'Mapping';
    }

    /**
     * Retrieve header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = $this->_getModel();
        $modelTitle = $this->_getModelTitle();

        if ($model && $model->getId()) {
            return $this->_getHelper()->__("Edit $modelTitle (ID: {$model->getId()})");
        } else {
            return $this->_getHelper()->__("New $modelTitle");
        }
    }

    /**
     * Retrieve url for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }

    /**
     * Retrieve url for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }

    /**
     * Get form save URL
     *
     * @deprecated
     * @see getFormActionUrl()
     * @return string
     */
    public function getSaveUrl()
    {
        $this->setData('form_action_url', 'save');
        return $this->getFormActionUrl();
    }
}
