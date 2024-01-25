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
    <table class="" style="width: 100%; float: left;" cellpadding="15" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 35%; text-align: left;">{$logo|escape:'htmlall':'UTF-8'}</td>
                <td style="width: 30%;text-align:center;"></td>
                <td style="width: 35%;text-align: right;"><h4 class="invoice_title">INVOICE</h4><span>{$invoice_date|escape:'htmlall':'UTF-8'}</span></td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%; float: left;">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;" cellpadding="15" cellspacing="0">
                    <tr><td style="width: 50%;">
                    <h4 class="header_title">BILLING ADDRESS</h4>
                    <p>{$billing_firstname|escape:'htmlall':'UTF-8'} {$billing_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_address1|escape:'htmlall':'UTF-8'} {$billing_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_city|escape:'htmlall':'UTF-8'} - {$billing_postcode|escape:'htmlall':'UTF-8'} {$billing_state|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                    </td><td style="width: 50%;">
                    <h4 class="header_title">DELIVERY ADDRESS</h4>
                    <p>{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                    </td></tr>
                    </table>
                    
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 40%;">
                    <table style="width: 100%;" class="top_page_wp"  cellpadding="15" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table style="width: 100%;" class="top_page"  cellpadding="5" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Invoice No: </strong></td><td style="text-align: left; width: 40%;">{$invoice_number|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Carier: </strong></td><td style="text-align: left; width: 40%;">{$order_carrier_name|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Invoice Date: </strong></td><td style="text-align: left; width: 40%;">{$invoice_date|escape:'htmlall':'UTF-8'}</td>
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
    <table style="width: 100%;" cellpadding="15" cellspacing="0"><tr><td>{$products_list|escape:'htmlall':'UTF-8'}</td></tr></table>
    <table class="productlist_footer" style="width: 100%;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 40%;">
                    <table style="width: 100%;" class="top_page_wp"  cellpadding="15" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="top_page_wp" style="width: 100%;" cellpadding="5" cellspacing="0">
                                        <tbody>
                                            <tr class="product color_line_even">
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
                                                <td style="text-align: left; width: 40%;">{displayPrice:$total_products|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr  class="product color_line_odd">
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
                                                <td style="text-align: left; width: 40%;">-{displayPrice:$total_discounts_tax_excl|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr class="product color_line_even">
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
                                                <td style="text-align: left; width: 40%;">{displayPrice:$total_shipping_tax_excl|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr  class="product color_line_odd">
                                                <td style="text-align: left; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
                                                <td style="text-align: left; width: 40%;">{displayPrice:$footer.total_taxes|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                            <tr class="total_wp">
                                                <td style="text-align: left; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                                <td style="text-align: left; width: 40%;" class="total_val">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 60%;">
                    <table style="width: 100%;" cellpadding="5" cellspacing="0">
                        <tbody>
                            <tr><td class="payment_method"><strong class="strong_item">Payment Method</strong> : {$payment|escape:'htmlall':'UTF-8'}</td></tr>
                            <tr><td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tamen est aliquid, quod nobis non liceat, liceat illis. Bona autem corporis huic sunt, quod posterius posui, similiora.</td></tr>
                        </tbody>
                    </table>
                    <table style="width: 100%; margin-left: auto; margin-right: auto;">
                        <tbody>
                            <tr>
                                <td style="text-align:center;"><br /><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="170px" height="auto" /></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
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
    <table style="width: 100%;" cellpadding="3" cellspacing="0">
        <tbody>
            <tr><td colspan="3"></td></tr>
            <tr><td style="width:30%;"></td><td style="width: 40%;text-align: center;">{$barcode_invoice|escape:'htmlall':'UTF-8'}</td><td style="width:30%;"></td></tr>
            <tr>
                <td  colspan="3" style="width: 100%;text-align: center;"><h4 class="thanksfor">Thank for your bussiness !</h4></td>
            </tr>
        </tbody>
    </table>
</td></tr></table>