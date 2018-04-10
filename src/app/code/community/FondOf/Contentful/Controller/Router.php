<?php

/**
 * Contentful Controller Router
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     * @return FondOf_Contentful_Controller_Router
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        if (!$event || !($event instanceof Varien_Event)) {
            return $this;
        }

        $front = $event->getData('front');

        if (!$front || !($front instanceof Mage_Core_Controller_Varien_Front)) {
            return $this;
        }

        $front->addRouter('fondof_contentful', $this);
    }

    /**
     * @param Zend_Controller_Request_Http $request
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');

        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));

        Mage::dispatchEvent('fondof_contentful_contentful_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));

        $identifier = $condition->getData('identifier');

        if ($condition->getData('redirect_url')) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getData('redirect_url'))
                ->sendResponse();

            $request->setDispatched(true);

            return true;
        }

        if (!$condition->getData('continue')) {
            return false;
        }

        $entryId = null;
        $entryId = Mage::getSingleton('fondof_contentful/mapping')->getEntryIdByEntryIdentifierAndContentType($identifier);

        if (!$entryId) {
            return false;
        }

        $request->setModuleName('fondof_contentful')
            ->setControllerName('page')
            ->setActionName('view')
            ->setParam('page_id', $entryId);

        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;
    }
}
