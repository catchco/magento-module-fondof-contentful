<?php

/**
 * Navigation item renderer custom block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderer_Custom extends FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract
{
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

        return '';
    }

    /**
     * Retrieve href
     *
     * @return string
     */
    public function getHref()
    {
        if (array_key_exists('url', $this->_item) && $this->_item['url'] != '') {
            return $this->_item['url'];
        }

        return '';
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

        $currentUrl = str_replace(array('http://', 'https://'), '', Mage::helper('core/url')->getCurrentUrl());
        $url = str_replace(array('http://', 'https://'), '', $this->getHref());

        $activeState = $currentUrl == $url;

        $this->setData('active_state', $activeState);

        return $activeState;
    }
}
