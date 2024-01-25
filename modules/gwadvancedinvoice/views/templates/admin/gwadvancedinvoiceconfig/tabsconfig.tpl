{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

<ul class="tabs-create menuconfig">
    <li class="active"><a href="javascript:void(0);" data-id="configuration_form" data-tab="config_content">{l s='General' mod='gwadvancedinvoice'}</a></li>
    <li><a href="javascript:void(0);" data-id="configuration_form" data-tab="extra_content">{l s='Extra Content' mod='gwadvancedinvoice'}</a></li>
    {if isset($countlang) && $countlang > 1}
	    <li><a href="javascript:void(0);" data-id="configuration_form_1">{l s='TRANSLATE FOR {$TAX_TAB} VARIABLE' mod='gwadvancedinvoice'}</a></li>
	    <li><a href="javascript:void(0);" data-id="configuration_form_2">{l s='Add New Font' mod='gwadvancedinvoice'}</a></li>
	    {else}
		    <li><a href="javascript:void(0);" data-id="configuration_form_1">{l s='TRANSLATE FOR {$TAX_TAB} VARIABLE' mod='gwadvancedinvoice'}</a></li>
		    <li><a href="javascript:void(0);" data-id="configuration_form_1">{l s='Add New Font' mod='gwadvancedinvoice'}</a></li>
    {/if}
</ul>