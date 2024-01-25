{*
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
<html>
    <head>
        <link rel="stylesheet" type="text/css" media="screen" href="{$style|escape:'htmlall':'UTF-8'}">
    </head>
    <body>
        {include file="$templatePath/loader.tpl"}
    </body>
    {if $mode eq 1}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var checkoutSessionId = urlParams.get('amazonCheckoutSessionId');
                var data = {
                    amazonCheckoutSessionId: checkoutSessionId
                };

                fetch('{$ajax_url|escape:'javascript':'UTF-8'}',{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '{$success_url|escape:'javascript':'UTF-8'}';
                    } else {
                        switch (response.status) {
                            case 412:
                                window.location.href = '{$address_url|escape:'javascript':'UTF-8'}';
                                break;
                            default:
                                window.location.href = '{$error_url|escape:'javascript':'UTF-8'}';
                                break;
                        }
                    }
                });
            });

        </script>
    {elseif $mode ne 2}
        <script src="{$sdkUrl|escape:'javascript':'UTF-8'}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var payload = '{$amazonpay.button_payload|@json_encode|escape}'.replaceAll('&quot;', '"');

                {if $amazonpay.sandbox}
                var sandbox = true;
                {else}
                var sandbox = false;
                {/if}
                amazon.Pay.initCheckout({
                    merchantId: '{$amazonpay.merchant_id|escape:'javascript':'UTF-8'}',
                    ledgerCurrency: '{$amazonpay.ledgerCurrency|escape:'javascript':'UTF-8'}',
                    sandbox: sandbox,
                    checkoutLanguage: '{$amazonpay.checkoutLanguage|escape:'javascript':'UTF-8'}',
                    productType: '{$amazonpay.checkoutType|escape:'javascript':'UTF-8'}',
                    placement: 'Checkout',
                    createCheckoutSessionConfig: {
                        payloadJSON: payload,
                        signature: '{$amazonpay.button_signature|escape:'javascript':'UTF-8'}',
                        publicKeyId: '{$amazonpay.public_key_id|escape:'javascript':'UTF-8'}'
                    }
                });
            })

        </script>
    {/if}
</html>
