<?php

/**
 * Catalog product helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Catalog_Product extends Mage_Core_Helper_Abstract
{
    /**
     * Retrieve list by skus
     *
     * @param $skus
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getList($skus)
    {
        /** @var Mage_Catalog_Model_Resource_Product_Collection $products */
        $products = Mage::getResourceModel('catalog/product_collection');

        if (!is_array($skus) || !count($skus)) {
            return $products->addIdFilter();
        }

        $products->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributeToFilter('sku', array('in' => $skus))
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addUrlRewrite();

        $orderExpression = new Zend_Db_Expr('FIELD(e.sku, \'' . implode('\',\'', $skus) . '\')');
        $products->getSelect()->order($orderExpression);

        $backendModel = $products->getResource()->getAttribute('media_gallery')->getBackend();

        foreach($products as $product){
            $backendModel->afterLoad($product);
        }

        return $products;
    }
}
