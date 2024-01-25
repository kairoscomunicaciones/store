{*
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FME Modules.
*
*  @author    FMM Modules
*  @copyright FME Modules 2020
*  @license   Single domain
*}
{if !empty($payments_meths) && $rpm_show > 0}
<div id="rpm_payments_list">
    <table>
        <thead>
            <tr>
                <th>{if empty($rpm_heading)}{l s='Payment Methods Available' mod='restrictpaymentmethods'}{else}{$rpm_heading|escape:'htmlall':'UTF-8'}{/if}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$payments_meths item=meths}
                <tr>
                    <td>{$meths->displayName|escape:'htmlall':'UTF-8'}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{/if}