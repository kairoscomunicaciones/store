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
<div class="header_content">
{block name='header_nav'}
  <nav class="header-nav">
    <div class="container">
        <div class="nav">
            <div class="left-nav">
              {hook h='displayNav1'}
            </div>
            <div class="right-nav">
                {hook h='displayNav2'}
            </div>
        </div>
    </div>
  </nav>
{/block}

{block name='header_top'}
  <div class="mobile_logo">
    <div class="" id="_mobile_logo">
      <a href="{$urls.base_url|escape:'html':'UTF-8'}">
        <img class="logo img-responsive" src="{if isset($tc_dev_mode) && $tc_dev_mode && isset($logo_url)&&$logo_url}{$logo_url|escape:'html':'UTF-8'}{else}{$shop.logo|escape:'html':'UTF-8'}{/if}" alt="{$shop.name|escape:'html':'UTF-8'}">
      </a>
    </div>
  </div>
  <div class="header-top">
    <div class="container">
       <div class="row">
        <div class="hidden-sm-down" id="_desktop_logo">
          <a href="{$urls.base_url|escape:'html':'UTF-8'}">
            <img class="logo img-responsive" src="{if isset($tc_dev_mode) && $tc_dev_mode && isset($logo_url)&&$logo_url}{$logo_url|escape:'html':'UTF-8'}{else}{$shop.logo|escape:'html':'UTF-8'}{/if}" alt="{$shop.name|escape:'html':'UTF-8'}">
          </a>
        </div>
        <div class="contact_header">
            {if isset($tc_config.YBC_TC_ENABLE_STORE) && $tc_config.YBC_TC_ENABLE_STORE}
                <a class="contact-store-link" href="{if isset($tc_config.YBC_TC_ENABLE_STORE_LINK) && $tc_config.YBC_TC_ENABLE_STORE_LINK}{$tc_config.YBC_TC_ENABLE_STORE_LINK|escape:'html':'UTF-8'}{else}#{/if}">
                    <i class="fa fa-map-marker"></i> {l s='Store Locator' d='Shop.Theme.Actions'}
                </a>
            {/if}
            <a class="contact-link" href="tel:{$tc_config.BLOCKCONTACTINFOS_PHONE_CALL|escape:'html':'UTF-8'}">
                  <i class="fa fa-phone" aria-hidden="true"></i>
                  <span>{l s='Hotline: ' d='Modules.Contactinfo.Shop'}</span>{$tc_config.BLOCKCONTACTINFOS_PHONE_LABEL|escape:'html':'UTF-8'}
              </a>
        </div>
        {hook h='displayTop'}
      </div>
    </div>
    <div class="menu_header">
        <div class="container">
            <span class="hidden-lg-up mobile closed" id="menu-icon">
              <i class="icon_menu"></i>
            </span>
            {hook h='displayMegaMenu'}
        </div>
    </div>
  </div>
  {hook h='displayNavFullWidth'}
{/block}
</div>
<div class="slidershow">
    <div class="container">
        {hook h='displayMLS'}
    </div>
</div>

