<?php

/**
 * Mapping model
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Model_Mapping extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'fondof_contentful_mapping';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'mapping';

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * When you use true - all cache will be clean
     *
     * @var string
     */
    protected $_cacheTag = '';

    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('fondof_contentful/mapping');
    }

    /**
     * Get entry id by entry identifier
     *
     * @param $entryIdentifier
     * @param string $contentType
     *
     * @return string
     */
    public function getEntryIdByEntryIdentifierAndContentType($entryIdentifier, $contentType = 'page')
    {
        return $this->_getResource()->getEntryIdByEntryIdentifierAndContentType($entryIdentifier, $contentType);
    }

    /**
     * Delete by entry id
     *
     * @param $entryId
     * @return $this
     */
    public function deleteByEntryId($entryId)
    {
        $this->_getResource()->deleteByEntryId($entryId);

        return $this;
    }
}
