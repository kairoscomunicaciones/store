<?php
/**
* This file to install template
* 
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link      http://www.globosoftware.net
*/

class Temp20{
    public static function getTemplate(){
        $template =  array(
            'id'=>'temp20',
            'name'=>'Template base 20',
            'thumbnail'=>'views/img/imgtemplates/temp20.png',
            'large_thumbnail'=>'views/img/imgtemplates/temp20.png',
            'pagesize'=>array('A4','A5','usletter'),
            'activefooter'=>'1',
            'activeheader'=>'0',
            'mgheader'=>'5',
            'mgfooter'=>'10',
            'mgcontent'=>'5-5-5-10',
            
            'barcodetype'=>'C128',
            'pageorientation'=>'P',
            'barcodeformat'=>'INVOICE {$invoice_number}',
            
            'template_config'=>array(
                                array(
                                    'title'=>'Color Text Default',
                                    'type'=>'color',
                                    'name'=>'bodytextcolor',
                                    'value'=>'#64615e'
                                ),
                                array(
                                    'title'=>'Heading Text Color',
                                    'type'=>'color',
                                    'name'=>'headingtextcolor',
                                    'value'=>'#ffffff'
                                ),
                                array(
                                    'title'=>'Block Title Color',
                                    'type'=>'color',
                                    'name'=>'color_title',
                                    'value'=>'#4db42e'
                                ),
                                array(
                                    'title'=>'Strong Color',
                                    'type'=>'color',
                                    'name'=>'color_strong',
                                    'value'=>'#474241'
                                ),
                                array(
                                    'title'=>'Heading Block Background',
                                    'type'=>'color',
                                    'name'=>'headingblockbackground',
                                    'value'=>'#4db42e'
                                ),
                                array(
                                    'title'=>'Color Text product',
                                    'type'=>'color',
                                    'name'=>'color_product',
                                    'value'=>'#474241'
                                ),
                                array(
                                    'title'=>'Background line even',
                                    'type'=>'color',
                                    'name'=>'color_line_even',
                                    'value'=>'#f9f9f9'
                                ),
                                array(
                                    'title'=>'Background line odd',
                                    'type'=>'color',
                                    'name'=>'color_line_odd',
                                    'value'=>'#fafafa'
                                ),
                                array(
                                    'title'=>'Color border',
                                    'type'=>'color',
                                    'name'=>'color_border',
                                    'value'=>'#ffffff'
                                ),
                                array(
                                    'title'=>'Invoice Title Color',
                                    'type'=>'color',
                                    'name'=>'color_invoice',
                                    'value'=>'#4db42e'
                                ),
                                array(
                                    'title'=>'Thanks for ... color',
                                    'type'=>'color',
                                    'name'=>'color_thank_for',
                                    'value'=>'#4db42e'
                                ),
                                array(
                                    'title'=>'Font Text Default',
                                    'type'=>'font',
                                    'name'=>'bodytextfont',
                                    'value'=>'robotolight'
                                ),
                                array(
                                    'title'=>'Font Heading Text',
                                    'type'=>'font',
                                    'name'=>'font_title',
                                    'value'=>'robotomedium'
                                ),
                                array(
                                    'title'=>'Invoice Title Font',
                                    'type'=>'font',
                                    'name'=>'font_invoice',
                                    'value'=>'robotolight'
                                ),
                                array(
                                    'title'=>'Thanks for ... font',
                                    'type'=>'font',
                                    'name'=>'font_thank_for',
                                    'value'=>'robotolight'
                                ),
                                array(
                                    'title'=>'Font Size Default',
                                    'type'=>'text',
                                    'name'=>'font_size_text',
                                    'value'=>'9pt'
                                ),
                                array(
                                    'title'=>'Font size product',
                                    'type'=>'text',
                                    'name'=>'font_size_product',
                                    'value'=>'9pt'
                                ),
                                array(
                                    'title'=>'Invoice Title Size',
                                    'type'=>'text',
                                    'name'=>'size_invoice',
                                    'value'=>'30pt'
                                ),
                                array(
                                    'title'=>'Thanks for ...',
                                    'type'=>'text',
                                    'name'=>'size_thank_for',
                                    'value'=>'20pt'
                                ),
                            ),
            'productcolumns'=> array(
                            'widthtitle' => array(
                                    '0' => '55',
                                    '1' => '12',
                                    '2' => '15',
                                    '3' => '8',
                                    '4' => '10',
                                ),
                            'title' => array
                                (
                                    '0' => 'Product',
                                    '1' => 'Barcode',
                                    '2' => 'Unit Price',
                                    '3' => 'Qty',
                                    '4' => 'Total Price',
                                ),
                            'content' => array
                                (
                                    '0' => '{$order_detail.product_name}{$order_detail.description_short}',
                                    '1' => '{$order_detail.barcode}',
                                    '2' => '{displayPrice:$order_detail.unit_price_tax_excl_including_ecotax}',
                                    '3' => '{$order_detail.product_quantity}',
                                    '4' => '{displayPrice:$order_detail.total_price_tax_excl_including_ecotax}'
                                ),
                            'align' => array(
                                    '0' => 'left',
                                    '1' => 'center',
                                    '2' => 'center',
                                    '3' => 'center',
                                    '4' => 'center',
                            )
                        ),
        );
        return $template;
    }
}