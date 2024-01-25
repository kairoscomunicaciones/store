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
        <link rel="stylesheet" type="text/css" media="screen" href="{$style}">
    </head>
    <body>
        {include file="$templatePath/loader.tpl"}

        <form method="post" action="{$form.action|escape:'html':'UTF-8'}" id="web_payment">
            {foreach from=$form.inputs item=input}
                <input type="{$input.type|escape:'html':'UTF-8'}" name="{$input.name|escape:'html':'UTF-8'}" value="{$input.value|escape:'html':'UTF-8'}"/>
            {/foreach}
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('web_payment').submit();
            });
        </script>
    </body>
    {hook h='displayPaymentByBinaries' mod=$moduleName}
</html>
