{**
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2023 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*}

<div class="row">
    <div class="col-xs-12">
        <p class="payment_module">
            {l s='Dear customer we give you possibility to pay for this order with delay.' mod='defpay'}<br/>
            {l s='Your payment will be deffered for:' mod='defpay'} <strong>{$defpayuser_days} {l s='days' mod='defpay'}</strong><br/>
            {l s='Payment deadline:' mod='defpay'} <strong>{Tools::displayDate($defpayuser_date)}</strong> <br/>
        </p>
    </div>
</div>


