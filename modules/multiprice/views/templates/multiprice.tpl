{*
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2020 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*}


<div class="multiprice_container">
    {foreach $multiprice as $multicurrency => $mprice}
        <br/>
        {if Configuration::get('MP_TIN')}
            <span class="multicurrency_tin multicurrency_tin_product_{$multiprice_id_product} multicurrency_tin_{$multicurrency}">{$mprice.tax_inc}</span>
        {/if}
        {if Configuration::get('MP_TINL')}
            {l s='tax incl.' mod='multiprice'}
        {/if}

        {if Configuration::get('MP_TEX')}
            <span class="multicurrency_tex multicurrency_tex_product_{$multiprice_id_product} multicurrency_tex_{$multicurrency}">{$mprice.tax_exc}</span>
        {/if}
        {if Configuration::get('MP_TEXL')}
            {l s='tax excl.' mod='multiprice'}
        {/if}
    {/foreach}
</div>