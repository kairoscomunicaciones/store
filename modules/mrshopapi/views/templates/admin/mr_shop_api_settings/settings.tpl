{**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* You must not modify, adapt or create derivative works of this source code
*
* @author Mr. APPs
* @copyright Mr. APPs 2023
* @license Mr. APPs
*}

<div id="main" style="padding-top: 0;">
    <div id="mrshop-widget"
            api-base-url="{$apiBaseUrl|escape:'javascript':'UTF-8'}"
            api-admin-url="{$apiAdminUrl|escape:'javascript':'UTF-8'}"
            is-multistore="{$isMultistore|escape:'javascript':'UTF-8'}"
            shop-description="{$shopDescription|escape:'javascript':'UTF-8'}"
            is-ps-billing="{$isPsBilling|escape:'javascript':'UTF-8'}"
    ></div>


    <script type="text/javascript">
        document.tidioIdentify = {
            distinct_id: "{$appSecretCode|escape:'javascript':'UTF-8'}",
            email: "{$merchantEmail|escape:'javascript':'UTF-8'}"
        };
    </script>

    {if $isPsBilling}
    <script src="{$urlBilling|escape:'htmlall':'UTF-8'}" rel=preload></script>
    {/if}

    <script src="{$widgetJavascriptUrl|escape:'javascript':'UTF-8'}" type="text/javascript"></script>
    <script src="{$widgetVendorsJavascriptUrl|escape:'javascript':'UTF-8'}" type="text/javascript"></script>
    <script src="//code.tidio.co/sqzqqirvejmuz0hywuvw5wvooc0rfizd.js" type="text/javascript" async></script>
    <style>
        #content.bootstrap {
            padding: 25px 10px 0 225px !important;
        }
    </style>
</div>
