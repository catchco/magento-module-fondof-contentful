<?php

/**
 * Entry block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Entry extends FondOf_Contentful_Block_Template
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var \Contentful\Delivery\DynamicEntry
     */
    protected $_entry;

    /**
     * Initialize
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setCacheLifetime(null);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Before rendering html, but after trying to load cache
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        if ($this->_id) {
            $this->_entry = Mage::getSingleton('fondof_contentful/client')->getEntry($this->_id);
        }

        return parent::_beforeToHtml();
    }

    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = '';

        if ($this->_entry === null
            || ($this->_entry->getContentType()->getField('isActive') !== null && !$this->_entry->getIsActive())
        ) {
            return $html;
        }

        $contentTypeId = $this->_entry->getContentType()->getId();
        $contentTypeRenderer = $this->_getContentTypeRenderer($contentTypeId);

        if (!$contentTypeRenderer || !($contentTypeRenderer instanceof FondOf_Contentful_Block_Content_Type_Renderer_Default)) {
            return $html;
        }

        $html = $contentTypeRenderer->setContent($this->_entry)->toHtml();

        return $html;
    }
}
