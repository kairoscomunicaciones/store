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

{if isset($bookingProductCartInfo)}
  {foreach $bookingProductCartInfo as $key => $productBooking}
  <div class="cart_pop_up_data range-period">
      <div class="booking-dates">
        {if $productBooking['booking_type'] == 1}
          {Tools::displayDate($productBooking['date_from'])}&nbsp;
          {l s='to' mod='psbooking'}&nbsp;
          {Tools::displayDate($productBooking['date_to'])}
        {else}
          {Tools::displayDate($productBooking['date_from'])}&nbsp;
          {$productBooking['time_from']} - {$productBooking['time_to']}
        {/if}
      </div>
      <div class="booking-quantity">
        <span style="font-weight: bold;">{l s='Total quantity' mod='psbooking'}</span>&nbsp;&nbsp;-&nbsp;&nbsp;{$productBooking['quantity']}
      </div>
      <div class="booking-price">
        <span style="font-weight: bold;">{l s='Total price' mod='psbooking'}</span>&nbsp;&nbsp;-&nbsp;&nbsp;{$productBooking['totalPriceTE']} (tax excl.)
      </div>
  </div>
  {/foreach}
{/if}
<style type="text/css">
  .product-name ~ p {
    display: none;
  }
  .booking-dates {
    font-weight:bold;
  }
  .cart_pop_up_data {
    font-size: 12px;
    color: #333;
    border-bottom:1px solid #333;
  }
</style>
