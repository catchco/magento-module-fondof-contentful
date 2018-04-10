<?php

/**
 * Content type renderers block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Content_Type_Renderers extends Mage_Core_Block_Abstract
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
        $this->addContentTypeRenderer('default');
    }

    /**
     * Add content type renderer
     *
     * @param $contentType
     * @param null $blockType
     * @param null $template
     * @return $this
     */
    public function addContentTypeRenderer($contentType, $blockType = null, $template = null) {
        if ($blockType === null) {
            $blockType = 'fondof_contentful/content_type_renderer_default';
        }

        if ($template === null) {
            $template = 'fon/contentful/default.phtml';
        }

        $this->_renderer[$contentType] = array(
            'type' => $blockType,
            'template' => $template
        );

        return $this;
    }

    /**
     * Retrieve content type renderer
     *
     * @param $contentType
     * @return FondOf_Contentful_Block_Content_Type_Renderer_Default
     */
    public function getContentTypeRenderer($contentType)
    {
        if (!array_key_exists($contentType, $this->_renderer)) {
            $contentType = 'default';
        }

        $rendererBlock = $this->getLayout()->createBlock($this->_renderer[$contentType]['type']);

        if (!$rendererBlock || !($rendererBlock instanceof FondOf_Contentful_Block_Content_Type_Renderer_Default)) {
            return null;
        }

        $rendererBlock->setTemplate($this->_renderer[$contentType]['template']);

        return $rendererBlock;
    }

    /**
     * Remove content type renderer
     *
     * @param $contentType
     * @return $this
     */
    public function removeContentTypeRenderer($contentType)
    {
        if (array_key_exists($contentType, $this->_renderer)) {
            unset($this->_renderer[$contentType]);
        }

        return $this;
    }
}
