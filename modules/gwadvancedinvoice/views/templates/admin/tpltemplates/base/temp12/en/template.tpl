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
                <td style="float: left; width: 60%;"></td>
                <td style="float: right; width: 40%;text-align: right;">{$logo|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <td style="float: left; width: 60%;"></td>
                <td style="width: 40%;float: right;">
                        <table style="width: 100%;float: right;">
                            <tbody>
                                <tr>
                                    <td style="font-size: 7pt;width: 50%"></td>
                                    <td style="font-size: 7pt;text-align: right;width: 50%">210 Hoang Quoc Viet Street</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 7pt;width: 50%"></td>
                                    <td style="font-size: 7pt;text-align: right;">Ha Noi, Viet Nam</td>
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
                <td style="width: 30%;">
                        <h4 class="invoice_title">  INVOICE</h4>
                        
                </td>
                <td  style="width: 70%;">
                <table style="width: 100%; margin-top: 20px;"><tbody>
                <tr>
                    <td style="width: 33%;">
                        <table style="width: 100%;">
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
                    
                    <td style="width: 34%;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/email.png" alt="" width="25" height="25" /></td>
                                    <td style="font-size:8pt; width: 85%;">demo@demo.com</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </td>
                  
                    <td style="width: 33%;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 15%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/location.png" alt="" width="25" height="25" /></td>
                                    
                                        <td style="font-size:8pt; width: 85%;">www.demodemo.com</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr></tbody></table>
                </td>
            </tr>
        </tbody>
    </table>
    <br /> 
    <table  style="width: 100%; float: left;">
        <tbody>
            <tr>
                 <td style="width: 33%;">
                    <h4 class="header_title">BILLING ADDRESS</h4><p>{$billing_firstname|escape:'htmlall':'UTF-8'} {$billing_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_address1|escape:'htmlall':'UTF-8'} {$billing_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_city|escape:'htmlall':'UTF-8'} - {$billing_postcode|escape:'htmlall':'UTF-8'} {$billing_state|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$billing_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                </td>
                <td style="width: 33%;">
                    <h4 class="header_title">DELIVERY ADDRESS</h4><p>{$delivery_firstname|escape:'htmlall':'UTF-8'} {$delivery_lastname|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_address1|escape:'htmlall':'UTF-8'} {$delivery_address2|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_city|escape:'htmlall':'UTF-8'} - {$delivery_postcode|escape:'htmlall':'UTF-8'} {$delivery_state|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone|escape:'htmlall':'UTF-8'}
                        <br /> {$delivery_phone_mobile|escape:'htmlall':'UTF-8'}</p>
                </td>
                <td style="width: 34%;">
                    <table  style="width: 100%; float: left;">
                        <tr>
                            <td class="border-total">
                                <table ><tbody>
                                <tr>
                                <td>Total Due: <strong class="strong_item"  >{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</strong>
                                </td>
                                </tr>
                                <tr><td></td></tr>
                                </tbody></table>
                               </td>
                        </tr>
                        <tr><td></td></tr>
                        <tr><td>
                        <br />
                        <strong class="strong_item">Invoice No: </strong>{$invoice_number|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Carier: </strong>{$order_carrier_name|escape:'htmlall':'UTF-8'}
                    <br /><strong class="strong_item">Invoice Date: </strong>{$invoice_date|escape:'htmlall':'UTF-8'}
                        </td></tr>
                    </table>
                    
                    
                    </td>
            </tr>
        </tbody>
    
    </table>
    {$products_list|escape:'htmlall':'UTF-8'}
    <table style="width: 100%;float:left;" cellpadding="7" cellspacing="0">
        <tbody>
            <tr>
                <td style="float: left; width: 60%;"></td>
                <td style="float: right; width: 40%;">
                    <table style="width: 100%;" cellpadding="5" cellspacing="0">
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
                <td><strong class="strong_item">Payment Method</strong> : {$payment|escape:'htmlall':'UTF-8'}</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%;float:left;" cellpadding="7" cellspacing="0">
        <tbody>
            <tr>
                <td style="float: left; width: 60%;"  class="top_border" ></td>
                <td style="float: right; width: 40%;" class="total_wp">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="text-align: center; width: 60%;" class="total_text"><strong>Total: </strong></td>
                                <td style="text-align: center; width: 40%;" class="total_text">{displayPrice:$total_paid|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
           
        </tbody>
    </table>
    <br />
    <table style="width: 100%;float:left;">
        <tbody>
            <tr>
                <td class="thanksfor"style="text-align: left;width: 50%;" ><br/><br />Thank for your bussiness !</td>
                <td style="text-align: center; width: 10%;"></td>
                <td style="text-align: center; width: 40%;">
                    <table style="width: 100%; margin-left: auto; margin-right: auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: center; width: 15%;"></td>
                                <td  style="text-align: center; width: 70%;">
                                    <p>Nguyen Van Nham</p>
                                    <p><strong class="strong_item">CEO & Founder</strong></p>
                                </td>
                                <td style="text-align: center; width: 15%;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width: 15%;"></td>
                                <td  style="text-align: center; width: 70%;"><img src="{$tpltemplate_dir|escape:'htmlall':'UTF-8'}/views/img/imgtemplates/founder.png" alt="" width="314" height="71" /></td>
                                <td style="text-align: center; width: 15%;"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                
            </tr>
        </tbody>
    </table>
</td></tr></table>