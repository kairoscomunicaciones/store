{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

{extends file="helpers/view/view.tpl"}
{block name="override_tpl"}
<div class="row">
	<div class="col-sm-2">
        <div class="list-group" id="gwadvancedinvoice_tabs">
            {if isset($tabs) && $tabs}
                {foreach $tabs as $tab}
                    <a class="list-group-item {$tab.id|escape:'html':'UTF-8'} {if isset($tab.active) && $tab.active}active{/if}" id="{$tab.id|escape:'html':'UTF-8'}" href="">{$tab.label|escape:'html':'UTF-8'}</a>
                {/foreach}
            {/if}
        </div>
	</div>
	<div class="col-sm-10">
        {if isset($contents) && $contents}
            {foreach $contents as $key=> $content}
        		<div id="{$key|escape:'html':'UTF-8'}_wp" class="{if isset($content.active) && $content.active}active{/if} gwadvancedinvoice_tab_content">
                    {$content.content nofilter}{* html content (generate by HelperForm). No need to escape*}   
        		</div>
            {/foreach}
        {/if}
	</div>
</div>
{addJsDefL name=confirm_delete_text}{l s='Are you sure you want to delete this rule?' js=1 mod='gwadvancedinvoice'}{/addJsDefL}
{/block}
