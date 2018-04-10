<?php

/**
 * Admin form
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Adminhtml_Mapping_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
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
     * Retrieve helper
     *
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        return Mage::helper('fondof_contentful');
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
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_getModel();
        $modelTitle = $this->_getModelTitle();
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__("$modelTitle Information"),
            'class' => 'fieldset-wide'
        ));

        if ($model && $model->getId()) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', array('name' => $modelPk));
        }

        $fieldset->addField('entry_id', 'text', array(
            'name' => 'entry_id',
            'label' => $this->_getHelper()->__('Entry ID'),
            'title' => $this->_getHelper()->__('Entry ID'),
            'required' => true
        ));

        $fieldset->addField('entry_identifier', 'text', array(
            'name' => 'entry_identifier',
            'label' => $this->_getHelper()->__('Entry Identifier'),
            'title' => $this->_getHelper()->__('Entry Identifier'),
            'required' => true
        ));

        $fieldset->addField('content_type', 'text', array(
            'name' => 'content_type',
            'label' => $this->_getHelper()->__('Content Type'),
            'title' => $this->_getHelper()->__('Content Type'),
            'required' => true
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                'name' => 'store_id',
                'label' => $this->_getHelper()->__('Store View'),
                'title' => $this->_getHelper()->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'store_id',
                'value' => Mage::app()->getStore(true)->getId()
            ));

            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        if ($model) {
            $form->setValues($model->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
