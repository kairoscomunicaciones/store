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
	<h3><i class="icon-cogs"></i> {l s='Order Grid Setting' mod='g_ordermanager'}</h3>
    <p>{l s='This is setting for Order Grid page. You can decide an order field (Order attribute) to appear/disappear in Order Grid page.' mod='g_ordermanager'}</p>
    <form action="{$current_url|escape:'html':'UTF-8'}" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Set action column as a first column' mod='g_ordermanager'}</label>
            <div class="col-lg-4">
				<label class="switch-light prestashop-switch fixed-width-lg">
					<input value="1" name="orderactioninfisrtcolumn" id="orderactioninfisrtcolumn" type="checkbox" {if isset($orderactioninfisrtcolumn) && $orderactioninfisrtcolumn} checked="checked" {/if} />
                    <span>
						<span>{l s='Yes' mod='g_ordermanager'}</span>
						<span>{l s='No' mod='g_ordermanager'}</span>
					</span>
					<a class="slide-button btn"></a>
				</label>
			</div>
        </div>
        <div class="form-group ">
            <label class="control-label col-lg-3"></label>
            <div class="col-lg-6">
    			<section class="filter_panel">
                    <section class="filter_list">
    					<ul class="list-unstyled sortable">
                            {foreach $order_fields_position as $orderfield}
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
    								<div class="col-lg-4">
    									<h4>{$orderfield.name|escape:'html':'UTF-8'}</h4>
    								</div>
                                    
    								<div class="col-lg-4 pull-right">
    									<label class="control-label col-lg-5">{l s='Align:' mod='g_ordermanager'}</label>
    									<div class="col-lg-7">
    										<select name="order_fields_align[{$orderfield.key|escape:'html':'UTF-8'}]">
    											<option value="text-left" {if isset($order_fields_align[$orderfield.key]) && $order_fields_align[$orderfield.key] == 'text-left'} selected="selected" {/if}>{l s='Left' mod='g_ordermanager'}</option>
    											<option value="text-center" {if isset($order_fields_align[$orderfield.key]) && $order_fields_align[$orderfield.key] == 'text-center'} selected="selected" {/if}>{l s='Center' mod='g_ordermanager'}</option>
    											<option value="text-right" {if isset($order_fields_align[$orderfield.key]) && $order_fields_align[$orderfield.key] == 'text-right'} selected="selected" {/if}>{l s='Right' mod='g_ordermanager'}</option>
    										</select>
    									</div>
    								</div>
                                    
    							</li>
                                {/foreach}
                        </ul>
                    </section>
                </section>
                <div class="help-block">{l s='Select ' mod='g_ordermanager'}<code>{l s='Yes ' mod='g_ordermanager'}</code> {l s='If you want to display the selected Order Attribute in Order Grid page' mod='g_ordermanager'}<br/><code>{l s='Align: ' mod='g_ordermanager'}</code>{l s='Help you to align content of a column' mod='g_ordermanager'}</div>
            </div>
        </div>
        <div class="panel-footer" id="toolbar-footer">
			<button class="btn btn-default pull-right" id="submit-filter" name="SubmitOrderFields" type="submit"><i class="process-icon-save"></i> <span>{l s='Save' mod='g_ordermanager'}</span></button>
		</div>
    </form>
</div>