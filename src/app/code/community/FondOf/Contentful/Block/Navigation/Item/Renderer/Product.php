<?php

/**
 * Navigation item renderer product block
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 *
 * @codeCoverageIgnore
 */
class FondOf_Contentful_Block_Navigation_Item_Renderer_Product extends FondOf_Contentful_Block_Navigation_Item_Renderer_Abstract
{
    /**
     * Retrieve product
     *
     * @return Mage_Catalog_Model_Product|null
     */
    protected function _getProduct()
    {
        if ($this->hasData('product')) {
            return $this->getData('product');
        }

        $sku = '';

        if (array_key_exists('typeId', $this->_item) && $this->_item['typeId'] != '') {
            $sku = $this->_item['typeId'];
        }

        if (!$sku) {
            $this->setData('product', null);
            return null;
        }

        $productId = Mage::getModel('catalog/product')->getIdBySku($sku);

        if (!$productId) {
            $this->setData('product', null);
            return null;
        }

        $product = Mage::getModel('catalog/product')->load($productId);

        if (!$product || !($product instanceof Mage_Catalog_Model_Product) || !$product->getId()) {
            $this->setData('product', null);
            return null;
        }

        $this->setData('product', $product);
        return $product;
    }

    /**
     * Retrieve current product
     *
     * @return Mage_Catalog_Model_Product|null
     */
    protected function _getCurrentProduct()
    {
        if ($this->hasData('current_product')) {
            return $this->getData('current_product');
        }

        $product = Mage::registry('current_product');

        if (!$product || !($product instanceof Mage_Catalog_Model_Product) || !$product->getId()) {
            $this->setData('current_product', null);
            return null;
        }

        $this->setData('current_product', $product);
        return $product;
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

        $product = $this->_getProduct();

        if ($product === null) {
            return '';
        }

        return $product->getName();
    }

    /**
     * Retrieve href
     *
     * @return string
     */
    public function getHref()
    {
        $product = $this->_getProduct();

        if ($product === null) {
            return '';
        }

        return $product->getUrlModel()->getUrl($product, array('_ignore_category' => true));
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

        $product = $this->_getProduct();
        $currentProduct = $this->_getCurrentProduct();

        $activeState = $product !== null && $currentProduct !== null
            && $product->getId() == $currentProduct->getId();

        $this->setData('active_state', $activeState);

        return $activeState;
    }
}
