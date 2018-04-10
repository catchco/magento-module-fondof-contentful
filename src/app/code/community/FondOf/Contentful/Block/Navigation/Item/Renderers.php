<?php

/**
 * Navigation item renderers block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderers extends Mage_Core_Block_Abstract
{
    /**
     * @var array
     */
    protected $_renderer = array();

    /**
     * Internal constructor, that is called from real constructor
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Add navigation item renderer
     *
     * @param $navigationItemType
     * @param $blockType
     * @param $template
     * @return $this
     */
    public function addNavigationItemRenderer($navigationItemType, $blockType, $template)
    {
        $this->_renderer[$navigationItemType] = array(
            'type' => $blockType,
            'template' => $template
        );

        return $this;
    }

    /**
     * Retrieve navigation item renderer
     *
     * @param $navigationItemType
     * @return FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract|null
     */
    public function getNavigationItemRenderer($navigationItemType)
    {
        if (!array_key_exists($navigationItemType, $this->_renderer)) {
            return null;
        }

        $rendererBlock = $this->getLayout()->createBlock($this->_renderer[$navigationItemType]['type']);

        if (!$rendererBlock || !($rendererBlock instanceof FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract)) {
            return null;
        }

        $rendererBlock->setTemplate($this->_renderer[$navigationItemType]['template']);

        return $rendererBlock;
    }

    /**
     * Remove navigation item renderer
     *
     * @param $navigationItemType
     * @return $this
     */
    public function removeNavigationItemRenderer($navigationItemType)
    {
        if (array_key_exists($navigationItemType, $this->_renderer)) {
            unset($this->_renderer[$navigationItemType]);
        }

        return $this;
    }
}
