<?php
/**
* This file will override class OrderInvoiceCore. Do not modify this file if you want to upgrade the module in future
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link      http://www.globosoftware.net
*/

class OrderInvoice extends OrderInvoiceCore
{
    public function getInvoiceNumberFormatted($id_lang, $id_shop = null)
    {
        if(Module::isInstalled('gwadvancedinvoice') && Module::isEnabled('gwadvancedinvoice'))
        {
            $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
            return $invoiceObj->formatNumber('I',$this->number,$this);
        }else{
            return parent::getInvoiceNumberFormatted($id_lang, $id_shop);
        }
    }
    public function getDeliveryNumberFormatted($id_lang, $id_shop = null)
    {
        if(Module::isInstalled('gwadvancedinvoice') && Module::isEnabled('gwadvancedinvoice'))
        {
            $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
            return $invoiceObj->formatNumber('D',$this->delivery_number,$this);
        }else{
            return '#' . Configuration::get('PS_DELIVERY_PREFIX', $id_lang, null, $id_shop)
                . sprintf('%06d', (int)$this->delivery_number);
        }
    }
}
