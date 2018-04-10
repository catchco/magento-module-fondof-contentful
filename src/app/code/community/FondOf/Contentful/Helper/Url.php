<?php

/**
 * Url helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Url extends Mage_Core_Helper_Abstract
{
    /**
     * Prepare
     *
     * @param $url
     * @return string
     */
    public function prepare($url) {
        if (preg_match('/https?:\/\/.+/', $url) === 1) {
            return $url;
        }

        if (strpos($url, '#') !== false) {
            return $url;
        }

        if (substr($url, 0, 1) === '/') {
            $url = substr($url, 1);
        }

        if (substr($url, strlen($url) - 1, 1) === '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $this->_getUrl($url);
    }
}
