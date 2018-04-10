<?php

/**
 * Page helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Page extends Mage_Core_Helper_Abstract
{
    const XML_PATH_PAGE_ENTRY_ID_FOR_HOME = 'fondof_contentful/page/entry_id_for_home';

    /**
     * Retrieve entry id for home
     *
     * @param null $store
     * @return string
     */
    public function getEntryIdForHome($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_PAGE_ENTRY_ID_FOR_HOME, $store);
    }
}
