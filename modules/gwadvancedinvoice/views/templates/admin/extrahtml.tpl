{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

{if isset($form_import_template) && $form_import_template}
    <div class="gimport_template">
        <div class="row">
            <div class="col-lg-6" >
                <div class="panel">
                    <div class="panel-heading"><i class="icon-upload"></i> {l s='Import Template' mod='gwadvancedinvoice'}</div>
                    <form action="" method="POST" name="gimport_template" enctype="multipart/form-data" >
                        <input type="hidden" name="submitGimport_template" value="1"/>
                        <div class="form-group">
                            <label class="control-label col-lg-3">{l s='File' mod='gwadvancedinvoice'}</label>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input id="file" type="file" name="zipfile" class="hide" />
                                        <div class="dummyfile input-group">
                                            <span class="input-group-addon"><i class="icon-file"></i></span>
                                            <input id="file-name" type="text" name="zipfile" readonly="" />
                                            <span class="input-group-btn">
                                                <button id="file-selectbutton" type="button" name="submitAddGTemplate" class="btn btn-default">
                                                    <i class="icon-folder-open"></i> {l s='Add file' mod='gwadvancedinvoice'}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">                
                            <label class="control-label col-lg-3">{l s='Delete old template' mod='gwadvancedinvoice'}</label>
                            <div class="col-lg-9">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="delete_old_template" id="delete_old_template_on" value="1"  />
                                    <label for="delete_old_template_on">{l s='Yes' mod='gwadvancedinvoice'}</label>
                                    <input type="radio" name="delete_old_template" id="delete_old_template_off" value="0" checked="checked" />
                                    <label for="delete_old_template_off">{l s='No' mod='gwadvancedinvoice'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">                
                            <label class="control-label col-lg-3">{l s='Override old template' mod='gwadvancedinvoice'}</label>
                            <div class="col-lg-9">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="override_old_template" id="override_old_template_on" value="1"  />
                                    <label for="override_old_template_on">{l s='Yes' mod='gwadvancedinvoice'}</label>
                                    <input type="radio" name="override_old_template" id="override_old_template_off" value="0" checked="checked" />
                                    <label for="override_old_template_off">{l s='No' mod='gwadvancedinvoice'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        {literal}
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $('#file-selectbutton').click(function(e) {
                                    $('#file').trigger('click');
                                });
                                $('#file-name').click(function(e) {
                                    $('#file').trigger('click');
                                });
                                $('#file-name').on('dragenter', function(e) {
                                    e.stopPropagation();
                                    e.preventDefault();
                                });
                                $('#file-name').on('dragover', function(e) {
                                    e.stopPropagation();
                                    e.preventDefault();
                                });
                                $('#file-name').on('drop', function(e) {
                                    e.preventDefault();
                                    var files = e.originalEvent.dataTransfer.files;
                                    $('#file')[0].files = files;
                                    $(this).val(files[0].name);
                                });
                                $('#file').change(function(e) {
                                    if ($(this)[0].files !== undefined)
                                    {
                                        var files = $(this)[0].files;
                                        var name  = '';
                        
                                        $.each(files, function(index, value) {
                                            name += value.name+', ';
                                        });
                        
                                        $('#file-name').val(name.slice(0, -2));
                                    }
                                    else // Internet Explorer 9 Compatibility
                                    {
                                        var name = $(this).val().split(/[\\/]/);
                                        $('#file-name').val(name[name.length-1]);
                                    }
                                });
                            });
                        </script>
                        {/literal}
                        <div style="clear:both;"></div>
                        <div class="panel-footer">
                            <button type="submit" value="1" id="gwadvancedinvoice_form_import" class="btn btn-default pull-right">
                                <i class="process-icon-upload"></i> {l s='Import' mod='gwadvancedinvoice'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {if isset($template) && $template > 0}
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading"><i class="icon-download"></i> {l s='Export Template' mod='gwadvancedinvoice'}</div>
                        <form action="" method="POST" name="gexport_template">
                            <div class="gbox_content">
                                <input type="hidden" name="submitGexport_template" value="1"/>
                                <input name="gid_templates" type="hidden" value="" id="gid_templates" autocomplete="off" />
                                {if $template > 1}
                                    <div class="export_warrning alert alert-info" role="alert">
                                        <p class="alert-text">{l s='You must select at least one element to export.' mod='gwadvancedinvoice'}</p>
                                    </div>
                                {/if}
                                <div style="clear:both;"></div>
                                <div class="panel-footer">
                                    <button type="submit" value="1" id="gwadvancedinvoice_form_export" class="btn btn-default pull-right">
                                        <i class="process-icon-download"></i> {l s='Export' mod='gwadvancedinvoice'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            {/if}
        </div>
    </div>
{elseif isset($exportTemplateToXml) && $exportTemplateToXml}
{literal}
<?xml version="1.0" encoding="UTF-8"?>
    <gtemplates>{/literal}
    {if isset($gtemplates) && $gtemplates}
        {foreach $gtemplates as $key=>$gtemplate}
        <gtemplate id="{$key|intval}">
            {foreach $fields as $_key=>$field}
                {if isset($gtemplate[$_key]) && isset($field.lang) && $field.lang == 1}
                    {foreach $gtemplate[$_key] as $idlang=>$data_lang}
                        {if isset($langs_iso[$idlang])}
                            {if isset($field.validate) && $field.validate == 'isCleanHtml'}
                                <{$_key|escape:'html':'UTF-8'} lang="{$langs_iso[$idlang]|escape:'html':'UTF-8'}">{if $data_lang !=''}<![CDATA[{$data_lang nofilter}]]>{else}{/if}</{$_key|escape:'html':'UTF-8'}>{* Html content. No need escape.*}
                            {else}
                                <{$_key|escape:'html':'UTF-8'}  lang="{$langs_iso[$idlang]|escape:'html':'UTF-8'}">{if $data_lang !=''}<![CDATA[{$data_lang|escape:'html':'UTF-8'}]]>{else}{/if}</{$_key|escape:'html':'UTF-8'}>
                            {/if}
                        {/if}
                    {/foreach}
                {else}
                    {if isset($field.validate) && $field.validate == 'isCleanHtml'}
                        <{$_key|escape:'html':'UTF-8'}>{if isset($gtemplate[$_key]) && $gtemplate[$_key] !=''}<![CDATA[{$gtemplate[$_key] nofilter}]]>{else}{/if}</{$_key|escape:'html':'UTF-8'}>{* Html content. No need escape.*}
                    {else}
                        <{$_key|escape:'html':'UTF-8'}>{if isset($gtemplate[$_key]) && $gtemplate[$_key] !=''}{$gtemplate[$_key]|escape:'html':'UTF-8'}{else}{/if}</{$_key|escape:'html':'UTF-8'}>
                    {/if}
                {/if}
            {/foreach}
        </gtemplate>
        {/foreach}
    {/if}{literal}
    </gtemplates>
{/literal}
{else}
<a href="../modules/gwadvancedinvoice/views/img/imgtemplates/{$val|escape:'htmlall':'UTF-8'}.png" class="popupimage"><img class="thumbnail" style="max-width:75px;" src="../modules/gwadvancedinvoice/views/img/imgtemplates/{$val|escape:'htmlall':'UTF-8'}.png" alt="" /></a>
{/if}