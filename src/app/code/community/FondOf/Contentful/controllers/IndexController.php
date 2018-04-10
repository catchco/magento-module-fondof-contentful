<?php

/**
 * Index controller
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $pageId = Mage::helper('fondof_contentful/page')->getEntryIdForHome();
        $page = Mage::getSingleton('fondof_contentful/client')->getEntry($pageId);

        if (!$page || !($page instanceof \Contentful\Delivery\DynamicEntry) || !$page->getId()) {
            $this->_forward('cms/index/defaultIndex');
            return;
        }

        Mage::register('fondof_contentful_page', $page);

        $this->loadLayout();
        $this->renderLayout();
    }
}
