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
    <table style="margin-bottom: 20px; width: 100%; float: left;" class="border_bottom">
        <tbody>
            <tr>
                <td style="width: 30%;"><h4 class="invoice_title">INVOICE</h4></td>
                <td style="width: 70%;">
                    <table style="width: 100%; float: left;"  cellpadding="7" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="header small" style="width: 25%;text-align:center;">Invoice Number</th>
                                <th class="header small"  style="width: 25%;text-align:center;">Invoice Date</th>
                                <th class="header small"  style="width: 25%;text-align:center;">Order Reference</th>
                                <th class="header small"  style="width: 25%;text-align:center;">Order date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 25%;text-align:center;"><strong class="strong_item">{$invoice_number|escape:'htmlall':'UTF-8'}</strong></td>
                                <td style="width: 25%;text-align:center;"><strong class="strong_item">{$invoice_date|escape:'htmlall':'UTF-8'}</strong></td>
                                <td style="width: 25%;text-align:center;"><strong class="strong_item">{$reference|escape:'htmlall':'UTF-8'}</strong></td>
                                <td style="width: 25%;text-align:center;"><strong class="strong_item">{$date_add|escape:'htmlall':'UTF-8'}</strong></td>
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
                <td style="width:65%;"></td>
                <td style="width:35%;text-align:right;">{$logo|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td colspan="2">
                    <h4 class="thanksfor">Thank for your bussiness !</h4>
                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt dolore magnam aliquam quaerat.</p>
                </td>
            </tr>
            <tr><td colspan="2"></td></tr>
        </tbody>
    </table>
    {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 60%;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr><td colspan="2"></td></tr>
                            <tr>
                                <td><h4 class="header_title">BILLING ADDRESS</h4>
                                    <p>{$billing_firstname|escape:'htmlall':'UTF-8'} {$billing_lastname|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_address1|escape:'htmlall':'UTF-8'} {$billing_address2|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_city|escape:'htmlall':'UTF-8'} - {$billing_postcode|escape:'htmlall':'UTF-8'} {$billing_state|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_phone|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                                </td>
                                <td><h4 class="header_title">DELIVERY ADDRESS</h4>
                                    <p>{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_phone|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="width: 100%;" cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td  style="width: 6%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/payment.png" alt="" width="25" height="25" /></td>
                                                <td style="width: 94%;" class="payment_method"><strong class="strong_item">Payment Method</strong> : {$payment|escape:'htmlall':'UTF-8'}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 40%;">
                    <table style="width: 100%;" cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr class="color_line_even">
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$total_products|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr class="color_line_odd">
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
                                <td style="text-align: center; width: 40%;">-{displayPrice:$total_discounts_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr class="color_line_even">
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$total_shipping_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr class="color_line_odd">
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
                                <td style="text-align: center; width: 40%;">{displayPrice:$footer.total_taxes|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                <td style="text-align: center; width: 40%;" class="total_title_background total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
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
                </td>
                <td style="text-align: center; width: 10%;"></td>
                <td style="text-align: center; width: 40%;">
                    <table style="width: 100%; margin-left: auto; margin-right: auto;">
                        <tbody>
                            <tr><td><p><strong class="strong_item">CEO & Founder</strong></p><br /></td></tr>
                            <tr style="text-align: center;">
                                <td><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="170px" height="auto" /></td>
                            </tr>
                            <tr style="text-align: center;">
                                <td>
                                    <p>Nguyen Van Nham</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</td></tr></table>