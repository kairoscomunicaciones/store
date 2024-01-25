{*
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
*}
<section id="main">
    <div class="cart-grid row">
      <!-- Left Block: cart product informations & shpping -->
      <div class="cart-grid-body col-xs-12 col-lg-12">
        <!-- cart products detailed -->
        <div class="card cart-container">
            {include file='module:ets_onepagecheckout/views/templates/hook/cart-detailed.tpl' cart=$cart}
        </div>
        <div class="card cart-total-action ass">
            <div class="row">
                {if $opc_layout =='layout_3' || $opc_layout=='layout_4'}
                    <div class="col-lg-12">
                        <div class="card cart-summary">
                            {include file='module:ets_onepagecheckout/views/templates/hook/cart-detailed-totals.tpl' cart=$cart}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card cart-summary">
                            {include file='module:ets_onepagecheckout/views/templates/hook/cart-voucher.tpl'}
                        </div>
                    </div>
                {else}
                    <div class="col-lg-6">
                        <div class="card cart-summary">
                            {include file='module:ets_onepagecheckout/views/templates/hook/cart-voucher.tpl'}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card cart-summary">
                            {include file='module:ets_onepagecheckout/views/templates/hook/cart-detailed-totals.tpl' cart=$cart}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
          {hook h='displayShoppingCartFooterOnepageCheckout'}
      </div>
    </div>
</section>