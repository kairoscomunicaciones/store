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
        {$monetico_form nofilter} {* HTML comment, no escape necessary because the form is rendered by monetico1f module *}
    </body>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('cmcic_form').submit();
            });
        </script>
</html>
