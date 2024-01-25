{*
* 2007-2022 ETS-Soft
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
* 
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs, please contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2022 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
<div id="footer_nlt" class="block_newsletter links col-lg-3 col-md-3 col-sm-12">
    <h4 class="text-uppercase title-footer-block">{l s='Sign up to our newsletter: ' d='Shop.Theme.Actions'}</h4>
      <form action="{$urls.pages.index|escape:'html':'UTF-8'}#footer" method="post">
            {if $conditions}
                <p>{$conditions|escape:'html':'UTF-8'}</p>
              {/if}
            <div class="block_newsletter_form">
                <div class="newsletter_submit">
                <input
                  class="btn btn-primary pull-xs-right"
                  name="submitNewsletter" type="submit" value="{l s='Subscribe' d='Shop.Theme.Actions'}" >
                <input
                  class="btn btn-primary pull-xs-right hidden-sm-up hidden-xs-down"
                  name="submitNewsletter"
                  type="submit"
                  value="{l s='OK' d='Shop.Theme.Actions'}"
                >
                </div>
                <div class="input-wrapper">
                  <input
                    name="email"
                    type="text"
                    value="{$value|escape:'html':'UTF-8'}"
                    placeholder="{l s='Enter your email...' d='Shop.Forms.Labels'}"
                  >
                </div>
                <input type="hidden" name="action" value="0">
                <div class="clearfix"></div>
            </div>
          <div class="col-xs-12">
            <div class="row">
              {if $msg}
                <p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
                  {$msg|escape:'html':'UTF-8'}
                </p>
              {/if}
              </div>
          </div>
      </form>
</div>
