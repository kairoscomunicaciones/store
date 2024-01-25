{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<div class="panel">
	<h3><i class="icon-download-alt"></i> {l s='CSV Settings' mod='g_ordermanager'}</h3>
    <p>{l s='This is basic setting for your CSV file. You can decide an order field to appear/disappear in CSV.' mod='g_ordermanager'}</p>
    <form action="{$current_url|escape:'html':'UTF-8'}" method="post" class="form-horizontal">
        <div class="form-group ">
            <label class="control-label col-lg-3">{l s='Field separator' mod='g_ordermanager'}</label>
            <div class="col-lg-6">
                <input name="field_separator"  class="fixed-width-xs form-control" type="text"  id="field_separator" value="{$field_separator|escape:'html':'UTF-8'}" />
                <div class="help-block">{l s='Example: #id_order;reference;customer;product;' mod='g_ordermanager'}</div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-lg-3">{l s='Multiple value separator' mod='g_ordermanager'}</label>
            <div class="col-lg-6">
                <input name="multiple_separator" class="fixed-width-xs form-control" type="text" id="multiple_separator" value="{$multiple_separator|escape:'html':'UTF-8'}" />
                <div class="help-block">{l s='Example: product name 1, product name 2, product name 3, product name 4,' mod='g_ordermanager'}</div>
            </div>
        </div>
        <div class="form-group ">
            <label class="control-label col-lg-3">{l s='Csv fields' mod='g_ordermanager'}</label>
            <div class="col-lg-6">
        			<section class="filter_panel">
                        <section class="filter_list">
        					<ul class="list-unstyled sortable">
                                {foreach $order_fields_position as $orderfield}
                                    {if $orderfield.key !='productimages'}
                                        <li class="filter_list_item" draggable="true">
                                            <div class="col-lg-4">
            									<label class="switch-light prestashop-switch fixed-width-lg">
            										<input class="" name="order_fields[{$orderfield.key|escape:'html':'UTF-8'}]" id="order_fields_{$orderfield.name|escape:'html':'UTF-8'}" type="checkbox" {if isset($order_fields[$orderfield.key])} checked="checked" {/if} />
            										<input type="hidden" name="order_fields_position[{$orderfield.key|escape:'html':'UTF-8'}]" />
                                                    <span>
            											<span>{l s='Yes' mod='g_ordermanager'}</span>
            											<span>{l s='No' mod='g_ordermanager'}</span>
            										</span>
            										<a class="slide-button btn"></a>
            									</label>
            								</div>
                                            <div class="col-lg-8">
            									<h4>{$orderfield.name|escape:'html':'UTF-8'}</h4>
            								</div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </section>
                    </section>
                <div class="help-block">{l s='Select ' mod='g_ordermanager'}<code>{l s='Yes ' mod='g_ordermanager'}</code> {l s='If you want to display the selected column in CSV' mod='g_ordermanager'}</div>
             </div>
        </div>
         <div class="panel-footer" id="toolbar-footer">
			<button class="btn btn-default pull-right" id="submit-filter" name="SubmitCsvExportFields" type="submit"><i class="process-icon-save"></i> <span>{l s='Save' mod='g_ordermanager'}</span></button>
		</div>
    </form>
</div>