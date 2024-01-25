{*
* Do not edit the file if you want to upgrade in future.
*
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2021 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{capture name='tr_count'}{counter name='tr_count'}{/capture}
<tbody>
<tr class="order_quick_action_wp">
    <td class="row-selector text-center">
        <input type="checkbox" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'html':'UTF-8'}Box[]', $(this).is(':checked'));" />
    </td>
    <td colspan="{if $shop_link_type}{count($fields_display)+2|escape:'html':'UTF-8'}{else}{count($fields_display)+1|escape:'html':'UTF-8'}{/if}">
    <div class="row">
    	<div class="col-lg-8">
    		{if $bulk_actions && $has_bulk_actions}
    		<div class="btn-group bulk-actions">
    			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    				{l s='Bulk actions' mod='g_ordermanager'} <span class="caret"></span>
    			</button>
    			<ul class="dropdown-menu">
    			<li>
    				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'html':'UTF-8'}Box[]', true);return false;">
    					<i class="icon-check-sign"></i>&nbsp;{l s='Select all' mod='g_ordermanager'}
    				</a>
    			</li>
    			<li>
    				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'html':'UTF-8'}Box[]', false);return false;">
    					<i class="icon-check-empty"></i>&nbsp;{l s='Unselect all' mod='g_ordermanager'}
    				</a>
    			</li>
    			<li class="divider"></li>

                <li>
                    <a class="desc-order-print" href="submitPrintOrder">
                        <i class="icon-file-text"></i>&nbsp;{l s='Print Orders' mod='g_ordermanager'}
                    </a>
                </li>
                <li>
    				<a class="desc-order-invoice" href="submitExportOrderInvoice">
    					<i class="icon-file-text"></i>&nbsp;{l s='Generate Invoices' mod='g_ordermanager'}
    				</a>
    			</li>
                <li>
    				<a class="desc-order-delivery" href="submitExportOrderDelivery">
    					<i class="icon-truck"></i>&nbsp;{l s='Generate Delivery' mod='g_ordermanager'}
    				</a>
    			</li>
                <li>
    				<a class="desc-order-csv" href="submitExportOrderCsv">
    					<i class="icon-download"></i>&nbsp;{l s='Export CSV' mod='g_ordermanager'}
    				</a>
    			</li>
                <li class="divider"></li>
    			{foreach $bulk_actions as $key => $params}
    				<li {if $params.text == 'divider'} class="divider"{/if}>
    					{if $params.text != 'divider'}
    					<a href="#" onclick="{if isset($params.confirm)}if (confirm('{$params.confirm|escape:'html':'UTF-8'}')){/if}sendBulkAction($(this).closest('form').get(0), 'submitBulk{$key|escape:'html':'UTF-8'}{$table|escape:'html':'UTF-8'}');">
    						{if isset($params.icon)}<i class="{$params.icon|escape:'html':'UTF-8'}"></i>{/if}&nbsp;{$params.text|escape:'html':'UTF-8'}
    					</a>
    					{/if}
    				</li>
    			{/foreach}
    			</ul>
    		</div>
    		{/if}
    	</div>
    	{if !$simple_header && $list_total > $pagination[0]}
    	<div class="col-lg-4">
    		{* Choose number of results per page *}
    		<span class="pagination">
    			{l s='Display' mod='g_ordermanager'}:
    			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    				{$selected_pagination|escape:'html':'UTF-8'}
    				<i class="icon-caret-down"></i>
    			</button>
    			<ul class="dropdown-menu">
    			{foreach $pagination AS $value}
    				<li>
    					<a href="javascript:void(0);" class="pagination-items-page" data-items="{$value|intval}" data-list-id="{$list_id|escape:'html':'UTF-8'}">{$value|escape:'html':'UTF-8'}</a>
    				</li>
    			{/foreach}
    			</ul>
    			/ {$list_total|escape:'html':'UTF-8'} {l s='result(s)' mod='g_ordermanager'}
    			<input type="hidden" class="{$list_id|escape:'html':'UTF-8'}-pagination-items-page" name="{$list_id|escape:'html':'UTF-8'}_pagination" value="{$selected_pagination|intval}" />
    		</span>
    		<script type="text/javascript">
    			$('.pagination-items-page').on('click',function(e){
    				e.preventDefault();
    				$('.'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
    			});
    		</script>
    		<ul class="pagination pull-right">
    			<li {if $page <= 1}class="disabled"{/if}>
    				<a href="javascript:void(0);" class="pagination-link" data-page="1" data-list-id="{$list_id|escape:'html':'UTF-8'}">
    					<i class="icon-double-angle-left"></i>
    				</a>
    			</li>
    			<li {if $page <= 1}class="disabled"{/if}>
    				<a href="javascript:void(0);" class="pagination-link" data-page="{$page - 1|escape:'html':'UTF-8'}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
    					<i class="icon-angle-left"></i>
    				</a>
    			</li>
    			{assign p 0}
    			{while $p++ < $total_pages}
    				{if $p < $page-2}
    					<li class="disabled">
    						<a href="javascript:void(0);">&hellip;</a>
    					</li>
    					{assign p $page-3}
    				{else if $p > $page+2}
    					<li class="disabled">
    						<a href="javascript:void(0);">&hellip;</a>
    					</li>
    					{assign p $total_pages}
    				{else}
    					<li {if $p == $page}class="active"{/if}>
    						<a href="javascript:void(0);" class="pagination-link" data-page="{$p|escape:'html':'UTF-8'}" data-list-id="{$list_id|escape:'html':'UTF-8'}">{$p|escape:'html':'UTF-8'}</a>
    					</li>
    				{/if}
    			{/while}
    			<li {if $page >= $total_pages}class="disabled"{/if}>
    				<a href="javascript:void(0);" class="pagination-link" data-page="{$page + 1|escape:'html':'UTF-8'}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
    					<i class="icon-angle-right"></i>
    				</a>
    			</li>
    			<li {if $page >= $total_pages}class="disabled"{/if}>
    				<a href="javascript:void(0);" class="pagination-link" data-page="{$total_pages|escape:'html':'UTF-8'}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
    					<i class="icon-double-angle-right"></i>
    				</a>
    			</li>
    		</ul>
    		<script type="text/javascript">
    			$('.pagination-link').on('click',function(e){
    				e.preventDefault();

    				if (!$(this).parent().hasClass('disabled'))
    					$('#submitFilter'+$(this).data("list-id")).val($(this).data("page")).closest("form").submit();
    			});
    		</script>
    	</div>
    	{/if}
    </div>
    </td>
</tr>
{if count($list)}
{foreach $list AS $index => $tr}
	<tr {if $position_identifier} id="tr_{$position_group_identifier|escape:'html':'UTF-8'}_{$tr.$identifier|escape:'html':'UTF-8'}_{if isset($tr.position['position'])}{$tr.position['position']|escape:'html':'UTF-8'}{else}0{/if}"{/if} class="{if isset($tr.class)}{$tr.class|escape:'html':'UTF-8'}{/if} {if $tr@iteration is odd by 1}odd{/if} {if $list_id == 'order'} tr_order_{$tr.$identifier|escape:'html':'UTF-8'} {/if} "{if isset($tr.color) && $color_on_bg} style="background-color: {$tr.color|escape:'html':'UTF-8'}"{/if} >
		{if $bulk_actions && $has_bulk_actions}
			<td class="row-selector text-center {if $orderactioninfisrtcolumn}{if $has_actions} orderactioninfisrtcolumn {/if}{/if}">
				{if isset($list_skip_actions.delete)}
					{if !in_array($tr.$identifier, $list_skip_actions.delete)}
						<input type="checkbox" name="{$list_id|escape:'html':'UTF-8'}Box[]" value="{$tr.$identifier|escape:'html':'UTF-8'}"{if isset($checked_boxes) && is_array($checked_boxes) && in_array({$tr.$identifier|escape:'html':'UTF-8'}, $checked_boxes)} checked="checked"{/if} class="noborder" />
					{/if}
				{else}
					<input type="checkbox" name="{$list_id|escape:'html':'UTF-8'}Box[]" value="{$tr.$identifier|escape:'html':'UTF-8'}"{if isset($checked_boxes) && is_array($checked_boxes) && in_array({$tr.$identifier|escape:'html':'UTF-8'}, $checked_boxes)} checked="checked"{/if} class="noborder" />
				{/if}
                {if $orderactioninfisrtcolumn}
                	{if $has_actions}
                			{assign var='compiled_actions' value=array()}
                			{foreach $actions AS $key => $action}
                				{if isset($tr.$action)}
                					{if $key == 0}
                						{assign var='action' value=$action}
                					{/if}
                					{if $action == 'delete' && $actions|@count > 2}
                						{$compiled_actions[] = 'divider'}
                					{/if}
                					{$compiled_actions[] = $tr.$action}
                				{/if}
                			{/foreach}
                			{if $compiled_actions|count > 0}
                				{if $compiled_actions|count > 1}<div class="btn-group-action ordermanager_list_action">{/if}
                				<div class="btn-group pull-right gquickview_btn">
                					{$compiled_actions[0] nofilter}{* html content, no need to escape *}
                					{if $compiled_actions|count > 1}
                					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                						<i class="icon-caret-down"></i>&nbsp;
                					</button>
                						<ul class="dropdown-menu">
                						{foreach $compiled_actions AS $key => $action}
                							{if $key != 0}
                							<li {if $action == 'divider' && $compiled_actions|count > 3} class="divider"{/if}>
                								{if $action != 'divider'}{$action nofilter}{/if}{* html content, no need to escape *}
                							</li>
                							{/if}
                						{/foreach}
                						</ul>
                					{/if}
                				</div>
                				{if $compiled_actions|count > 1}</div>{/if}
                			{/if}
                	{/if}
                    {/if}
			</td>
		{/if}
		{foreach $fields_display AS $key => $params}
			{block name="open_td"}
				<td
					{if isset($params.position)}
						id="td_{if !empty($position_group_identifier)}{$position_group_identifier|escape:'html':'UTF-8'}{else}0{/if}_{$tr.$identifier|escape:'html':'UTF-8'}{if $smarty.capture.tr_count > 1}_{($smarty.capture.tr_count - 1)|intval}{/if}"
					{/if}
					class="{strip}{if !$no_link}pointer{/if}
					{if isset($params.position) && $order_by == 'position'  && $order_way != 'DESC'} dragHandle{/if}
					{if isset($params.class)} {$params.class|escape:'html':'UTF-8'}{/if}
					{if isset($params.align)} {$params.align|escape:'html':'UTF-8'}{/if}{/strip}"
					{if (!isset($params.position) && !$no_link && !isset($params.remove_onclick))}
						onclick="document.location = '{$current_index|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$tr.$identifier|escape:'html':'UTF-8'}&amp;view{$table|escape:'html':'UTF-8'}{if $page > 1}&amp;page={$page|intval}{/if}&amp;token={$token|escape:'html':'UTF-8'}'">
					{else}
					>
				{/if}
			{/block}
			{block name="td_content"}
				{if isset($params.prefix)}{$params.prefix|escape:'html':'UTF-8'}{/if}
				{if isset($params.badge_success) && $params.badge_success && isset($tr.badge_success) && $tr.badge_success == $params.badge_success}<span class="badge badge-success">{/if}
				{if isset($params.badge_warning) && $params.badge_warning && isset($tr.badge_warning) && $tr.badge_warning == $params.badge_warning}<span class="badge badge-warning">{/if}
				{if isset($params.badge_danger) && $params.badge_danger && isset($tr.badge_danger) && $tr.badge_danger == $params.badge_danger}<span class="badge badge-danger">{/if}
				{if isset($params.color) && isset($tr[$params.color])}
					<span class="label color_field" style="background-color:{$tr[$params.color]|escape:'html':'UTF-8'};color:{if Tools::getBrightness($tr[$params.color]) < 128}white{else}#383838{/if}">
				{/if}
				{if isset($tr.$key)}
					{if isset($params.active)}
						{$tr.$key|escape:'html':'UTF-8'}
					{elseif isset($params.activeVisu)}
						{if $tr.$key}
							<i class="icon-check-ok"></i> {l s='Enabled' mod='g_ordermanager'}
						{else}
							<i class="icon-remove"></i> {l s='Disabled' mod='g_ordermanager'}
						{/if}
					{elseif isset($params.position)}
						{if !$filters_has_value && $order_by == 'position' && $order_way != 'DESC'}
							<div class="dragGroup">
								<div class="positions">
									{$tr.$key.position + 1|escape:'html':'UTF-8'}
								</div>
							</div>
						{else}
							{$tr.$key.position + 1|escape:'html':'UTF-8'}
						{/if}
					{elseif isset($params.image)}
						{$tr.$key|escape:'html':'UTF-8'}
					{elseif isset($params.icon)}
						{if is_array($tr[$key])}
							{if isset($tr[$key]['class'])}
								<i class="{$tr[$key]['class']|escape:'html':'UTF-8'}"></i>
							{else}
								<img src="../img/admin/{$tr[$key]['src']|escape:'html':'UTF-8'}" alt="{$tr[$key]['alt']|escape:'html':'UTF-8'}" title="{$tr[$key]['alt']|escape:'html':'UTF-8'}" />
							{/if}
						{/if}
					{elseif isset($params.type) && $params.type == 'price'}
					{*	{displayPrice price=$tr.$key} *}{$tr.$key nofilter}{* html content, no need to escape *}
					{elseif isset($params.float)}
						{$tr.$key|escape:'html':'UTF-8'}
					{elseif isset($params.type) && $params.type == 'date'}
						{dateFormat date=$tr.$key full=0}
					{elseif isset($params.type) && $params.type == 'datetime'}
						{dateFormat date=$tr.$key full=1}
					{elseif isset($params.type) && $params.type == 'decimal'}
						{$tr.$key|string_format:"%.2f"|escape:'html':'UTF-8'}
					{elseif isset($params.type) && $params.type == 'percent'}
						{$tr.$key|escape:'html':'UTF-8'} {l s='%' mod='g_ordermanager'}
					{* If type is 'editable', an input is created *}
					{elseif isset($params.type) && $params.type == 'editable' && isset($tr.id)}
						<input type="text" name="{$key|escape:'html':'UTF-8'}_{$tr.id|escape:'html':'UTF-8'}" value="{$tr.$key|escape:'html':'UTF-8'}" class="{$key|escape:'html':'UTF-8'}" />
					{elseif isset($params.callback)}
						{if isset($params.maxlength) && Tools::strlen($tr.$key) > $params.maxlength}
							<span title="{$tr.$key|escape:'html':'UTF-8'}">{$tr.$key|truncate:$params.maxlength:'...'}</span>
						{else}
							{$tr.$key nofilter}{* html content, no need to escape *}
						{/if}
					{elseif $key == 'color'}
						{if !is_array($tr.$key)}
						<div style="background-color: {$tr.$key|escape:'html':'UTF-8'};" class="attributes-color-container"></div>
						{else} {*TEXTURE*}
						<img src="{$tr.$key.texture|escape:'html':'UTF-8'}" alt="{$tr.name|escape:'html':'UTF-8'}" class="attributes-color-container" />
						{/if}
					{elseif isset($params.maxlength) && Tools::strlen($tr.$key) > $params.maxlength}
						<span title="{$tr.$key|escape:'html':'UTF-8'}">{$tr.$key|truncate:$params.maxlength:'...'|escape:'html':'UTF-8'}</span>
					{else}
						{$tr.$key|escape:'html':'UTF-8'}
					{/if}
				{else}
					{block name="default_field"}--{/block}
				{/if}
				{if isset($params.suffix)}{$params.suffix|escape:'html':'UTF-8'}{/if}
				{if isset($params.color) && isset($tr.color)}
					</span>
				{/if}
				{if isset($params.badge_danger) && $params.badge_danger && isset($tr.badge_danger) && $tr.badge_danger == $params.badge_danger}</span>{/if}
				{if isset($params.badge_warning) && $params.badge_warning && isset($tr.badge_warning) && $tr.badge_warning == $params.badge_warning}</span>{/if}
				{if isset($params.badge_success) && $params.badge_success && isset($tr.badge_success) && $tr.badge_success == $params.badge_success}</span>{/if}
			{/block}
			{block name="close_td"}
				</td>
			{/block}
		{/foreach}

	{if isset($shop_link_type) && $shop_link_type}
		<td title="{$tr.shop_name|escape:'html':'UTF-8'}">
			{if isset($tr.shop_short_name)}
				{$tr.shop_short_name|escape:'html':'UTF-8'}
			{else}
				{$tr.shop_name|escape:'html':'UTF-8'}
			{/if}
		</td>
	{/if}
	{if isset($has_actions) &&  $has_actions}
		<td class="text-right">
			{assign var='compiled_actions' value=array()}
			{foreach $actions AS $key => $action}
				{if isset($tr.$action)}
					{if $key == 0}
						{assign var='action' value=$action}
					{/if}
					{if $action == 'delete' && $actions|@count > 2}
						{$compiled_actions[] = 'divider'}
					{/if}
					{$compiled_actions[] = $tr.$action}
				{/if}
			{/foreach}
			{if $compiled_actions|count > 0}
				{if $compiled_actions|count > 1}<div class="btn-group-action ordermanager_list_action">{/if}
				<div class="btn-group pull-right gquickview_btn">
					{$compiled_actions[0] nofilter}{* html content, no need to escape *}
					{if $compiled_actions|count > 1}
					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="icon-caret-down"></i>&nbsp;
					</button>
						<ul class="dropdown-menu">
						{foreach $compiled_actions AS $key => $action}
							{if $key != 0}
							<li {if $action == 'divider' && $compiled_actions|count > 3} class="divider"{/if}>
								{if $action != 'divider'}{$action nofilter}{/if}{* html content, no need to escape *}
							</li>
							{/if}
						{/foreach}
						</ul>
					{/if}
				</div>
				{if $compiled_actions|count > 1}</div>{/if}
			{/if}
		</td>
	{/if}
	</tr>
    {if $list_id == 'order'}
        <tr class="tr_custom_data">
            {assign var='allcolspan' value=count($fields_display)+1}
            {if $shop_link_type}
                {assign var='allcolspan' value=$allcolspan+1}
            {/if}
            {if $has_actions}
                {assign var='allcolspan' value=$allcolspan+1}
            {/if}
            <td  colspan="{$allcolspan|escape:'html':'UTF-8'}" >
                <div class="order_extra_box " data-idorder="{$tr.$identifier|escape:'html':'UTF-8'}" id="order_extra_box_{$tr.$identifier|escape:'html':'UTF-8'}">
					<div class="box_setting_showin_top box_extra_showinclose"></div>
					<div class="order_extra_block_wp">
						<div class="showin-heading-box">{l s='Order' mod='g_ordermanager'}</div>
						<div class="order_extra_block"><span class="lds-dual-ring-w"><span class="lds-dual-ring"></span></span></div>
						<div class="gpopupp_footer">
							<button type="button" class="btn btn-primary box_extra_showinclose">{l s='Close' mod='g_ordermanager'}</button>
						</div>
					</div>
					
				</div>
            </td>
        </tr>
    {/if}
{/foreach}
{else}
	<tr>
		<td class="list-empty" colspan="{count($fields_display)+1|escape:'html':'UTF-8'}">
			<div class="list-empty-msg">
				<i class="icon-warning-sign list-empty-icon"></i>
				{l s='No records found' mod='g_ordermanager'}
			</div>
		</td>
	</tr>
{/if}
</tbody>
