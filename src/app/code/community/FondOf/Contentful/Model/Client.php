<?php

/**
 * Client model
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Model_Client extends \Contentful\Delivery\Client
{
    /**
     * @var FondOf_Contentful_Model_Api_Result_Cache
     */
    protected $_apiResultCache;

    /**
     * @var string
     */
    protected $_spaceId;

    /**
     * @var string
     */
    protected $_currentLocale;

    /**
     * @var Mage_Core_Helper_Abstract|Mage_Core_Helper_Data
     */
    protected $_coreHelper;

    /**
     * FondOf_Contentful_Model_Client constructor.
     */
    public function __construct() {
        $accessToken = Mage::helper('fondof_contentful')->getAccessToken();

        $this->_currentLocale = Mage::helper('fondof_contentful')->getLocale();
        $this->_spaceId = Mage::helper('fondof_contentful')->getSpaceId();
        $this->_apiResultCache = Mage::getSingleton('fondof_contentful/api_result_cache');

        $this->_coreHelper = Mage::helper('core');

        parent::__construct($accessToken, $this->_spaceId, false, '*');
    }

    /**
     * Retrieve asset by id
     *
     * @param string $id
     *
     * @param null $locale
     * @return \Contentful\Delivery\Asset
     */
    public function getAsset($id, $locale = null)
    {
        $cacheKeyInfo = array('Asset', $id);

        $asset = $this->_loadResultFromCache($cacheKeyInfo);
        if ($asset !== null) {
            return $asset;
        }

        $asset = parent::getAsset($id, null);
        $this->_saveResultToCache($asset, $cacheKeyInfo);
        return $asset;
    }

    /**
     * Retrieve assets by query
     *
     * @param \Contentful\Delivery\Query|null $query
     * @return \Contentful\ResourceArray|null
     */
    public function getAssets(\Contentful\Delivery\Query $query = null)
    {
        $cacheKeyInfo = array_values($query->getQueryData());
        $cacheKeyInfo = array_merge_recursive($cacheKeyInfo, array_keys($query->getQueryData()));
        $cacheKeyInfo[] = 'Asset';

        $assets = $this->_loadResultFromCache($cacheKeyInfo);
        if ($assets !== null) {
            return $assets;
        }

        $assets = parent::getAssets($query);
        $this->_saveResultToCache($assets, $cacheKeyInfo);
        $this->_saveQueryToCache($cacheKeyInfo);

        return $assets;
    }

    /**
     * Retrieve content type by id
     *
     * @param string $id
     * @return \Contentful\Delivery\ContentType|null
     */
    public function getContentType($id)
    {
        $cacheKeyInfo = array('ContentType', $id);

        $contentType = $this->_loadResultFromCache($cacheKeyInfo);
        if ($contentType !== null) {
            return $contentType;
        }

        $contentType = parent::getContentType($id);
        $this->_saveResultToCache($contentType, $cacheKeyInfo);

        return $contentType;
    }

    /**
     * Retrieve content types by query
     *
     * @param \Contentful\Delivery\Query|null $query
     * @return \Contentful\ResourceArray|null
     */
    public function getContentTypes(\Contentful\Delivery\Query $query = null)
    {
        $cacheKeyInfo = array_values($query->getQueryData());
        $cacheKeyInfo = array_merge_recursive($cacheKeyInfo, array_keys($query->getQueryData()));
        $cacheKeyInfo[] = 'ContentType';

        $contentTypes = $this->_loadResultFromCache($cacheKeyInfo);

        if ($contentTypes !== null) {
            return $contentTypes;
        }

        $contentTypes = parent::getEntries($query);
        $this->_saveResultToCache($contentTypes, $cacheKeyInfo);
        $this->_saveQueryToCache($cacheKeyInfo);

        return $contentTypes;
    }

    /**
     * Retrieve entry by id
     *
     * @param string $id
     * @param null $locale
     * @return \Contentful\Delivery\EntryInterface|null
     */
    public function getEntry($id, $locale = null)
    {
        $cacheKeyInfo = array('Entry', $id);

        $entry = $this->_loadResultFromCache($cacheKeyInfo);
        if ($entry !== null) {
            $entry->setLocale($this->_currentLocale);
            return $entry;
        }

        $entry = parent::getEntry($id, null);
        $this->_saveResultToCache($entry, $cacheKeyInfo);
        $entry->setLocale($this->_currentLocale);

        return $entry;
    }

    /**
     * Retrieve entries by query
     *
     * @param \Contentful\Delivery\Query|null $query
     * @return \Contentful\ResourceArray|null
     */
    public function getEntries(\Contentful\Delivery\Query $query = null)
    {
        $cacheKeyInfo = array_values($query->getQueryData());
        $cacheKeyInfo = array_merge_recursive($cacheKeyInfo, array_keys($query->getQueryData()));
        $cacheKeyInfo[] = 'Entry';

        $entries = $this->_loadResultFromCache($cacheKeyInfo);

        if ($entries !== null) {
            return $entries;
        }

        $entries = parent::getEntries($query);
        $this->_saveResultToCache($entries, $cacheKeyInfo);
        $this->_saveQueryToCache($cacheKeyInfo);

        return $entries;
    }

    /**
     * Retrieve space
     *
     * @return \Contentful\Delivery\Space|null
     */
    public function getSpace()
    {
        $cacheKeyInfo = array('Space', $this->_spaceId);

        $space = $this->_loadResultFromCache($cacheKeyInfo);
        if ($space !== null) {
            return $space;
        }

        $space = parent::getSpace();
        $this->_saveResultToCache($space, $cacheKeyInfo);

        return $space;
    }

    /**
     * Load result form cache
     *
     * @param $cacheKeyInfo
     *
     * @return \Contentful\Delivery\Asset|\Contentful\Delivery\ContentType|\Contentful\Delivery\DynamicEntry|\Contentful\Delivery\Space|\Contentful\Delivery\Synchronization\DeletedAsset|\Contentful\Delivery\Synchronization\DeletedEntry|\Contentful\ResourceArray|null
     */
    protected function _loadResultFromCache($cacheKeyInfo)
    {
        $json = $this->_apiResultCache->load($cacheKeyInfo);

        if (!$json) {
            return null;
        }

        return $this->reviveJson($json);
    }

    /**
     * Save result to cache
     *
     * @param JsonSerializable $result
     * @param array $cacheKeyInfo
     *
     * @return $this
     */
    protected function _saveResultToCache(JsonSerializable $result, $cacheKeyInfo = array())
    {
        $json = $this->_coreHelper->jsonEncode($result);
        $this->_apiResultCache->save($cacheKeyInfo, $json);

        return $this;
    }

    /**
     * Save query to cache
     *
     * @param array $cacheKeyInfo
     *
     * @return $this
     */
    protected function _saveQueryToCache($cacheKeyInfo = array())
    {
        $query = array();
        $cacheKey = $this->_apiResultCache->getCacheKey($cacheKeyInfo);
        $json = $this->_apiResultCache->load(array('query'));

        if ($json) {
            $query = $this->_coreHelper->jsonDecode($json);
        }

        if (array_key_exists($cacheKey, $query)) {
            return $this;
        }

        $query[$cacheKey] = $cacheKey;
        $this->_apiResultCache->save(array('query'), $this->_coreHelper->jsonEncode($query));

        return $this;
    }
}
