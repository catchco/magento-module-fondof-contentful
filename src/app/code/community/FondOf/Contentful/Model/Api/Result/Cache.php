<?php

/**
 * Api result cache model
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 */
class FondOf_Contentful_Model_Api_Result_Cache
{
    const GROUP = 'fondof_contentful_api_results';
    const TAG = 'FONDOF_CONTENTFUL_API_RESULTS';

    /**
     * Save
     *
     * @param array $cacheKeyInfo
     * @param mixed $result
     * @return $this
     */
    public function save($cacheKeyInfo, $result)
    {
        if (!Mage::app()->useCache(self::GROUP)) {
            return $this;
        }

        Mage::app()->saveCache($result, $this->getCacheKey($cacheKeyInfo), array(self::TAG), null);

        return $this;
    }

    /**
     * Load
     *
     * @param array $cacheKeyInfo
     * @return mixed|null
     */
    public function load($cacheKeyInfo)
    {
        if (!Mage::app()->useCache(self::GROUP)) {
            return null;
        }

        return Mage::app()->loadCache($this->getCacheKey($cacheKeyInfo));
    }

    /**
     * Delete
     *
     * @param $cacheKeyInfo
     * @return $this
     */
    public function delete($cacheKeyInfo)
    {
        if (!Mage::app()->useCache(self::GROUP)) {
            return $this;
        }

        Mage::app()->removeCache($this->getCacheKey($cacheKeyInfo));

        return $this;
    }

    /**
     * Retrieve cache key
     *
     * @param $cacheKeyInfo
     * @return string
     */
    public function getCacheKey($cacheKeyInfo) {
        $cacheKeyInfo = array_values($cacheKeyInfo);
        $cacheKey = implode('|', $cacheKeyInfo);

        return sha1($cacheKey);
    }
}
