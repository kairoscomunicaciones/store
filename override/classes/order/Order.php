<?php
/**
* This file will override class OrderCore. Do not modify this file if you want to upgrade the module in future
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link      http://www.globosoftware.net
*/
class Order extends OrderCore
{
    /*
    * module: gwadvancedinvoice
    * date: 2022-11-29 00:05:55
    * version: 1.3.1
    */
    public static function setLastInvoiceNumber($order_invoice_id, $id_shop)
    {
        if (!$order_invoice_id) {
            return false;
        }
        if(Module::isInstalled('gwadvancedinvoice') && Module::isEnabled('gwadvancedinvoice'))
        {
            $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
            $object = new OrderInvoice($order_invoice_id);
            $result = $invoiceObj->customizeNumber('I',$object);
            if(!$result) return parent::setLastInvoiceNumber($order_invoice_id, $id_shop);
            else return true;
        }
        else {
            return parent::setLastInvoiceNumber($order_invoice_id, $id_shop);
        }
    }
    /*
    * module: gwadvancedinvoice
    * date: 2022-11-29 00:05:55
    * version: 1.3.1
    */
    public function setDeliveryNumber($order_invoice_id, $id_shop)
    {
        if (!$order_invoice_id) {
            return false;
        }
        if(Module::isInstalled('gwadvancedinvoice') && Module::isEnabled('gwadvancedinvoice'))
        {
            $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
            $object = new OrderInvoice($order_invoice_id);
            $result = $invoiceObj->customizeNumber('D',$object);
            if(!$result) return parent::setDeliveryNumber($order_invoice_id, $id_shop);
            else return true;
        }
        else {
            return parent::setDeliveryNumber($order_invoice_id, $id_shop);
        }
    }
}