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

{extends file='customer/_partials/address-form.tpl'}

{block name="address_form_url"}
    <form
            method="POST"
            action="{url entity='module' name='mrshopapi' controller='mobileamazonpayaddress' params=['id_address' => $id_address]}"
            data-id-address="{$id_address|escape:'htmlall':'UTF-8'}"
            data-refresh-url="{url entity='module' name='mrshopapi' controller='mobileamazonpayaddress' params=['ajax' => 1, 'action' => 'addressForm']}"
    >
{/block}
