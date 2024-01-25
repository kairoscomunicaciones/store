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

{extends file='page.tpl'}
{block name="page_content"}
    {capture name=path}{l s='Shipping' mod='codpro'}{/capture}
    <div class="col-lg-12 alert alert-info">
        {l s='Verify details of your order below and if everything is correct - place an order.' mod='codpro'}
    </div>
    {if Configuration::get('CODPRO_CSUMMARY')}
        <section id="main">
            <div class="cart-grid row">
                <!-- Left Block: cart product informations & shpping -->
                <div class="cart-grid-body col-xs-12 col-lg-8">
                    <!-- cart products detailed -->
                    <div class="card cart-container">
                        <div class="card-block">
                            <h1 class="h1">{l s='Shopping Cart' mod='codpro'}</h1>
                        </div>
                        <hr class="separator">
                        <div class="card-block">
                            <div class="alert alert-warning">
                                {l s='If you will change your cart you will be redirected back to section where you have to select shipping method again' mod='codpro'}
                            </div>
                        </div>
                        {block name='cart_overview'}
                            {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
                        {/block}
                    </div>
                </div>
                <div class="cart-grid-right col-xs-12 col-lg-4">
                    {block name='cart_summary'}
                        <div class="card cart-summary">
                            {block name='cart_totals'}
                                {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
                            {/block}
                        </div>
                    {/block}
                    <div class="card col-xs-12 col-lg-12">
                        <form name="codpropayment" id="codpropayment" action="{$link->getModuleLink('codpro', 'validation', [], true)|escape:'html'}" method="post">
                            <input type="hidden" name="confirm" value="1"/>
                            {capture name=path}{l s='Shipping' mod='codpro'}{/capture}
                            <div class="success" style="margin-top:10px;">
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2">{l s='You have chosen the Cash on Delivery method.' mod='codpro'}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {l s='COD Fee' mod='codpro'}
                                            {if $show_taxes}
                                                <div class="cart-summary-line">
                                                    <small class="label">
                                                        {l s='Included taxes' mod='codpro'}
                                                    </small>
                                                </div>
                                            {/if}
                                        </td>
                                        <td>
                                            <div class="cart-summary-line">
                                                <span class="value">{Tools::displayPrice($charge_value+$charge_value_tax)}</span>
                                            </div>
                                            {if $show_taxes}
                                                <div class="cart-summary-line">
                                                    <small class="value">{Tools::displayPrice($charge_value_tax)}</small>
                                                </div>
                                            {/if}
                                        </td>
                                    </tr>
                                    {if Configuration::get('CODPRO_HOWFEE') != 1}
                                        <tr>
                                            <td>
                                                {l s='Total' mod='codpro'}
                                            </td>
                                            <td>
                                                <div class="cart-summary-line">
                                                <span class="value">
                                                    {Tools::displayPrice($total+$charge_value+$charge_value_tax)}
                                                </span>
                                                </div>
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                prestashop.on("updateCart", function () {
                    window.location.replace(prestashop.urls.pages.order);
                });
            });
        </script>
    {else}
        <div class="card col-xs-12 col-lg-12">
            <form name="codpropayment" id="codpropayment" action="{$link->getModuleLink('codpro', 'validation', [], true)|escape:'html'}" method="post">
                <input type="hidden" name="confirm" value="1"/>
                {capture name=path}{l s='Shipping' mod='codpro'}{/capture}
                <div class="success" style="margin-top:10px;">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2">{l s='You have chosen the Cash on Delivery method.' mod='codpro'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Products total value in your cart is equal to' mod='codpro'}</td>
                            <td><span class="price">{Tools::displayPrice($total)}</span></td>
                        </tr>
                        <tr>
                            <td>{l s='The delivery charge fee is equal to' mod='codpro'}</td>
                            <td><span class="price">{Tools::displayPrice($charge_value)}</span></td>
                        </tr>
                        {if Configuration::get('CODPRO_HOWFEE') != 1}
                            <tr>
                                <td>{l s='The total amount of your order is' mod='codpro'}</td>
                                <td><span class="price">{Tools::displayPrice($total+$charge_value)}</span></td>
                            </tr>
                        {/if}
                    </table>
                </div>
            </form>
        </div>
    {/if}
    <p class="cart_navigation" id="cart_navigation">
        {if defpay::psversion()==7 || defpay::psversion(0) >= 8}
            <a href="{$link->getPageLink('order', true)}?step=3" class="button btn btn-default"><i class="material-icons">chevron_left</i>{l s='Other payment methods' mod='codpro'}</a>
            <button onclick="codpropayment.submit()" style="float:right;" type="submit" class="btn-primary btn"><span>{l s='I confirm my order' mod='codpro'}</span></button>
        {/if}
    </p>
{/block}