<?php

/**
 * Text helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Markdown extends Mage_Core_Helper_Abstract
{
    /** @var \Ciconia\Ciconia|null */
    protected $_parser = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_parser = new \Ciconia\Ciconia();

        $this->_parser->addExtension(new Ciconia\Extension\Gfm\FencedCodeBlockExtension());
        $this->_parser->addExtension(new Ciconia\Extension\Gfm\TaskListExtension());
        #$this->_parser->addExtension(new Ciconia\Extension\Gfm\InlineStyleExtension());
        $this->_parser->addExtension(new Ciconia\Extension\Gfm\WhiteSpaceExtension());
        $this->_parser->addExtension(new Ciconia\Extension\Gfm\TableExtension());
        $this->_parser->addExtension(new Ciconia\Extension\Gfm\UrlAutoLinkExtension());
    }


    /**
     * Transform markdown to html
     *
     * @param string $markdown
     * @return string
     */
    public function toHtml($markdown)
    {
        return $this->_parser->render($markdown);
    }
}
