<?php

/**
 * Upgrade script
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */

/**
 * @var Mage_Core_Model_Resource_Setup $installer
 */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->dropIndex(
    $installer->getTable('fondof_contentful/mapping'),
    $installer->getIdxName(
        $installer->getTable('fondof_contentful/mapping'),
        array('entry_identifier', 'entry_id', 'store_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('fondof_contentful/mapping'),
    'content_type',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length' => 255,
        'nullable' => false,
        'after' => 'entry_id',
        'comment' => 'Content Type'
    )
);

$installer->getConnection()->addIndex(
    $installer->getTable('fondof_contentful/mapping'),
    $installer->getIdxName(
        $installer->getTable('fondof_contentful/mapping'),
        array('entry_identifier', 'entry_id', 'content_type', 'store_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entry_identifier', 'entry_id', 'content_type', 'store_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->getConnection()->update(
    $installer->getTable('fondof_contentful/mapping'),
    array('content_type' => 'page')
);

$installer->endSetup();
