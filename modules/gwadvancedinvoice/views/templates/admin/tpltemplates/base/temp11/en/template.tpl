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
                <td style="text-align:center;">{$logo|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <td  style="text-align:center;">
                    <h4 class="invoice_title"><strong>INVOICE</strong> {$invoice_number|escape:'htmlall':'UTF-8'}</h4>
                </td>
            </tr>
            <tr>
                <td  style="text-align:center;">
                    <strong class="strong_item">Invoice Date: </strong>{$invoice_date|escape:'htmlall':'UTF-8'}
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width: 100%; float: left;" class="border_bottom">
        <tbody>
            <tr>
                <td style="width: 30%;">
                    <table style="width: 100%;" cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <h4 class="header_title">BILLING ADDRESS</h4>
                                    <p>{$billing_firstname|escape:'htmlall':'UTF-8'} {$billing_lastname|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_address1|escape:'htmlall':'UTF-8'} {$billing_address2|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_city|escape:'htmlall':'UTF-8'} - {$billing_postcode|escape:'htmlall':'UTF-8'} {$billing_state|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_phone|escape:'htmlall':'UTF-8'}
                                        <br /> {$billing_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 class="header_title">DELIVERY ADDRESS</h4>
                                    <p>{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_phone|escape:'htmlall':'UTF-8'}
                                        <br /> {$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                                </td>
                            </tr>
                            <tr><td>
                                <h4 class="header_title">CEO & Founder</strong></h4>
                                <p>Nguyen Van Nham</p>
                            </td></tr>
                            <tr>
                                <td><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 70%;">
                    <table style="width: 100%;" cellpadding="7" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <h4 class="header_title">OVERVIEW</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quasi vero, inquit, perpetua oratio rhetorum solum, non etiam philosophorum sit. Nonne igitur tibi videntur, inquit, mala? Dici enim nihil potest verius.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {$products_list|escape:'htmlall':'UTF-8'}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--tr>
                <td style="width: 30%;">
                    
                </td>
                <td style="width: 70%;"><strong class="strong_item">Invoice No: </strong>{$invoice_number|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Carier: </strong>{$order_carrier_name|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Invoice Date: </strong>{$invoice_date|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Total Due: </strong>{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
            </tr-->
        </tbody>
    </table>
    <table style="width: 100%;" cellpadding="7" cellspacing="0">
        <tbody>
            <tr>
                <td style="float: left; width: 60%;">
                    <table style="width: 100%;">
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
                <td style="float: right; width: 40%;">
                    <table class="total_box" style="width: 100%;"  cellpadding="3" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Product: </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$total_products|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Discounts: </strong></td>
                                <td style="text-align: right; width: 40%;">-{displayPrice:$total_discounts_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Shipping Cost : </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$total_shipping_tax_excl|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width: 60%;"><strong class="strong_item">Total Tax : </strong></td>
                                <td style="text-align: right; width: 40%;">{displayPrice:$footer.total_taxes|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="width: 100%;" cellpadding="7" cellspacing="0">
                                        <tr  class="total_wp">
                                            <td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                            <td style="text-align: center; width: 40%;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                                        </tr>
                                    </table>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--tr class="total_wp">
                <td style="float: right; width: 60%;"></td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                <td style="text-align: center; width: 40%;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr-->
        </tbody>
    </table>
    <br />
    <table style="width: 100%;">
        <tbody>
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