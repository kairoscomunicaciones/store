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
    <table style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 30%;">
                    <table  style="width: 100%; float: left;" cellpadding="1" cellspacing="0">
                        <tbody>
                            <tr><td>Invoice No:</td><td><strong class="strong_item"> {$invoice_number|escape:'htmlall':'UTF-8'}</strong></td></tr>
                            <tr><td>Carier:</td><td><strong class="strong_item"> {$order_carrier_name|escape:'htmlall':'UTF-8'}</strong></td></tr>
                            <tr><td>Invoice Date:</td><td><strong class="strong_item">{$invoice_date|escape:'htmlall':'UTF-8'} </strong></td></tr>
                            <tr><td>Total Due:</td><td><strong class="strong_item"> {displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</strong></td></tr>
                        </tbody>
                    </table>
                </td>
                 <td style="width: 40%;"></td>
                 <td style="width: 30%;">
                    <h4 class="invoice_title">INVOICE</h4>
                 </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 70%;">
                    <p>Invoice To: <strong class="strong_item color_text">{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}</strong></p>
                </td>
                <td style="width: 30%;"></td>
             </tr>
             <tr><td colspan="2"></td></tr>
             <tr>
                <td  style="width: 70%;">
                    <table style="width: 100%;" cellpadding="1" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width: 5%;"><strong class="strong_item">A</strong></td>
                                <td style="width: 95%;"><p>{$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                                <br />{$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}</p></td>
                            </tr>
                            <tr>
                                <td style="width: 5%;"><strong class="strong_item">E</strong></td>
                                <td style="width: 95%;">{$customeremail|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="width: 5%;"><strong class="strong_item">P</strong></td>
                                <td style="width: 95%;">{$delivery_phone|escape:'htmlall':'UTF-8'}<br/>{$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>   
                </td>
                <td  style="width: 30%;">
                    <table style="width: 100%;" cellpadding="1" cellspacing="0">
                        <tbody>
                            <tr><td><br /></td></tr>
                            <tr><td></td></tr>
                            <tr><td><strong class="strong_item">Total Due</strong></td></tr>
                        </tbody>
                    </table>  
                </td> 
        </tbody>
    </table>
    <table style="width: 100%; float: left;">
        <tbody>
            <tr class="border-bottom">
                <td style="width: 70%;"></td>
                <td style="width: 30%;">
                    <table style="width: 100%;" cellpadding="5" cellspacing="0">
                        <tbody>
                            <tr class="total_wp">
                                <td style="text-align: center;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
             </tr>
        </tbody>
    </table>
    <br /> {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="float: left; width: 60%;"></td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;" cellpadding="4" cellspacing="0">
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
            <tr>
                <td colspan="2"><strong class="strong_item total_text">Payment Method</strong> : <span class="color_text">{$payment|escape:'htmlall':'UTF-8'}</span></td>
            </tr>
            <tr class="border-top">
                <td style="float: left; width: 60%;"></td>
                <td style="float: right; width: 40%;" >
                    <table style="width: 100%;" cellpadding="4" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 60%;" class="strong_item total_text"><strong>Grand Total: </strong></td>
                                <td style="text-align: center; width: 40%;" class="strong_item total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%;"  cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr><td><strong class="strong_item">Terms & Conditions</strong></td></tr>
                            <tr>
                                <td>
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
                                <td>
                                    <p><strong class="strong_item">CEO & Founder</strong></p>
                                </td>
                            </tr>
                             <tr>
                                <td><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                            </tr>
                            <tr><td><p>Nguyen Van Nham</p></td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            
        </tbody>
    </table>
    <br />
    <table style="margin-bottom: 20px; width: 100%;"  cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td colspan="5" style="text-align: left;width:100%;">
                    <h4 class="thanksfor">Thank for your bussiness !</h4>
                </td>
            </tr>
            <tr>
                <td style="float:left;width: 20%;">
                    <table style="float:left;width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/phone.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
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
                </td>
                <td style="float:left;width: 20%;">
                    <table style="float:left;width: 100%;">
                        <tbody>
                            <tr>
                            
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/location.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 8pt;">210 Hoang Quoc Viet Street <br />Ha Noi, Viet Nam</td>
                                        </tr>
                                       
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </td>
                <td style="float:left;width: 20%;">
                    <table style="float:left;width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/email.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                        <tr>
                                                <td style="font-size: 7pt;">demo@demo.com</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 7pt;">www.demodemo.com</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </td>
                <td style="float:left;width: 10%;"></td>
                <td style="float:right;width: 30%;">{$logo|escape:'htmlall':'UTF-8'}</td>
            </tr>
        </tbody>
    </table>
</td></tr></table>