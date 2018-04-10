<?php

/**
 * Default helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_GENERAL_ACCESS_TOKEN = 'fondof_contentful/general/access_token';
    const XML_PATH_GENERAL_SPACE_ID = 'fondof_contentful/general/space_id';
    const XML_PATH_GENERAL_LOCALE = 'fondof_contentful/general/locale';

    /**
     * Retrieve space id
     *
     * @param null $store
     * @return string
     */
    public function getSpaceId($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_SPACE_ID, $store);
    }

    /**
     * Retrieve access token
     *
     * @param null $store
     * @return string
     */
    public function getAccessToken($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_ACCESS_TOKEN, $store);
    }

    /**
     * Retrieve locale
     *
     * @param null $store
     * @return string
     */
    public function getLocale($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_LOCALE, $store);
    }

    /**
     * Retrieve default locale
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->getLocale(Mage_Core_Model_App::ADMIN_STORE_ID);
    }
}
