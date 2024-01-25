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

<div id="main" style="padding-top: 0px;">
    <prestashop-accounts></prestashop-accounts>

    <script src="{$urlAccountsCdn|escape:'htmlall':'UTF-8'}" rel=preload></script>

    <script>
        window?.psaccountsVue?.init();
        let interval = setInterval(() => {
            if(window.psaccountsVue.isOnboardingCompleted()){
                clearInterval(interval);
                location.reload();
            }
        }, 500)
    </script>
    <style>
        #content.bootstrap {
            padding: 25px 10px 0 225px !important;
        }
    </style>
</div>
