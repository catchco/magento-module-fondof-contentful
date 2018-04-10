<?php

/**
 * Content type renderer default block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Content_Type_Renderer_Default extends FondOf_Contentful_Block_Template
{
    /**
     * @var \Contentful\Delivery\DynamicEntry|null
     */
    protected $_content = null;

    /**
     * Internal constructor, that is called from real constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setCacheLifetime(false);
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

        return $cacheKeyInfo;
    }

    /**
     * Set content
     *
     * @param \Contentful\Delivery\DynamicEntry $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }

    /**
     * Retrieve content
     *
     * @return \Contentful\Delivery\DynamicEntry|null
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Retrieve HTML for given content
     *
     * @param $content
     * @return string
     */
    public function getContentHtml($content)
    {
        $contentTypeId = $content->getContentType()->getId();
        $contentTypeRenderer = $this->_getContentTypeRenderer($contentTypeId);

        if (!$contentTypeRenderer || !($contentTypeRenderer instanceof FondOf_Contentful_Block_Template)) {
            return '';
        }

        $contentTypeRenderer->setContent($content);

        return $contentTypeRenderer->toHtml();
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->_content === null
            || ($this->_content->getContentType()->getField('isActive') !== null && !$this->_content->getIsActive())
        ) {
            return '';
        }

        return parent::_toHtml();
    }
}
