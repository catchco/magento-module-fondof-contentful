<?php

/**
 * Navigation item renderer page block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderer_Page extends FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract
{
    /**
     * Retrieve page
     *
     * @return \Contentful\Delivery\DynamicEntry|null
     */
    protected function _getPage()
    {
        if ($this->hasData('page')) {
            return $this->getData('page');
        }

        $pageIdentifier = '';

        if (array_key_exists('typeId', $this->_item) && $this->_item['typeId'] != '') {
            $pageIdentifier = $this->_item['typeId'];
        }

        if (!$pageIdentifier) {
            $this->setData('page', null);
            return null;
        }

        $entryId = Mage::getSingleton('fondof_contentful/mapping')->getEntryIdByEntryIdentifierAndContentType($pageIdentifier);

        if (!$entryId) {
            $this->setData('page', null);
            return null;
        }

        $page = Mage::getSingleton('fondof_contentful/client')->getEntry($entryId);

        if (!$page || !($page instanceof \Contentful\Delivery\DynamicEntry) || !$page->getId()) {
            $this->setData('page', null);
            return null;
        }

        $this->setData('page', $page);
        return $page;
    }

    /**
     * Retrieve current page
     *
     * @return \Contentful\Delivery\DynamicEntry|null
     */
    protected function _getCurrentPage()
    {
        if ($this->hasData('current_page')) {
            return $this->getData('current_page');
        }

        $page = Mage::registry('fondof_contentful_page');

        if (!$page || !($page instanceof \Contentful\Delivery\DynamicEntry) || !$page->getId()) {
            $this->setData('current_page', null);
            return null;
        }

        $this->setData('current_page', $page);
        return $page;
    }


    /**
     * Retrieve link text
     *
     * @return string
     */
    public function getLinkText()
    {
        if (array_key_exists('customText', $this->_item) && $this->_item['customText'] != '') {
            return $this->_item['customText'];
        }

        $page = $this->_getPage();

        if ($page === null) {
            return '';
        }

        return $page->getTitle();
    }

    /**
     * Retrieve href
     *
     * @return string
     */
    public function getHref()
    {
        $page = $this->_getPage();

        if ($page === null) {
            return '';
        }

        return Mage::app()->getStore()->getUrl($page->getIdentifier());
    }

    /**
     * Has active state
     *
     * @return bool
     */
    public function hasActiveState()
    {
        if ($this->hasData('active_state')) {
            return $this->getData('active_state');
        }

        $page = $this->_getPage();
        $currentPage = $this->_getCurrentPage();

        $activeState = $page !== null && $currentPage !== null
            && $page->getId() == $currentPage->getId();

        $this->setData('active_state', $activeState);

        return $activeState;
    }
}
