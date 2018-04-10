<?php

/**
 * Page controller
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_PageController extends Mage_Core_Controller_Front_Action
{
    /**
     * View action
     */
    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id', false);
        $pageId = $this->getRequest()->getParam('page_id', $id);

        $page = Mage::getSingleton('fondof_contentful/client')->getEntry($pageId);

        if (!$page || !($page instanceof \Contentful\Delivery\DynamicEntry) || !$page->getId()
            || ($page->getContentType()->getField('isActive') !== null && !$page->getIsActive())
        ) {
            $this->_forward('noRoute');
            return;
        }

        Mage::register('fondof_contentful_page', $page);

        $this->loadLayout();
        $this->renderLayout();
    }
}
