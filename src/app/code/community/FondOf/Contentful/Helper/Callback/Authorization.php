<?php

/**
 * Callback authorization helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Callback_Authorization extends Mage_Core_Helper_Abstract
{
    const XML_PATH_CALLBACK_AUTHORIZATION_USERNAME = 'fondof_contentful/callback_authorization/username';
    const XML_PATH_CALLBACK_AUTHORIZATION_PASSWORD = 'fondof_contentful/callback_authorization/password';

    /**
     * Retrieve username
     *
     * @param null $store
     * @return mixed
     */
    public function getUsername($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_CALLBACK_AUTHORIZATION_USERNAME, $store);
    }

    /**
     * Retrieve password
     *
     * @param null $store
     * @return mixed
     */
    public function getPassword($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_CALLBACK_AUTHORIZATION_PASSWORD, $store);
    }

    /**
     * Validate authorization header
     *
     * @param $authorizationHeader
     * @return bool
     */
    public function validateAuthorizationHeader($authorizationHeader)
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $validAuthorizationHeader = 'Basic ' . base64_encode($username . ':' . $password);

        return $authorizationHeader === $validAuthorizationHeader;
    }
}
