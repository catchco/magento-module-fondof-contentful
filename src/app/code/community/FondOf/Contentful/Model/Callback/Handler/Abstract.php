<?php

/**
 * Abstract callback handler
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 *
 * @codeCoverageIgnore
 */
abstract class FondOf_Contentful_Model_Callback_Handler_Abstract
{
    /**
     * Handle callback
     *
     * @param string $topic
     * @param mixed $body
     * @return FondOf_Contentful_Model_Callback_Handler_Abstract
     */
    public function handle($topic, $body) {
        if (!$this->_canHandle($topic, $body)) {
            return $this;
        }

        return $this->_handle($topic, $body);
    }

    /**
     * Handle callback
     *
     * @param string $topic
     * @param mixed $body
     * @return FondOf_Contentful_Model_Callback_Handler_Abstract
     */
    abstract protected function _handle($topic, $body);

    /**
     * Can handle
     *
     * @param $topic
     * @param $body
     *
     * @return bool
     */
    abstract protected function _canHandle($topic, $body);

}
