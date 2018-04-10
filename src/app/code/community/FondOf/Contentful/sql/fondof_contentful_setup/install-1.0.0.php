<?php

/**
 * Installer script
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */

/**
 * @var Mage_Core_Model_Resource_Setup $installer
 */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'fondof_contentful/mapping'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('fondof_contentful/mapping'))
    ->addColumn('mapping_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Mapping ID')
    ->addColumn('entry_identifier', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Entry Identifier')
    ->addColumn('entry_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Entry ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Block Creation Time')
    ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Block Modification Time')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('fondof_contentful/mapping'),
            array('entry_identifier', 'entry_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entry_identifier', 'entry_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('Mapping Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();
