<?php

/**
 * Callback handler chain
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 */
class FondOf_Contentful_Model_Callback_Handler_Chain
{
    /**
     * @var array
     */
    protected $_callbackHandlerPool = array();

    /**
     * @var
     */
    protected $_client;

    /**
     * FondOf_Contentful_Model_Callback_Handler_Pool constructor.
     */
    public function __construct()
    {
        $this->_client = Mage::getSingleton('fondof_contentful/client');
        $this->_init();
    }

    /**
     * Init
     *
     * @return $this
     */
    protected function _init()
    {
        $node = Mage::getConfig()->getNode('global/fondof_contentful/callback/handlers');

        if (!$node || !($node instanceof Mage_Core_Model_Config_Element) || !$node->hasChildren()) {
            return $this;
        }

        foreach ($node->children() as $child) {
            $callbackHandler = Mage::getModel($child->__toString());

            if (!$callbackHandler) {
                continue;
            }

            $this->_callbackHandlerPool[$child->getName()] = $callbackHandler;
        }

        return $this;
    }

    /**
     * Execute
     *
     * @param Mage_Core_Controller_Request_Http $request
     *
     * @return $this
     */
    public function execute(Mage_Core_Controller_Request_Http $request)
    {
        $topic = $request->getHeader('X-Contentful-Topic');

        if ($topic == '') {
            return $this;
        }

        $rawBody = $request->getRawBody();
        $body = $this->_client->reviveJson($rawBody);

        foreach ($this->_callbackHandlerPool as $callbackHandler) {
            if (!($callbackHandler instanceof FondOf_Contentful_Model_Callback_Handler_Abstract)) {
                continue;
            }

            $callbackHandler->handle($topic, $body);
        }

        return $this;
    }
}
