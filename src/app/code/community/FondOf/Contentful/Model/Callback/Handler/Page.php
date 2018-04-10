<?php

/**
 * Page callback handler
 *
 * @category    FondOf
 * @package     FondOF_Contentful
 */
class FondOf_Contentful_Model_Callback_Handler_Page extends FondOf_Contentful_Model_Callback_Handler_Abstract
{
    /**
     * Handle callback
     *
     * @param string $topic
     * @param mixed $body
     * @return FondOf_Contentful_Model_Callback_Handler_Abstract
     */
    protected function _handle($topic, $body)
    {
        $explodedTopic = explode('.', $topic);

        switch ($explodedTopic[2]) {
            case 'publish':
                $this->_handlePublish($body);
                break;
            case 'unpublish':
                $this->_handleUnpublish($body->getId());
                break;
        }

        return $this;
    }

    /**
     * @param \Contentful\Delivery\DynamicEntry $body
     * @return $this
     */
    protected function _handlePublish(\Contentful\Delivery\DynamicEntry $body)
    {
        $entryId = $body->getId();
        $defaultLocale = Mage::helper('fondof_contentful')->getDefaultLocale();
        $defaultIdentifier = $body->getIdentifier($defaultLocale);
        $contentType = $body->getContentType()->getId();

        $this->_handleUnpublish($entryId);

        if ($defaultIdentifier) {
            $mappingData = array(
                'entry_id' => $entryId,
                'entry_identifier' => $defaultIdentifier,
                'content_type' => $contentType,
                'store_id' => Mage_Core_Model_App::ADMIN_STORE_ID
            );

            Mage::getModel('fondof_contentful/mapping')
                ->setData($mappingData)
                ->save();
        }

        foreach (Mage::app()->getStores() as $store) {
            $locale = Mage::helper('fondof_contentful')->getLocale($store);

            if ($locale == $defaultLocale) {
                continue;
            }

            $identifier = $body->getIdentifier($locale);

            if ($identifier == '' || $identifier == $defaultIdentifier) {
                continue;
            }

            $mappingData = array(
                'entry_id' => $entryId,
                'entry_identifier' => $identifier,
                'content_type' => $contentType,
                'store_id' => $store->getId()
            );

            Mage::getModel('fondof_contentful/mapping')
                ->setData($mappingData)
                ->save();
        }

        return $this;
    }

    /**
     * Handle unpublish
     *
     * @param $entryId
     * @return $this
     */
    protected function _handleUnpublish($entryId)
    {
        Mage::getModel('fondof_contentful/mapping')->deleteByEntryId($entryId);

        return $this;
    }

    /**
     * Can handle
     *
     * @param $topic
     * @param $body
     * @return bool
     */
    protected function _canHandle($topic, $body)
    {
        $isTopicValid = $topic && count(explode('.', $topic)) === 3;
        $isBodyValid = $body
            && $body instanceof \Contentful\Delivery\Synchronization\DeletedEntry
            || ($body instanceof \Contentful\Delivery\DynamicEntry && $body->getContentType()->getId() === 'page');

        return $isTopicValid && $isBodyValid;
    }
}
