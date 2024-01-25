{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($children) && $children}
    <ul class="homcat_tab_sub_categroies{if $sort_options} inline_sortby{/if}">
        {foreach from=$children item='category'}
            <li>
                <a class="homcat_tab_{$category.id_category|intval} {if !$ETS_HOMECAT_OPEN_CAT_BY_LINK}homecat_ajax_tab{if !$ETS_HOMECAT_TABS_GROUPED}_list{/if} homecat_ajax_tab_{if !$ETS_HOMECAT_TABS_GROUPED}list_{/if}{$category.id_category|intval}{/if} is_sub_cat homecat_tab_name" href="{$link->getCategoryLink($category.id_category)|escape:'html':'UTF-8'}" data-id-category="{$category.id_category|intval}">{$category.name|escape:'html'}</a>                
            </li>
        {/foreach}
    </ul>
{/if}