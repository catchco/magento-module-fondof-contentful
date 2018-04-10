<?php

/**
 * Content type renderer product block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Content_Type_Renderer_Product extends FondOf_Contentful_Block_Content_Type_Renderer_Default
{
    /**
     * Internal constructor, that is called from real constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setCacheLifetime(false);
    }

    /**
     * Retrieve current product
     *
     * @return Mage_Catalog_Model_Product|null
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Get cache key informative items
     * Provide string array key to share specific info item with FPC placeholder
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $cacheKeyInfo = array(
            Mage::app()->getStore()->getCode()
        );

        if (!$this->_content && !($this->_content instanceof \Contentful\Delivery\DynamicEntry)) {
            return $cacheKeyInfo;
        }

        $cacheKeyInfo[] = $this->_content->getId();

        if ($this->getProduct() != null && $this->getProduct()->getId() > 0) {
            $cacheKeyInfo[] = $this->getProduct()->getSku();
        }

        return $cacheKeyInfo;
    }
}