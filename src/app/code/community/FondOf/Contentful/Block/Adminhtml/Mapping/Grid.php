<?php

/**
 * Admin grid
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Adminhtml_Mapping_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_id');
        $this->setDefaultSort('mapping_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('fondof_contentful/mapping_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entry_id', array(
            'header' => Mage::helper('fondof_contentful')->__('Entry ID'),
            'align' => 'left',
            'index' => 'entry_id',
        ));

        $this->addColumn('entry_id', array(
            'header' => Mage::helper('fondof_contentful')->__('Entry ID'),
            'align' => 'left',
            'index' => 'entry_id',
        ));

        $this->addColumn('entry_identifier', array(
            'header' => Mage::helper('fondof_contentful')->__('Entry Identifier'),
            'align' => 'left',
            'index' => 'entry_identifier',
        ));

        $this->addColumn('content_type', array(
            'header' => Mage::helper('fondof_contentful')->__('Content Type'),
            'align' => 'left',
            'index' => 'content_type',
        ));

        $this->addColumn('store_id', array(
            'header' => Mage::helper('fondof_contentful')->__('Store ID'),
            'align' => 'left',
            'index' => 'store_id',
        ));

        $this->addColumn('creation_time', array(
            'header' => Mage::helper('fondof_contentful')->__('Date Created'),
            'index' => 'creation_time',
            'type' => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header' => Mage::helper('fondof_contentful')->__('Last Modified'),
            'index' => 'update_time',
            'type' => 'datetime',
        ));

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Retrieve row url
     *
     * @param $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Prepare mass action
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $modelPk = Mage::getModel('fondof_contentful/mapping')->getResource()->getIdFieldName();

        $this->setMassactionIdField($modelPk);
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));

        return $this;
    }
}
