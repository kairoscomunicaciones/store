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

<div class="box">
<p>
    <h2>{l s='Payment instructions' mod='defpay'}</h2><br/>
    {l s='You selected deffered payment method' mod='defpay'}<br/>
    {l s='Your order is deffered for:' mod='defpay'} <strong>{$defpayuser_days}</strong><br/>
    {l s='Payment deadline:' mod='defpay'} <strong>{$defpayuser_date}</strong><br/>
    {l s='your order ID number is:' mod='defpay'} <strong>{$order_id}</strong><br/>
    {l s='your order reference is:' mod='defpay'}: <strong>{$order_reference}</strong><br/><br/><br/>
    {$defpayuser_message nofilter}

</p>
</div>