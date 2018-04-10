<?php

/**
 * Mapping collection
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Model_Resource_Mapping_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection resource model
     */
    protected function _construct()
    {
        $this->_init('fondof_contentful/mapping');
    }
}
