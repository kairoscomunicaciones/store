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
<body>
<form id="payonweb_confirmation" name="payonweb_confirmation" action="{$payonweb_url|escape:'htmlall':'UTF-8'}" method="post" >

    <input type="hidden" name="mid" value="{$mid|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="nome" value="{$firstname|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="cognome" value="{$lastname|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="amount" value="{$amount|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="residenza" value="{$address|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="address2" value="{$address2|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="citta" value="{$city|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="stato" value="{$state_name|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="telefono" value="{$phone|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="email" value="{$email|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="codice_acquisto" value="{$codice_acquisto|escape:'htmlall':'UTF-8'}" />
    <p>{$text|escape:'htmlall':'UTF-8'}</p>
</form>
<script>
    document.getElementById("payonweb_confirmation").submit();
</script>
</body>
</html>
