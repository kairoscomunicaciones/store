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
            <div class="control-label col-lg-3">
            <div class="box_setting_showin_box box_csvsetting_showin_box">
					<div class="box_setting_showin_top box_setting_showinclose"></div>
					<div id="box_setting_showin" class="box_setting_showin">
						<div class="showin-heading-box">{l s='Setting' mod='g_ordermanager'}</div>
						<div class="gpopup_content">
								{foreach $orderfields as $orderfieldgroup}
									<h4>{$orderfieldgroup.label|escape:'html':'UTF-8'}</h4>
									<ul class="orderlistfield">
										{foreach $orderfieldgroup.cols as $orderfield}
                                            {if $orderfield.key !='productimages'}
                                                <li class="">
                                                    <label><input class="order_csv_field_config" rel="{$orderfield.key|escape:'html':'UTF-8'}" name="order_fields_position[{$orderfield.key|escape:'html':'UTF-8'}]" id="order_fields_{$orderfield.key|escape:'html':'UTF-8'}" type="checkbox" {if isset($order_fields[$orderfield.key])} checked="checked" {/if} /> {$orderfield.name|escape:'html':'UTF-8'}</label>
                                                </li>
                                            {/if}
										{/foreach}
									</ul>
								{/foreach}
							
						</div>
						<div class="gpopupp_footer">
							<button type="button" class="pull-right btn btn-default gbtn-default" id="box_csvsetting_showinreset"><i class="icon icon-refresh"></i> {l s='Reset to Default' mod='g_ordermanager'}</button>
                            <button type="button" class="btn btn-primary box_setting_showinclose">{l s='Close' mod='g_ordermanager'}</button>
						</div>
					</div>
				</div>
            </div>
            <div class="col-lg-6">
        			<section class="filter_panel panel">
                        <div class="panel-heading">
                            {l s='Csv fields' mod='g_ordermanager'}
                            <a href="#" class="btn btn-default pull-right csv_config_pos"><i class="icon-cogs"></i></a>
                        </div>
                        <section class="filter_list">
        					<ul class="list-unstyled sortable">
                                {foreach $order_fields_position as $orderfield}
                                    {if $orderfield.key !='productimages'}
                                       <li class="filter_list_item order_csv_field_{$orderfield.key|escape:'html':'UTF-8'} {if !isset($order_fields[$orderfield.key])} field_not_show {/if}" draggable="true">
                                            <div class="col-lg-8">
            									<h4>{$orderfield.name|escape:'html':'UTF-8'}</h4>
            								</div>
                                            <div class="col-lg-4">
												<span class="order_field_move btn btn-default pull-right"><i class="icon-move"></i></span>
											</div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </section>
                    </section>
             </div>
        </div>
         <div class="panel-footer" id="toolbar-footer">
			<button class="btn btn-default pull-right" id="submit-filter" name="SubmitCsvExportFields" type="submit"><i class="process-icon-save"></i> <span>{l s='Save' mod='g_ordermanager'}</span></button>
		</div>
    </form>
</div>