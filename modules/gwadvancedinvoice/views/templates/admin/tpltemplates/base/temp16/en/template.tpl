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
                <td style="width: 40%;">{$logo|escape:'htmlall':'UTF-8'}</td>
                <td style="width: 35%;"></td>
                <td style="width: 25%;">
                    <h4 class="invoice_title">INVOICE</h4>
               </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%; float: left;">
        <tbody>
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 25%;">
                    <h4 class="header_title">INVOICE TO</h4>
                    <p>{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                </td>
                <td style="width: 25%;">
                    <h4 class="header_title">INVOICE DETAILS</h4>
                    <table cellpadding="1" cellspacing="0"  style="width: 100%; float: left;">
                        <tbody>
                            <tr>
                                <td style="width:50%;"><strong class="strong_item">Invoice No: </strong></td>
                                <td style="width:50%;">{$invoice_number|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="width:50%;"><strong class="strong_item">Carier: </strong></td>
                                <td style="width:50%;">{$order_carrier_name|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="width:50%;"><strong class="strong_item">Invoice Date: </strong></td>
                                <td style="width:50%;">{$invoice_date|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="width:50%;"><strong class="strong_item">Total Due: </strong></td>
                                <td style="width:50%;">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'} </td>
                            </tr>
                        </tbody>
                    </table>   
                </td>
            </tr>
        </tbody>
    </table>
    <br /> {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;" cellpadding="7" cellspacing="0"  >
        <tbody>
            <tr>
                <td style="float: right; width: 60%;"></td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;" cellpadding="3" cellspacing="0">
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
                <td style="float: right; width: 60%;"></td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;" cellpadding="6" cellspacing="0">
                        <tbody>
                            <tr class="total_wp">
                                <td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                <td style="text-align: center; width: 40%;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%;float:left;">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;float:left;">
                        <tbody>
                            <tr>
                                <td><strong class="strong_item">Payment Method</strong> : {$payment|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong class="strong_item">Terms & Conditions</strong></p>
                                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: center; width: 15%;"></td>
                <td style="text-align: center; width: 30%;">
                    <table style="width: 100%;float:left;">
                        <tbody>
                            <tr>
                                <td  style="text-align: center;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">
                                    <p  style="text-align: center; width=100%;">Nguyen Van Nham</p>
                                    <p  style="text-align: center; width=100%;"><strong class="strong_item">CEO & Founder</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: center; width: 5%;"></td>
            </tr>
            <tr>
                <td style="text-align: left;" colspan="4">
                    <br />
                    <h4 class="thanksfor">Thank for your bussiness !</h4>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="margin-bottom: 20px; width: 100%; float: left;" class="border-icon">
        <tbody>
            <tr >
                <td style="width: 33.33%;">
                    <table style="width: 100%;" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/phone.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size:7pt;">+ 84 1659 005 710</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:7pt;">+ 84 1655 068 980</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 33.33%;">
                    <table style="width: 100%;" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/location.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size:7pt;">180 Hoang Quoc Viet Street,</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:7pt;">Ha Noi, Viet Nam</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 33.33%;">
                    <table style="width: 100%;" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/email.png" alt="" width="25" height="25" /></td>
                                <td style="width: 85%;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size:7pt;">demo@demo.com</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:7pt;">www.demodemo.com</td>
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
</td></tr></table>