{*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.txt
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
*}

{extends file=$order_confirmation_template_file}
{block name='order_confirmation_table'}
  {block name='order-items-table-head'}
  <div id="order-items" class="col-md-8">
    <h3 class="card-title h3">{l s='Order items' d='Shop.Theme.Checkout'}</h3>
  {/block}
    <div class="order-confirmation-table" style="text-align: center;">
      <table class="table">
        {foreach from=$orderProducts item=product}
          {if isset($product.isBookingProduct) && $product.isBookingProduct}
            {if isset($product.booking_product_data) && $product.booking_product_data}
              {foreach from=$product.booking_product_data item=bookingProduct}
                <div class="order-line row">
                  <div class="col-sm-2 col-xs-3">
                    <span class="image">
                      {if !empty($product.default_image)}
                        <img src="{$product.default_image.medium.url}" loading="lazy" />
                      {else}
                        <img src="{$urls.no_picture_image.bySize.medium_default.url}" loading="lazy" />
                      {/if}
                    </span>
                  </div>
                  <div class="col-sm-4 col-xs-9 details">
                    {if $add_product_link}<a href="{$product.url}" target="_blank">{/if}
                      <span>{$product.name}</span>
                    {if $add_product_link}</a>{/if}
                    {if $product.customizations|count}
                      {foreach from=$product.customizations item="customization"}
                        <div class="customizations">
                          <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                        </div>
                        <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                              </div>
                              <div class="modal-body">
                                {foreach from=$customization.fields item="field"}
                                  <div class="product-customization-line row">
                                    <div class="col-sm-3 col-xs-4 label">
                                      {$field.label}
                                    </div>
                                    <div class="col-sm-9 col-xs-8 value">
                                      {if $field.type == 'text'}
                                        {if (int)$field.id_module}
                                          {$field.text}
                                        {else}
                                          {$field.text}
                                        {/if}
                                      {elseif $field.type == 'image'}
                                        <img src="{$field.image.small.url}">
                                      {/if}
                                    </div>
                                  </div>
                                {/foreach}
                              </div>
                            </div>
                          </div>
                        </div>
                      {/foreach}
                    {/if}
                    {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                  </div>
                  <div class="col-sm-6 col-xs-12 qty">
                    <div class="row">
                      <div class="col-xs-4 text-sm-right text-xs-left booking_date_range" style="font-size: 14px; text-align: center!important;">
                        {if $bookingProduct['booking_type'] == 1}
                          {Tools::displayDate($bookingProduct['date_from'])}</br>
                          {l s='to' mod='psbooking'}</br>
                          {Tools::displayDate($bookingProduct['date_to'])}
                        {else}
                          {Tools::displayDate($bookingProduct['date_from'])}</br>
                          {$bookingProduct['time_from']} - {$bookingProduct['time_to']}
                        {/if}
                      </div>
                      <div class="col-xs-1">{$bookingProduct['quantity']}</div>
                      <div class="col-xs-3">
                        {if (!$priceDisplay || $priceDisplay == 2)}
                          {$bookingProduct['unit_feature_price_tax_incl_formated']}
                        {else}
                          {$bookingProduct['unit_feature_price_tax_excl_formated']}
                        {/if}
                      </div>
                      <div class="col-xs-4 text-xs-right bold">
                        {if (!$priceDisplay || $priceDisplay == 2)}
                          {$bookingProduct['total_range_feature_price_tax_incl_formated']}
                        {else}
                          {$bookingProduct['total_range_feature_price_tax_excl_formated']}
                        {/if}
                      </div>
                    </div>
                  </div>
                </div>
              {/foreach}
            {/if}
          {else}
            <div class="order-line row">
              <div class="col-sm-2 col-xs-3">
                <span class="image">
                  {if !empty($product.default_image)}
                    <img src="{$product.default_image.medium.url}" loading="lazy" />
                  {else}
                    <img src="{$urls.no_picture_image.bySize.medium_default.url}" loading="lazy" />
                  {/if}
                </span>
              </div>
              <div class="col-sm-4 col-xs-9 details">
                {if $add_product_link}<a href="{$product.url}" target="_blank">{/if}
                  <span>{$product.name}</span>
                {if $add_product_link}</a>{/if}
                {if $product.customizations|count}
                  {foreach from=$product.customizations item="customization"}
                    <div class="customizations">
                      <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                    </div>
                    <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                          </div>
                          <div class="modal-body">
                            {foreach from=$customization.fields item="field"}
                              <div class="product-customization-line row">
                                <div class="col-sm-3 col-xs-4 label">
                                  {$field.label}
                                </div>
                                <div class="col-sm-9 col-xs-8 value">
                                  {if $field.type == 'text'}
                                    {if (int)$field.id_module}
                                      {$field.text}
                                    {else}
                                      {$field.text}
                                    {/if}
                                  {elseif $field.type == 'image'}
                                    <img src="{$field.image.small.url}">
                                  {/if}
                                </div>
                              </div>
                            {/foreach}
                          </div>
                        </div>
                      </div>
                    </div>
                  {/foreach}
                {/if}
                {hook h='displayProductPriceBlock' product=$product type="unit_price"}
              </div>
              <div class="col-sm-6 col-xs-12 qty">
                <div class="row">
                  <div class="col-xs-5">{$product.quantity}</div>
                  <div class="col-xs-3 text-sm-right text-xs-left">{$product.price}</div>
                  <div class="col-xs-4 text-xs-right bold">{$product.total}</div>
                </div>
              </div>
            </div>
          {/if}
        {/foreach}
      <hr />
      <table>
        {foreach $subtotals as $subtotal}
          {if $subtotal.type !== 'tax'}
            <tr>
              <td>{$subtotal.label}</td>
              <td>{$subtotal.value}</td>
            </tr>
          {/if}
        {/foreach}
        {if $subtotals.tax.label !== null}
          <tr class="sub">
            <td>{$subtotals.tax.label}</td>
            <td>{$subtotals.tax.value}</td>
          </tr>
        {/if}
        <tr class="font-weight-bold">
          <td><span class="text-uppercase">{$totals.total.label}</span> {$labels.tax_short}</td>
          <td>{$totals.total.value}</td>
        </tr>
      </table>
    </div>
  </div>
{/block}
