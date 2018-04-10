<?php

/**
 * Navigation item renderer category block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderer_Category extends FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract
{
    /**
     * Retrieve category
     *
     * @return Mage_Catalog_Model_Category|null
     */
    protected function _getCategory()
    {
        if ($this->hasData('category')) {
            return $this->getData('category');
        }

        $categoryId = '';

        if (array_key_exists('typeId', $this->_item) && $this->_item['typeId'] != '') {
            $categoryId = $this->_item['typeId'];
        }

        if (!$categoryId) {
            $this->setData('category', null);
            return null;
        }

        $category = Mage::getModel('catalog/category')->load($categoryId);

        if (!$category || !($category instanceof Mage_Catalog_Model_Category) || !$category->getId()) {
            $this->setData('category', null);
            return null;
        }

        $this->setData('category', $category);
        return $category;
    }

    /**
     * Retrieve current category
     *
     * @return Mage_Catalog_Model_Category|null
     */
    protected function _getCurrentCategory()
    {
        if ($this->hasData('current_category')) {
            return $this->getData('current_category');
        }

        $category = Mage::registry('current_category');

        if (!$category || !($category instanceof Mage_Catalog_Model_Category) || !$category->getId()) {
            $this->setData('current_category', null);
            return null;
        }

        $this->setData('current_category', $category);
        return $category;
    }



    /**
     * Retrieve link text
     *
     * @return string
     */
    public function getLinkText()
    {
        if (array_key_exists('customText', $this->_item) && $this->_item['customText'] != '') {
            return $this->_item['customText'];
        }

        $category = $this->_getCategory();

        if ($category === null) {
            return '';
        }

        return $category->getName();
    }

    /**
     * Retrieve href
     *
     * @return string
     */
    public function getHref()
    {
        $category = $this->_getCategory();

        if ($category === null) {
            return '';
        }

        return $category->getUrl();
    }

    /**
     * Has active state
     *
     * @return bool
     */
    public function hasActiveState()
    {
        if ($this->hasData('active_state')) {
            return $this->getData('active_state');
        }

        $category = $this->_getCategory();
        $currentCategory = $this->_getCurrentCategory();

        $activeState = $category !== null && $currentCategory !== null
            && $category->getId() == $currentCategory->getId();

        $this->setData('active_state', $activeState);

        return $activeState;
    }
}
