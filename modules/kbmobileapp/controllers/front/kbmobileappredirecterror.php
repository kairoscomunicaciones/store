<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to get personal info and orders of customer
 */
class KbMobileAppkbkbmobileappredirecterrorModuleFrontController extends ModuleFrontController
{
    /*
     * Build an front controller
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Defualt prestashop function for front controller
     */
    public function init()
    {
        parent::init();
        $this->cartChecksum = new CartChecksum(new AddressChecksum());
    }

    /*
     * Default front controller initialize function
     */
    public function initContent()
    {
        parent::initContent();
        die();
    }
} 