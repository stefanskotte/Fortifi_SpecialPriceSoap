<?php

class Fortifi_SpecialPriceSoap_Model_Product_Api extends Mage_Api_Model_Resource_Abstract
{
    public function update($productsData = array()):q
    {
        //Mage::log("hit setSpecialPrices soap call");

        //Mage::log($productsData);
        foreach ($productsData as $product) {

            $storeId = 0;

            $id = Mage::getSingleton('catalog/product')->getIdBySku($product->sku);

            if ($id != "") {

                if ($product->store != "") {
                    $storeId = $product->store;
                }

                // always present
                $attributes['special_price'] = $product->specialPrice;

                if ($product->fromDate != "") {
                    $attributes['special_from_date'] = $product->fromDate;
                }

                if ($product->toDate != "") {
                    $attributes['special_to_date'] = $product->toDate;
                }

                Mage::getModel('catalog/product_action')->updateAttributes(array($id), $attributes, $storeId);

                //Mage::log("product special price update: ");
                //Mage::log($attributes);

            } else {
                Mage::log($product->sku . " skipped, product did not exist.");
            }
        }

        return true;

    }

    public function fault($code, $message)
    {
        throw new Zend_XmlRpc_Server_Exception($message, $code);
    }

}