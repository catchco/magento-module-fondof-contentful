<?php

/**
 * Cache callback handler
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 */
class FondOf_Contentful_Model_Callback_Handler_Cache extends FondOf_Contentful_Model_Callback_Handler_Abstract
{
    protected $_apiResultCache;

    /**
     * FondOf_Contentful_Model_Callback_Handler_Page constructor.
     */
    public function __construct()
    {
        $this->_apiResultCache = Mage::getSingleton('fondof_contentful/api_result_cache');
    }


    /**
     * Handle callback
     *
     * @param string $topic
     * @param mixed $body
     * @return FondOf_Contentful_Model_Callback_Handler_Abstract
     */
    protected function _handle($topic, $body)
    {
        $this->_removeFromApiResultCache($body)
            ->_removeFromQueryCache($body)
            ->_removeFromOutputCache();

        return $this;

    }

    /**
     * Remove from query cache
     *
     * @param $body
     *
     * @return $this
     */
    protected function _removeFromQueryCache($body)
    {
        if (!($body instanceof \Contentful\Delivery\DynamicEntry
            || $body instanceof \Contentful\Delivery\Synchronization\DeletedEntry)
        ) {
            return $this;
        }

        $this->_apiResultCache->delete(array('query'));

        return $this;
    }

    /**
     * Remove from api result cache
     *
     * @param $body
     *
     * @return $this
     */
    protected function _removeFromApiResultCache($body)
    {
        if ($body instanceof \Contentful\Delivery\DynamicEntry
            || $body instanceof \Contentful\Delivery\Synchronization\DeletedEntry
        ) {
            $type = 'Entry';
        } elseif ($body instanceof \Contentful\Delivery\Asset
            || $body instanceof \Contentful\Delivery\Synchronization\DeletedAsset) {
            $type = 'Asset';
        } else {
            return $this;
        }

        $this->_apiResultCache->delete(array($type, $body->getId()));

        return $this;
    }

    /**
     * Remove from output cache
     *
     * @return $this
     */
    protected function _removeFromOutputCache()
    {
        Mage::app()->cleanCache(array('FONDOF_CONTENTFUL_HTML'));

        return $this;
    }

    /**
     * Can handle
     *
     * @param $topic
     * @param $body
     * @return bool
     */
    protected function _canHandle($topic, $body)
    {
        $isTopicValid = $topic && count(explode('.', $topic)) === 3;
        return $isTopicValid && $body;
    }
}
