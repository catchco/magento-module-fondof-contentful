<?php

/**
 * Navigation item renderer page fallback block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderer_Page_Fallback extends FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract
{
    /**
     * Retrieve page
     *
     * @return Mage_Cms_Model_Page|null
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

        $page = Mage::getModel('cms/page')->load($pageIdentifier);

        if (!$page || !($page instanceof Mage_Cms_Model_Page) || !$page->getId()) {
            $this->setData('page', null);
            return null;
        }

        $this->setData('page', $page);
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

        $activeState = Mage::helper('core/url')->getCurrentUrl() == $this->getHref();

        $this->setData('active_state', $activeState);

        return $activeState;
    }
}