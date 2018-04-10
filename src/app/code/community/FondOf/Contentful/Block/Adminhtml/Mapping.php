<?php

/**
 * Admin grid container
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Adminhtml_Mapping extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize grid container
     */
    public function __construct()
    {
        $this->_blockGroup = 'fondof_contentful';
        $this->_controller = 'adminhtml_mapping';
        $this->_headerText = $this->__('Mapping');
        $this->_addButtonLabel = $this->__('Add New Mapping');
        parent::__construct();
    }

    /**
     * Retrieve create url
     *
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }
}
