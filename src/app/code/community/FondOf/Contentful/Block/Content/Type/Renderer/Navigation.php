<?php

/**
 * Content type renderer navigation block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Content_Type_Renderer_Navigation extends FondOf_Contentful_Block_Content_Type_Renderer_Default
{
    /**
     * Retrieve html of item
     *
     * @param $item
     * @return string
     */
    public function getItemHtml($item)
    {
        if (!is_array($item) || !array_key_exists('type', $item) || $item['type'] == '') {
            return '';
        }

        $navigationItemRenderer = $this->_getNavigationItemRenderer($item['type']);
        $navigationItemRenderer->setItem($item);

        return $navigationItemRenderer->toHtml();
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
            Mage::app()->getStore()->getCode(),
            Mage::helper('core/url')->getCurrentUrl(),
            json_encode($this->_content->getItems())
        );

        return $cacheKeyInfo;
    }
}
