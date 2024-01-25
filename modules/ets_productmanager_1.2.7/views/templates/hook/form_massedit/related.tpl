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
var delete_related_comfirm = '{l s='Do you want to delete related product?' mod='ets_productmanager' js=1}';
var ets_mp_url_search_related_product ='{$link->getAdminLink('AdminProductManagerAjax') nofilter}&searchRelatedProduct=1&disableCombination=1'
</script>
<div id="related-content" class="row hide">
    <div class="col-xl-8 col-lg-11">
        <fieldset class="form-group">
            <div class="autocomplete-search">
                <div class="search search-with-icon">
                    <span class="twitter-typeahead" style="position: relative; display: block;">
                        <input id="form_step1_related_products" class="form-control search typeahead form_step1_related_products tt-input" placeholder="{l s='Search a related product' mod='ets_productmanager'}" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;" type="text" />
                    </span>
                </div>
            </div>
        </fieldset>
        <small class="form-text text-muted text-left typeahead-hint"> </small>
        <ul id="form_step1_related_products-data" class="typeahead-list nostyle col-sm-12 product-list">
            
        </ul>
        <div id="tplcollection-form_step1_related_products" class="invisible">
            <span class="label">%s</span>
            <i class="icon delete-icon"></i>
        </div>
    </div>
    <div class="col-md-1">
        <fieldset class="form-group">
            <a id="reset_related_product" class="btn tooltip-link delete pl-0 pr-0">
                <i class="fa fa-trash-o"></i>
            </a>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <button id="add-related-product-button" class="btn btn-outline-primary" type="button" >
            <i class="ets_svg_icon">
                <svg class="w_14 h_14" width="16" height="16" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1344 960v-128q0-26-19-45t-45-19h-256v-256q0-26-19-45t-45-19h-128q-26 0-45 19t-19 45v256h-256q-26 0-45 19t-19 45v128q0 26 19 45t45 19h256v256q0 26 19 45t45 19h128q26 0 45-19t19-45v-256h256q26 0 45-19t19-45zm320-64q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>
            </i>
            {l s='Add a related product' mod='ets_productmanager'}
        </button>
    </div>
</div>