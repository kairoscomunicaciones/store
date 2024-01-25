<?php
class Mail extends MailCore
{
    /*
    * module: gwadvancedinvoice
    * date: 2019-10-22 08:09:20
    * version: 1.3.0
    */
    /*
    * module: gwadvancedinvoice
    * date: 2019-10-22 08:09:20
    * version: 1.3.0
    */
    /*
    * module: gwadvancedinvoice
    * date: 2019-10-22 08:09:20
    * version: 1.3.0
    */
    /*
    * module: gwadvancedinvoice
    * date: 2019-10-22 08:09:20
    * version: 1.3.0
    */
    /*
    * module: gwadvancedinvoice
    * date: 2022-11-29 00:05:54
    * version: 1.3.1
    */
    public static function Send($id_lang, $template, $subject, $template_vars, $to,
        $to_name = null, $from = null, $from_name = null, $file_attachment = null, $mode_smtp = null,
        $template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null, $bcc = null, $reply_to = null,$reply_to_name = null)
    {
        $id_order_invoice = 0; 
        if(Module::isInstalled('gwadvancedinvoice') && Module::isEnabled('gwadvancedinvoice')){
            if ($file_attachment) {
                $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
                if($template == 'order_conf' && isset($file_attachment['name']) && $file_attachment['name'] !=''){
                    $number = (int)Tools::substr($file_attachment['name'], Tools::strlen($file_attachment['name'])-10,6);
                    $id_order = 0;
                    if(isset($template_vars['{id_order}']))
                        $id_order = (int)$template_vars['{id_order}'];
                    elseif(isset($template_vars['{email}']) && $template_vars['{order_name}']){
                        $orderObj = Order::getByReferenceAndEmail($template_vars['{email}'],$template_vars['{order_name}']);
                        $id_order = (int)$orderObj->id;
                    }
                    $id_order_invoice = (int)$invoiceObj->getInvoiceId($id_order,$number);
                    if($id_order_invoice > 0){
                        $OrderInvoiceObj =  new OrderInvoice((int)$id_order_invoice);
                        if(Validate::isLoadedObject($OrderInvoiceObj)){
                            $file_attachment['name'] = $invoiceObj->formatNumber('I',$OrderInvoiceObj->number,$OrderInvoiceObj). '.pdf';
                        }
                    }
                }
                if (isset($file_attachment['invoice']) && isset($file_attachment['invoice']['name'])) {
                    $number = (int)Tools::substr($file_attachment['invoice']['name'], Tools::strlen($file_attachment['invoice']['name'])-10,6);
                    $id_order = 0;
                    if(isset($template_vars['{id_order}']))
                        $id_order = (int)$template_vars['{id_order}'];
                    elseif(isset($template_vars['{email}']) && $template_vars['{order_name}']){
                        $orderObj = Order::getByReferenceAndEmail($template_vars['{email}'],$template_vars['{order_name}']);
                        $id_order = (int)$orderObj->id;
                    }
                    $id_order_invoice = (int)$invoiceObj->getInvoiceId($id_order,$number);
                    if($id_order_invoice > 0){
                        $OrderInvoiceObj =  new OrderInvoice((int)$id_order_invoice);
                        if(Validate::isLoadedObject($OrderInvoiceObj)){
                            $file_attachment['invoice']['name'] = $invoiceObj->formatNumber('I',$OrderInvoiceObj->number,$OrderInvoiceObj). '.pdf';
                            if (isset($file_attachment['delivery']) && isset($file_attachment['delivery']['name'])) {
                                $file_attachment['delivery']['name'] = $invoiceObj->formatNumber('D',$OrderInvoiceObj->delivery_number,$OrderInvoiceObj). '.pdf';
                            }
                        }
                    }
                }
                
            }
        }
        return  parent::Send($id_lang, $template, $subject, $template_vars, $to,
        $to_name, $from, $from_name, $file_attachment, $mode_smtp,
        $template_path, $die, $id_shop, $bcc, $reply_to,$reply_to_name);
    }
}