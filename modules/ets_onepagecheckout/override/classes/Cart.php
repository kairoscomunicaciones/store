<?php
/**
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */
 
class Cart extends CartCore
{
    public function getPackageList($flush = false)
    {
        if(($address_type =  Tools::getValue('address_type')) && $address_type=='shipping_address')
            $this->id_address_delivery = (int)Tools::getValue('id_address',$this->id_address_delivery);
        return parent::getPackageList($flush);
    }
    public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null, bool $keepOrderPrices = false)
    {
        if($IDzone = (int)Hook::exec('actionGetIDZoneByAddressID'))
        {
            $id_zone = $IDzone;
        }
        
        return parent::getPackageShippingCost($id_carrier,$use_tax,$default_country,$product_list,$id_zone, $keepOrderPrices);
    }
}