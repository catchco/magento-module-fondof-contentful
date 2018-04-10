<?php

/**
 * Callback controller
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_CallbackController extends Mage_Core_Controller_Front_Action
{
    /**
     * Predispatch: should set layout area
     *
     * @return Mage_Core_Controller_Front_Action
     */
    public function preDispatch()
    {
        if (!$this->getRequest()->isPost()) {
            $this->getResponse()->setHttpResponseCode(405);
            $this->setFlag('', 'no-dispatch', true);
        } else {
            $authorizationHeader = $this->getRequest()->getHeader('Authorization');

            if ($authorizationHeader == '') {
                $authorizationHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }

            if (!Mage::helper('fondof_contentful/callback_authorization')->validateAuthorizationHeader($authorizationHeader)) {
                $this->getResponse()->setHttpResponseCode(403);
                $this->setFlag('', 'no-dispatch', true);
                Mage::log('Invalid username or password.', Zend_Log::ERR, 'fondof_contentful.log');
                return parent::preDispatch();

            }

            if ($this->getRequest()->getHeader('X-Contentful-Topic') == '') {
                $this->getResponse()->setHttpResponseCode(400);
                $this->setFlag('', 'no-dispatch', true);
                Mage::log('Invalid header value for "X-Contentful-Topic".' , Zend_Log::ERR, 'fondof_contentful.log');
            }
        }

        return parent::preDispatch();
    }

    /**
     * Handle action
     */
    public function handleAction()
    {
        $callbackHandlerChain = Mage::getSingleton('fondof_contentful/callback_handler_chain');
        $callbackHandlerChain->execute($this->getRequest());
        $this->getResponse()->setHttpResponseCode(200);
    }
}
