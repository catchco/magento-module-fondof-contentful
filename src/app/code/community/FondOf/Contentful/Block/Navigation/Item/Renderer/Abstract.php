<?php

/**
 * Navigation item renderer abstract block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
abstract class FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract extends FondOf_Contentful_Block_Template
{
    /**
     * @var array
     */
    protected $_renderedChildren = array();

    /**
     * @var null
     */
    protected $_item = null;

    /**
     * @var int
     */
    protected $_level = 0;

    /**
     * Retrieve level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * Set level
     *
     * @param $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->_level = $level;
        return $this;
    }

    /**
     * Set item
     *
     * @param null $item
     * @return $this
     */
    public function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }

    /**
     * Has active state
     *
     * @return bool
     */
    abstract public function hasActiveState();

    /**
     * Retrieve link text
     *
     * @return string
     */
    abstract public function getLinkText();

    /**
     * Retrieve href
     *
     * @return string
     */
    abstract public function getHref();

    /**
     * Retrieve rendered children
     *
     * @return array
     */
    public function getRenderedChildren()
    {
        return $this->_renderedChildren;
    }

    /**
     * Render children
     */
    protected function _renderChildren()
    {
        if (!array_key_exists('children', $this->_item) || !is_array($this->_item['children'])) {
            return $this;
        }

        foreach ($this->_item['children'] as $index => $child) {
            $this->_renderedChildren[$index] = $this->_renderChild($child);
        }

        return $this;
    }

    /**
     * Render child
     *
     * @param $child
     *
     * @return string
     */
    protected function _renderChild($child)
    {
        if (!is_array($child) || !array_key_exists('type', $child) || $child['type'] == '') {
            return '';
        }

        $navigationItemRenderer = $this->_getNavigationItemRenderer($child['type']);
        $navigationItemRenderer->setItem($child);
        $navigationItemRenderer->setLevel($this->getLevel() + 1);

        $html = $navigationItemRenderer->toHtml();

        if ($navigationItemRenderer->hasActiveState()) {
            $this->setData('active_state', $navigationItemRenderer->hasActiveState());
        }

        return $html;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->_renderChildren();
        return parent::_toHtml();
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
            $this->_level,
            Mage::helper('core/url')->getCurrentUrl(),
            json_encode($this->_item)
        );

        return $cacheKeyInfo;
    }
}
