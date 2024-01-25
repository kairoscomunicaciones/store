{*
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
*}
<script type="text/javascript">
var delete_related_comfirm = '{l s='Do you want to delete the related product?' mod='ets_productmanager' js=1}';
var delete_item_comfirm = '{l s='Do you want to delete this item?' mod='ets_productmanager' js=1}';
</script>
<script type="text/javascript" src="{$module_dir|escape:'html':'UTF-8'}/views/js/autocomplete.js"></script>
<link href="{$_PS_JS_DIR_|escape:'html':'UTF-8'}jquery/plugins/autocomplete/jquery.autocomplete.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{$module_dir|escape:'html':'UTF-8'}/views/js/product_related.js"></script>
<form class="defaultForm form-horizontal" action="" method="post" enctype="multipart/form-data" novalidate="">
    <div id="fieldset_0" class="panel">
        <div class="panel-heading">{l s='Related product' mod='ets_productmanager'}: {$product_name|escape:'html':'UTF-8'}</div>
        <div class="form-wrapper">
            <div id="related-content" class="row{if !$related_products} hide{/if}">
                <div class="col-xl-8 col-lg-11">
                    <fieldset class="form-group">
                        <div class="autocomplete-search">
                            <div class="search search-with-icon">
                                <span class="twitter-typeahead" style="position: relative; display: block;">
                                    <input id="form_step1_related_products" class="form-control search typeahead form_step1_related_products tt-input" placeholder="{l s='Search and add a related product' mod='ets_productmanager'}" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;" type="text" />
                                </span>
                            </div>
                        </div>
                    </fieldset>
                    <small class="form-text text-muted text-right typeahead-hint"> </small>
                    <ul id="form_step1_related_products-data" class="typeahead-list nostyle col-sm-12 product-list">
                        {if $related_products}
                            {foreach from=$related_products item='related_product'}
                                <li class="media">
                                    <div class="media-left">
                                        {if isset($related_product.img) && $related_product.img}
                                            <img class="media-object image" src="{$related_product.img|escape:'html':'UTF-8'}" />
                                        {/if}
                                    </div>
                                    <div class="media-body media-middle">
                                        <span class="label">{$related_product.name|escape:'html':'UTF-8'}{if $related_product.reference} (ref:{$related_product.reference|escape:'html':'UTF-8'}){/if}</span>
                                        <i class="delete delete_related ets_svg_icon ets_svg_times"></i>
                                    </div>
                                    <input name="related_products[]" value="{$related_product.id_product|intval}" type="hidden" />
                                </li>
                            {/foreach}
                        {/if}
                    </ul>
                    <div id="tplcollection-form_step1_related_products" class="invisible">
                        <span class="label">%s</span>
                        <i class="icon delete-icon"></i>
                    </div>
                </div>
                <div class="col-md-1">
                    <fieldset class="form-group">
                        <a id="reset_related_product" class="btn tooltip-link delete pl-0 pr-0">
                            <i class="ets_svg_icon ets_svg-trash-o"></i>
                        </a>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button id="add-related-product-button" class="btn btn-outline-primary" type="button"{if $related_products} style="display:none;"{/if} >
                        <i class="ets_svg_icon ets_svg-new fill_primary fill_hover_white">
                            <svg class="w_14 h_14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1344 960v-128q0-26-19-45t-45-19h-256v-256q0-26-19-45t-45-19h-128q-26 0-45 19t-19 45v256h-256q-26 0-45 19t-19 45v128q0 26 19 45t45 19h256v256q0 26 19 45t45 19h128q26 0 45-19t19-45v-256h256q26 0 45-19t19-45zm320-64q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>
                        </i>
                        {l s='Add a related product' mod='ets_productmanager'}
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" name="btnCancel" class="btn btn-default pull-left">
            <i class="process-icon-cancel svg_process-icon">
                <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"/></svg>

            </i> {l s='Cancel' mod='ets_productmanager'}</button>
            <input type="hidden" name="id_product" value="{$id_product|intval}" />
            <button id="module_form_submit_btn" class="btn btn-default pull-right" type="submit" value="1" name="submitRelatedProduct">
                <i class="process-icon-save ets_svg_process">
                    <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M512 1536h768v-384h-768v384zm896 0h128v-896q0-14-10-38.5t-20-34.5l-281-281q-10-10-34-20t-39-10v416q0 40-28 68t-68 28h-576q-40 0-68-28t-28-68v-416h-128v1280h128v-416q0-40 28-68t68-28h832q40 0 68 28t28 68v416zm-384-928v-320q0-13-9.5-22.5t-22.5-9.5h-192q-13 0-22.5 9.5t-9.5 22.5v320q0 13 9.5 22.5t22.5 9.5h192q13 0 22.5-9.5t9.5-22.5zm640 32v928q0 40-28 68t-68 28h-1344q-40 0-68-28t-28-68v-1344q0-40 28-68t68-28h928q40 0 88 20t76 48l280 280q28 28 48 76t20 88z"/></svg>
                </i>
                {l s='Save' mod='ets_productmanager'}
            </button>
        </div>
    </div>
</form>