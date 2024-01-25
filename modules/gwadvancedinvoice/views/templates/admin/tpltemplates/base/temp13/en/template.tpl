{*
* Do not edit the file if you want to upgrade in future.
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2016 Globo ., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}
<table id="wrap" style="width: 100%; float: left;" cellpadding="0" cellspacing="0"><tr><td>
    <table style="margin-bottom: 20px; width: 100%; float: left;">
        <tbody>
            <tr>
                <td style="float;left; width: 40%;" class="bg-invoice">
                    <table style="width: 100%;" cellpadding="15" cellspacing="0">
                        <tbody>
                    <tr>
                        <td>
                          <h4 class="invoice_title">INVOICE</h4>
                        </td>
                    </tr>
                    </tbody></table>
                </td>
                <td style="float;left;width: 30%;"></td>
                <td style="float;left;width: 30%;">{$logo|escape:'htmlall':'UTF-8'}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%; float: left;"  cellpadding="15" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 33%;">
                    <table style="width: 100%; float: left;"><tbody><tr><td class="border-bottom "><h4 class="header_title">BILLING ADDRESS</h4>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                    <br />{$billing_firstname|escape:'htmlall':'UTF-8'} {$billing_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_address1|escape:'htmlall':'UTF-8'} {$billing_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_city|escape:'htmlall':'UTF-8'} - {$billing_postcode|escape:'htmlall':'UTF-8'} {$billing_state|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone_mobile|escape:'htmlall':'UTF-8'}
                </td>
                <td style="width: 33%;">
                    <table style="width: 100%; float: left;"><tbody><tr><td class="border-bottom "><h4 class="header_title">INVOICE DETAILS</h4></td>
                    </tr>
                    </tbody></table>
                 
                     <br /><strong class="strong_item">Invoice Date: </strong>{$invoice_date|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Invoice No: </strong>{$invoice_number|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Carier: </strong>{$order_carrier_name|escape:'htmlall':'UTF-8'}
                    <br />
                    <br />
                    <table cellpadding="10" cellspacing="0"><tbody><tr><td class="total_wp total_text"  style="text-align: center;">
                        {displayPrice:$total_paid|escape:'htmlall':'UTF-8'}
                    </td></tr></tbody></table>
                    
                    </td>
                <td style="width: 34%;">
                    
                    <h4 class="header_title">210 Hoang Quoc Viet Street
                        <br />Ha Noi, Viet Nam</h4>
                        <br />
                        <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 12%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/phone.png" alt="" width="25" height="25" /></td>
                                <td style="width: 88%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 8pt;">+ 84 1659 005 710</td>
                                        </tr>
                                        <tr>
                                                <td style="font-size: 8pt;">+ 84 1655 068 980</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                       
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 12%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/email.png" alt="" width="25" height="25" /></td>
                                    <td style="width: 88%;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td></td></tr>
                                                <tr>
                                                    <td style="font-size: 8pt;">demo@demo.com</td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                       
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 12%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/location.png" alt="" width="25" height="25" /></td>
                                    <td style="width: 88%;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td></td></tr>
                                                <tr>
                                                    <td style="font-size: 8pt;">www.demodemo.com</td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                       </td>     
                </tr>        
        </tbody>
    </table>
    {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="float: left; width: 60%;">
                    <table style="float: left; width: 70%;"  cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                <table cellpadding="7" cellspacing="0" class="border-bottom" style="float: left; width: 100%;"><tbody>
                                <tr><td>
                                <strong class="strong_item thanksfor" style="font-size:10pt">Payment Method</strong> : {$payment|escape:'htmlall':'UTF-8'}
                                </td></tr>
                                </tbody></table>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;"  cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$total_products|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
                                <td style="text-align: center; width: 40%;">-{displayPrice:$total_discounts_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$total_shipping_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$footer.total_taxes|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr >
                <td style="float: left; width: 60%;"></td>
                <td style="float: right; width: 40%;" class="total_wp">
                    <table style="width: 100%;"  cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr >
                                <td style="text-align: right; width: 60%; font-size:12pt;" class="total_text"><strong>Total: </strong></td>
                                <td style="text-align: center; width: 40%; font-size:12pt;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;"  cellpadding="15" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                    <strong class="thanksfor" style="font-size:15pt;">Thank for your bussiness !</strong>
                    <p><strong class="strong_item">Terms & Conditions</strong></p>
                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: center; width: 10%;"></td>
                <td style="text-align: center; width: 40%;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width:15%;"></td>
                                <td style="width:70%;text-align: center;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                                <td style="width:15%;"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">
                                    <p>Nguyen Van Nham</p>
                                    <p><strong class="strong_item">CEO & Founder</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>            
        </tbody>
    </table>
</td></tr></table>