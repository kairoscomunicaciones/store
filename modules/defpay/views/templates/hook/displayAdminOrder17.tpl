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

<div class="card">
    <div class="card-header">
        <h3>{l s='This order is a subject of deffered payment' mod='defpay'}</h3>
    </div>
    <div class="card-body">
            {l s='Customer selected deffered payment method' mod='defpay'}<br/>
            {l s='This order is deffered for:' mod='defpay'} <strong>{$defpayuser_days} {l s='days' mod='defpay'}</strong><br/>
            {l s='Payment deadline:' mod='defpay'} <strong>{$defpayuser_date}</strong>
    </div>
</div>