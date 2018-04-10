<?php

/**
 * Page block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Page extends FondOf_Contentful_Block_Template
{
    /**
     * @var null|\Contentful\Delivery\DynamicEntry
     */
    protected $_page = null;

    /**
     * @var null|FondOf_Contentful_Helper_Page
     */
    protected $_pageHelper = null;

    /**
     * @var null|FondOf_Contentful_Helper_Url
     */
    protected $_urlHelper = null;

    /**
     * Initialize
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setCacheLifetime(null);
        $this->_page = Mage::registry('fondof_contentful_page');
        $this->_pageHelper = Mage::helper('fondof_contentful/page');
        $this->_urlHelper = Mage::helper('fondof_contentful/url');
    }

    /**
     * Retrieve Page instance
     *
     * @return \Contentful\Delivery\DynamicEntry|null
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * Prepare global layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        /** @var Mage_Page_Block_Html_Breadcrumbs $breadcrumbs */
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');

        if ($this->_canShowBreadcrumbs()) {
            $breadcrumbs->addCrumb('home', array(
                    'label' => Mage::helper('fondof_contentful')->__('Home'),
                    'title' => Mage::helper('fondof_contentful')->__('Go to Home Page'),
                    'link' => Mage::getBaseUrl()
                )
            );

            $breadcrumbs->addCrumb('fondof_contentful_page', array(
                    'label' => $this->_page->getTitle(),
                    'title' => $this->_page->getTitle()
                )
            );
        }

        $root = $this->getLayout()->getBlock('root');

        if ($root) {
            $root->addBodyClass('fondof-contentful-page-view-' . $this->_page->getIdentifier());
        }

        $head = $this->getLayout()->getBlock('head');

        if ($head) {
            $head->setTitle($this->_page->getTitle());
            $head->setKeywords($this->_page->getMetaKeywords());
            $head->setDescription($this->_page->getMetaDescription());

            $canonical = $this->_urlHelper->prepare($this->_page->getIdentifier());

            if ($this->_page->getId() == $this->_pageHelper->getEntryIdForHome()) {
                $canonical = Mage::getBaseUrl();
            }

            $head->addLinkRel('canonical', $canonical);

            if ($this->_page->getRobots()) {
                $head->setRobots($this->_page->getRobots());
            }
        }

        return parent::_prepareLayout();
    }


    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = '';

        foreach ($this->_page->getContent() as $content) {
            $contentTypeId = $content->getContentType()->getId();
            $contentTypeRenderer = $this->_getContentTypeRenderer($contentTypeId);

            if (!$contentTypeRenderer || !($contentTypeRenderer instanceof FondOf_Contentful_Block_Content_Type_Renderer_Default)) {
                continue;
            }

            $html .= $contentTypeRenderer->setContent($content)->toHtml();
        }

        return $html;
    }

    /**
     * Can show breadcrumbs
     *
     * @return bool
     */
    protected function _canShowBreadcrumbs()
    {
        $page = $this->_page;
        /** @var Mage_Page_Block_Html_Breadcrumbs $breadcrumbs */
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');

        return Mage::getStoreConfig('web/default/show_cms_breadcrumbs')
            && $breadcrumbs
            && $page->getIdentifier() !== Mage::getStoreConfig('web/default/cms_home_page')
            && $page->getIdentifier() !== Mage::getStoreConfig('web/default/cms_no_route');
    }
}
