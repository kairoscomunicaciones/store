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
                <td style="float: left; width: 40%;">{$logo|escape:'htmlall':'UTF-8'}</td>
                <td style="float: left; width: 10%;" ></td>
                <td style="float: right; width: 50%;">
                    <table style=" width: 100%"  cellpadding="1" cellspacing="3">
                        <tbody>
                            <tr>
                                <td  style=" width: 20%;text-align:right;" class="add_label"><strong>P</strong></td>
                                <td  style=" width: 80%;">+84 1234567890  +84 9876543212</td>
                            </tr>
                            <tr>
                                <td  style=" width: 20%;text-align:right;" class="add_label"><strong>E</strong></td>
                                <td  style=" width: 80%;">demo@demo.com</td>
                            </tr>
                            <tr>
                                <td  style=" width: 20%;text-align:right;" class="add_label"><strong>W</strong></td>
                                <td  style=" width: 80%;">www.demodemo.com</td>
                            </tr>
                            <tr>
                                <td  style=" width: 20%;text-align:right;" class="add_label"><strong>A</strong></td>
                                <td  style=" width: 80%;">210 Hoang Quoc Viet Street Ha Noi, Viet Nam</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    
    <table style="width: 100%; float: left;">
        <tbody>
            <tr>
                <td style="width: 45%; ">
                    <table style="width: 100%;float:left;" cellpadding="5" cellspacing="0"><tbody><tr><td class="header_title total_wp" style="text-align:center;">
                        INVOICE
                    </td></tr></tbody></table>
                    
                    <table  cellpadding="2" cellspacing="0"  style="width: 100%;float:left;"><tbody>
                        <tr><td style="width: 50%;text-align:right;"><strong class="strong_item">Invoice No: </strong></td><td>{$invoice_number|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%;text-align:right;"><strong class="strong_item">Carier: </strong></td>
                            <td>{$order_carrier_name|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%;text-align:right;"><strong class="strong_item">Invoice Date: </strong></td>
                            <td>{$invoice_date|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%;text-align:right;"><strong class="strong_item">Total Due: </strong></td>
                            <td>{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                    </tbody></table>
                    
                </td>
                <td style="width: 10%; "></td>
                <td style="width: 45%; ">
                    <table   style="width: 100%;float:left;" cellpadding="5" cellspacing="0"><tbody><tr><td class="header_title total_wp" style="text-align:center;">
                        INVOICE TO
                    </td></tr></tbody></table>
                    <table   cellpadding="2" cellspacing="0"   style="width: 100%;float:left;"><tbody>
                        <tr><td style="text-align:center;">{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}</td></tr>
                        <tr><td style="text-align:center;">{$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}</td></tr>
                        <tr><td style="text-align:center;">{$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}</td></tr>
                        <tr><td style="text-align:center;">{$delivery_phone|escape:'htmlall':'UTF-8'}</td></tr>
                        <tr><td style="text-align:center;">{$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</td></tr>
                    </tbody></table>
                    
                
                </td>
            </tr>
        </tbody>
    
    </table>
   
    <br /> {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;" >
        <tbody>
            <tr>
                <td colspan ="3"></td>
            </tr>
            <tr>
                <td colspan ="3"></td>
            </tr>
            <tr>
                <td style="width: 45%;" >
                   
                    <table  style="width: 100%;float:left;" cellpadding="5" cellspacing="0"><tbody><tr><td class="header_title total_wp" style="text-align:center;">
                        Payment Method
                    </td></tr></tbody></table>
                    <table  style="width: 100%;" >
                        <tbody>
                            <tr>
                                <td style="text-align:center;">{$payment|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td>
                                    <br />
                                    <p><strong class="strong_item">Terms & Conditions</strong></p>
                                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 10%;" ></td>
                <td style="width: 45%;" >
                    <table  style="width: 100%;float:left;" cellpadding="5" cellspacing="0"><tbody><tr><td class="header_title total_wp" style="text-align:center;">
                        Amount Due
                    </td></tr></tbody></table>
                    <table style="width: 100%;">
                        <tbody>
                            <tr><td colspan="2"></td></tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$total_products|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr><td colspan ="2"></td></tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
                                <td style="text-align: right; width: 40%;">-{displayPrice:$total_discounts_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr><td colspan ="2"></td></tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$total_shipping_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr><td colspan ="2"></td></tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$footer.total_taxes|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr><td colspan ="2"></td></tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"  class="total_text" ><strong >Total: </strong></td>
                                <td style="text-align: right; width: 40%;" class="total_text" >{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
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
            <tr><td colspan="3"></td></tr>
            <tr><td colspan="3"></td></tr>
            <tr>
                <td style="width: 50%;">
                </td>
                <td style="width: 20%;"></td>
                <td style="text-align: right; width: 30%;">
                    <table style="width: 100%; margin-left: auto; margin-right: auto;">
                        <tbody>
                            
                            <tr>
                                <td>
                                    <p>Nguyen Van Nham</p>
                                    <p><strong class="strong_item">CEO & Founder</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="3">
                    <br />
                    <br />
                    <h4 class="thanksfor">Thank for your bussiness !</h4>
                </td>
            </tr>
        </tbody>
    </table>
</td></tr></table>