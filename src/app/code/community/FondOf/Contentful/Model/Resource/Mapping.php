<?php

/**
 * Mapping resource model
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Model_Resource_Mapping extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('fondof_contentful/mapping', 'mapping_id');
    }

    /**
     * Get entry id by entry identifier
     *
     * @param string $entryIdentifier
     * @param string $contentType
     *
     * @return false|string
     */
    public function getEntryIdByEntryIdentifierAndContentType($entryIdentifier, $contentType = 'page')
    {
        $adapter = $this->_getReadAdapter();
        $entryId = '';
        $currentStore = Mage::app()->getStore();
        $stores = array($currentStore->getId());

        if (!$currentStore->isAdmin()) {
            $stores[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        }

        foreach ($stores as $store) {
            $select = $adapter->select()
                ->from($this->getMainTable(), 'entry_id')
                ->where('entry_identifier = :entry_identifier')
                ->where('content_type = :content_type')
                ->where('store_id = :store_id');

            $bind = array(
                ':entry_identifier' => (string) $entryIdentifier,
                ':content_type' => (string) $contentType,
                ':store_id' => (int) $store
            );

            $entryId = $adapter->fetchOne($select, $bind);

            if ($entryId != '' && $store != Mage_Core_Model_App::ADMIN_STORE_ID) {
                return $entryId;
            }
        }

        if ($entryId == '') {
            return $entryId;
        }

        $select = $adapter->select()
            ->from($this->getMainTable(), 'entry_identifier')
            ->where('entry_id = :entry_id')
            ->where('store_id = :store_id');

        $bind = array(
            ':entry_id' => (string) $entryId,
            ':store_id' => (int) $currentStore->getId()
        );

        $entryIdentifier = $adapter->fetchOne($select, $bind);

        if ($entryIdentifier != '') {
            return '';
        }

        return $entryId;
    }

    /**
     * Perform operations before object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setData('creation_time', Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setData('update_time', Mage::getSingleton('core/date')->gmtDate());

        return $this;
    }

    /**
     * Delete by entry id
     *
     * @param $entryId
     * @return int
     */
    public function deleteByEntryId($entryId) {
        $adapter = $this->_getWriteAdapter();

        return $adapter->delete($this->getMainTable(), array('entry_id = ?' => $entryId));
    }
}
