<?php

/**
 * Template block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Template extends Mage_Core_Block_Template
{
    /**
     * Cache group
     */
    const CACHE_GROUP = 'fondof_contentful_html';

    /**
     * Cache Tag
     */
    const CACHE_TAG = 'FONDOF_CONTENTFUL_HTML';

    /**
     * Save block content to cache storage
     *
     * @param string $data
     * @return Mage_Core_Block_Abstract
     */
    protected function _saveCache($data)
    {
        if (is_null($this->getCacheLifetime()) || !$this->_getApp()->useCache(self::CACHE_GROUP)) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $data = str_replace(
            $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
            $this->_getSidPlaceholder($cacheKey),
            $data
        );

        $tags = $this->getCacheTags();

        $this->_getApp()->saveCache($data, $cacheKey, $tags, $this->getCacheLifetime());
        $this->_getApp()->saveCache(
            json_encode($tags),
            $this->_getTagsCacheKey($cacheKey),
            $tags,
            $this->getCacheLifetime()
        );
        return $this;
    }

    /**
     * Load block html from cache storage
     *
     * @return string|false
     */
    protected function _loadCache()
    {
        if (is_null($this->getCacheLifetime()) || !$this->_getApp()->useCache(self::CACHE_GROUP)) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $cacheData = $this->_getApp()->loadCache($cacheKey);
        if ($cacheData) {
            $cacheData = str_replace(
                $this->_getSidPlaceholder($cacheKey),
                $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
                $cacheData
            );
        }
        return $cacheData;
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     */
    public function getCacheTags()
    {
        $tagsCache = $this->_getApp()->loadCache($this->_getTagsCacheKey());
        if ($tagsCache) {
            $tags = json_decode($tagsCache);
        }
        if (!isset($tags) || !is_array($tags) || empty($tags)) {
            $tags = !$this->hasData(self::CACHE_TAGS_DATA_KEY) ? array() : $this->getData(self::CACHE_TAGS_DATA_KEY);
            if (!in_array(self::CACHE_TAG, $tags)) {
                $tags[] = self::CACHE_TAG;
            }
        }
        return array_unique($tags);
    }

    /**
     * Retrieve content type renderer
     *
     * @param $contentType
     * @return FondOf_Contentful_Block_Content_Type_Renderer_Default|null
     */
    protected function _getContentTypeRenderer($contentType)
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock('fondof_contentful_content_type_renderers');

        if (!$block || !($block instanceof FondOf_Contentful_Block_Content_Type_Renderers)) {
            return null;
        }

        return $block->getContentTypeRenderer($contentType);
    }

    /**
     * Retrieve navigation item renderer
     *
     * @param $navigationItemType
     * @return FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract|null
     */
    protected function _getNavigationItemRenderer($navigationItemType)
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock('fondof_contentful_navigation_item_renderers');

        if (!$block || !($block instanceof FondOf_Contentful_Block_Navigation_Item_Renderers)) {
            return null;
        }

        return $block->getNavigationItemRenderer($navigationItemType);
    }
}
