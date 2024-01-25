<?php
/**
* The file is controller. Do not modify the file if you want to upgrade the module in future
*
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

include_once(_PS_MODULE_DIR_ . 'gwadvancedinvoice/model/gwadvancedinvoicetemplateModel.php');
class AdminGwadvancedinvoiceconfigController extends ModuleAdminController
{
	public function __construct()
	{
		$this->bootstrap = true;
		$this->display = 'edit';
        parent::__construct();
		$this->meta_title = $this->l('Advanced Invoice Template Builder');
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}
    public function initContent()
	{
		$this->display = 'edit';
		$this->initTabModuleList();
		$this->initToolbar();
		$this->initPageHeaderToolbar();

        $languages = Language::getLanguages(false);
        $this->context->smarty->assign(array(
            'countlang' => count($languages),
        ));
        $tpl = _PS_MODULE_DIR_.'gwadvancedinvoice/views/templates/admin/gwadvancedinvoiceconfig/tabsconfig.tpl';
        $tpl = Context::getContext()->smarty->fetch($tpl);
        $this->content .= $tpl;
		$this->content .= $this->renderForm();
        if(count($languages) > 1)
            $this->content .= $this->renderForm2();
		$this->content .= $this->initAddNewFont();

		$this->context->smarty->assign(array(
			'content' => $this->content,
			'url_post' => self::$currentIndex.'&token='.$this->token,
			'show_page_header_toolbar' => $this->show_page_header_toolbar,
			'page_header_toolbar_title' => $this->page_header_toolbar_title,
			'page_header_toolbar_btn' => $this->page_header_toolbar_btn
		));
	}
    public function initToolBarTitle()
	{
		$this->toolbar_title[] = $this->l('Advanced Invoice Template Builder');
		$this->toolbar_title[] = $this->l('General Settings');
	}
    public function initAddNewFont(){
        $this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Add new font'),
				'icon' => 'icon-list',
			),
			'input' => array(
				array(
					'type' => 'file',
					'label' => $this->l('New font'),
					'name' => 'font',
					'required' => true,
					'desc' => $this->l('Upload your customize font for your invoice. You must upload .ttf format'),
				),
                array(
					'type' => 'hidden',
					'label' => $this->l('Font'),
					'name' => 'newfont',
					'value' => '1',
				)
			),
			'submit' => array(
				'title' => $this->l('Add'),
				'name' => 'submitAddNewFont',
				'icon' => 'process-icon-save'
			)
		);
		$this->show_toolbar = false;
		$this->show_form_cancel_button = false;
		$this->toolbar_title = $this->l('Font');
		return parent::renderForm();
    }
    public function initPageHeaderToolbar()
	{
        $this->page_header_toolbar_btn = array(
            'new' => array(
                'href' => $this->context->link->getAdminLink('AdminGwadvancedinvoicetemplate'),
                'desc' => $this->l('Manage Templates'),
                'icon' => 'process-icon-duplicate'
            ),
            'about' => array(
                'href' => $this->context->link->getAdminLink('AdminGwadvancedinvoiceabout'),
                'desc' => $this->l('Document'),
                'icon' => 'process-icon-modules-list'
            ),
        );
		parent::initPageHeaderToolbar();
	}
    public function postProcess()
	{

        if (Tools::isSubmit('saveConfig'))
        {
            $shop_groups_list = array();
			$shops = Shop::getContextListShopID();
            $shop_context = Shop::getContext();
            //$allgroup = Group::getGroups((int)$this->context->language->id,(int)$shop_id);
            $allgroup = Group::getGroups((int)$this->context->language->id,(int)Context::getContext()->shop->id);
            $payment_modules = PaymentModule::getInstalledPaymentModules();
            $allstatus = OrderState::getOrderStates((int)$this->context->language->id);
            $res = true;
            foreach ($shops as $shop_id)
			{
				$shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
				if (!in_array($shop_group_id, $shop_groups_list))
					$shop_groups_list[] = (int)$shop_group_id;
                $res &= Configuration::updateValue('GWADVANCEDINVOICE_FEATURES_ID', Tools::getValue('GWADVANCEDINVOICE_FEATURES_ID'), false, (int)$shop_group_id, (int)$shop_id);
				$res &= Configuration::updateValue('GWADVANCEDINVOICE_ACTIVE', (int)Tools::getValue('GWADVANCEDINVOICE_ACTIVE'), false, (int)$shop_group_id, (int)$shop_id);
				$res &= Configuration::updateValue('GWADVANCEDINVOICE_TEMPLATE', (int)Tools::getValue('GWADVANCEDINVOICE_TEMPLATE'), false, (int)$shop_group_id, (int)$shop_id);
			    $res &= Configuration::updateValue('GWADVANCEDDELIVERY_TEMPLATE', (int)Tools::getValue('GWADVANCEDDELIVERY_TEMPLATE'), false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GWADVANCEDINVOICE_BACKOFFICE_LANG', (int)Tools::getValue('GWADVANCEDINVOICE_BACKOFFICE_LANG'), false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER'), false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS'), false, (int)$shop_group_id, (int)$shop_id);
                if($allgroup)
                    foreach($allgroup as $group){
                        $res &=Configuration::updateValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id, (int)$shop_id);
                        $res &=Configuration::updateValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id, (int)$shop_id);
                    }
                $res &= Configuration::updateValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id, (int)$shop_id);
                if($payment_modules)
                    foreach($payment_modules as $group){
                        $res &=Configuration::updateValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id, (int)$shop_id);
                        $res &=Configuration::updateValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id, (int)$shop_id);
                    }
                if($allstatus)
                    foreach($allstatus as $status)
                    {
                        if($status['invoice'])
                            $res &=Configuration::updateValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id, (int)$shop_id);
                        if($status['delivery'])
                            $res &=Configuration::updateValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id, (int)$shop_id);
                    };
            }
			/* Update global shop context if needed*/
			switch ($shop_context)
			{
				case Shop::CONTEXT_ALL:
                    $res &= Configuration::updateValue('GWADVANCEDINVOICE_FEATURES_ID', Tools::getValue('GWADVANCEDINVOICE_FEATURES_ID'));
					$res &= Configuration::updateValue('GWADVANCEDINVOICE_ACTIVE', (int)Tools::getValue('GWADVANCEDINVOICE_ACTIVE'));
					$res &= Configuration::updateValue('GWADVANCEDINVOICE_TEMPLATE', (int)Tools::getValue('GWADVANCEDINVOICE_TEMPLATE'));
                    $res &= Configuration::updateValue('GWADVANCEDDELIVERY_TEMPLATE', (int)Tools::getValue('GWADVANCEDDELIVERY_TEMPLATE'));
                    $res &= Configuration::updateValue('GWADVANCEDINVOICE_BACKOFFICE_LANG', (int)Tools::getValue('GWADVANCEDINVOICE_BACKOFFICE_LANG'));
					$res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER'));
                    $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS'));
                    if($allgroup)
                    foreach($allgroup as $group){
                        $res &=Configuration::updateValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group']));
                        $res &=Configuration::updateValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group']));
                    }
                    $res &= Configuration::updateValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE'));
                    $res &= Configuration::updateValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE'));
                    if($payment_modules)
                        foreach($payment_modules as $group){
                            $res &=Configuration::updateValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module']));
                            $res &=Configuration::updateValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module']));
                        }
                    if($allstatus)
                        foreach($allstatus as $status)
                        {
                            if($status['invoice'])
                                $res &=Configuration::updateValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state']));
                            if($status['delivery'])
                                $res &=Configuration::updateValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state']));
                        };
                    if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
						    $res &= Configuration::updateValue('GWADVANCEDINVOICE_FEATURES_ID', Tools::getValue('GWADVANCEDINVOICE_FEATURES_ID'), false, (int)$shop_group_id);
							$res &= Configuration::updateValue('GWADVANCEDINVOICE_ACTIVE', (int)Tools::getValue('GWADVANCEDINVOICE_ACTIVE'), false, (int)$shop_group_id);
							$res &= Configuration::updateValue('GWADVANCEDINVOICE_TEMPLATE', (int)Tools::getValue('GWADVANCEDINVOICE_TEMPLATE'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDDELIVERY_TEMPLATE', (int)Tools::getValue('GWADVANCEDDELIVERY_TEMPLATE'), false, (int)$shop_group_id);
						    $res &= Configuration::updateValue('GWADVANCEDINVOICE_BACKOFFICE_LANG', (int)Tools::getValue('GWADVANCEDINVOICE_BACKOFFICE_LANG'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS'), false, (int)$shop_group_id);
                            if($allgroup)
                                foreach($allgroup as $group){
                                    $res &=Configuration::updateValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id);
                                    $res &=Configuration::updateValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id);
                                }
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id);
                            if($payment_modules)
                                foreach($payment_modules as $group){
                                    $res &=Configuration::updateValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id);
                                    $res &=Configuration::updateValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id);
                                }
                            if($allstatus)
                                foreach($allstatus as $status)
                                {
                                    if($status['invoice'])
                                        $res &=Configuration::updateValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id);
                                    if($status['delivery'])
                                        $res &=Configuration::updateValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id);
                                };
                        }
					}
					break;
				case Shop::CONTEXT_GROUP:
					if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
						    $res &= Configuration::updateValue('GWADVANCEDINVOICE_FEATURES_ID', Tools::getValue('GWADVANCEDINVOICE_FEATURES_ID'), false, (int)$shop_group_id);
							$res &= Configuration::updateValue('GWADVANCEDINVOICE_ACTIVE', (int)Tools::getValue('GWADVANCEDINVOICE_ACTIVE'), false, (int)$shop_group_id);
							$res &= Configuration::updateValue('GWADVANCEDINVOICE_TEMPLATE', (int)Tools::getValue('GWADVANCEDINVOICE_TEMPLATE'), false, (int)$shop_group_id);
						    $res &= Configuration::updateValue('GWADVANCEDDELIVERY_TEMPLATE', (int)Tools::getValue('GWADVANCEDDELIVERY_TEMPLATE'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_BACKOFFICE_LANG', (int)Tools::getValue('GWADVANCEDINVOICE_BACKOFFICE_LANG'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS', (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS'), false, (int)$shop_group_id);
                            if($allgroup)
                                foreach($allgroup as $group){
                                    $res &=Configuration::updateValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id);
                                    $res &=Configuration::updateValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'],(int)Tools::getValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group']), false, (int)$shop_group_id);
                                }
                            $res &= Configuration::updateValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', Tools::getValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE'), false, (int)$shop_group_id);
                            if($payment_modules)
                                foreach($payment_modules as $group){
                                    $res &=Configuration::updateValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id);
                                    $res &=Configuration::updateValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'],(int)Tools::getValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module']), false, (int)$shop_group_id);
                                }
                            if($allstatus)
                                foreach($allstatus as $status)
                                {
                                    if($status['invoice'])
                                        $res &=Configuration::updateValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id);
                                    if($status['delivery'])
                                        $res &=Configuration::updateValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'],(int)Tools::getValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state']), false, (int)$shop_group_id);
                                };

                        }
					}
					break;
			}

            if (!$res)
				$this->errors[] = $this->l('The configuration could not be updated.');
			else
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminGwadvancedinvoiceconfig', true));
        }elseif (Tools::isSubmit('saveConfig2'))
        {
            $res = true;
            $languages = Language::getLanguages(false);
            $GINVOICE_LABEL_TAXDETAIL = array();
            $GINVOICE_LABEL_TAXRATE = array();
            $GINVOICE_LABEL_TOTAL = array();
            $GINVOICE_LABEL_TOTALEXCL = array();
            $GINVOICE_LABEL_PRODUCT = array();
            $GINVOICE_LABEL_ECOTAX = array();
            $GINVOICE_LABEL_SHIPPING = array();
            $GINVOICE_LABEL_EXEMPT = array();
            $GINVOICE_LABEL_BASEPRICE = array();
            $GINVOICE_LABEL_WRAPPING = array();
            $GINVOICE_LABEL_NOTAX = array();
            $GINVOICE_LABEL_DISCOUNT = array();
            $GINVOICE_LABEL_IMAGE = array();
            if($languages){
                foreach($languages as $language){
                    $GINVOICE_LABEL_TAXDETAIL[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_TAXDETAIL_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_TAXRATE[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_TAXRATE_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_TOTAL[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_TOTAL_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_TOTALEXCL[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_TOTALEXCL_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_PRODUCT[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_PRODUCT_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_ECOTAX[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_ECOTAX_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_SHIPPING[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_SHIPPING_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_EXEMPT[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_EXEMPT_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_BASEPRICE[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_BASEPRICE_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_WRAPPING[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_WRAPPING_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_NOTAX[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_NOTAX_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_DISCOUNT[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_DISCOUNT_'.(int)$language['id_lang']);
                    $GINVOICE_LABEL_IMAGE[(int)$language['id_lang']] = Tools::getValue('GINVOICE_LABEL_IMAGE_'.(int)$language['id_lang']);
                }
                $res &= Configuration::updateValue('GINVOICE_LABEL_TAXDETAIL', $GINVOICE_LABEL_TAXDETAIL);
                $res &= Configuration::updateValue('GINVOICE_LABEL_TAXRATE', $GINVOICE_LABEL_TAXRATE);
                $res &= Configuration::updateValue('GINVOICE_LABEL_TOTAL', $GINVOICE_LABEL_TOTAL);
                $res &= Configuration::updateValue('GINVOICE_LABEL_TOTALEXCL', $GINVOICE_LABEL_TOTALEXCL);
                $res &= Configuration::updateValue('GINVOICE_LABEL_PRODUCT', $GINVOICE_LABEL_PRODUCT);
                $res &= Configuration::updateValue('GINVOICE_LABEL_ECOTAX', $GINVOICE_LABEL_ECOTAX);
                $res &= Configuration::updateValue('GINVOICE_LABEL_SHIPPING', $GINVOICE_LABEL_SHIPPING);
                $res &= Configuration::updateValue('GINVOICE_LABEL_EXEMPT', $GINVOICE_LABEL_EXEMPT);
                $res &= Configuration::updateValue('GINVOICE_LABEL_BASEPRICE', $GINVOICE_LABEL_BASEPRICE);
                $res &= Configuration::updateValue('GINVOICE_LABEL_WRAPPING', $GINVOICE_LABEL_WRAPPING);
                $res &= Configuration::updateValue('GINVOICE_LABEL_NOTAX', $GINVOICE_LABEL_NOTAX);
                $res &= Configuration::updateValue('GINVOICE_LABEL_DISCOUNT', $GINVOICE_LABEL_DISCOUNT);
                $res &= Configuration::updateValue('GINVOICE_LABEL_IMAGE', $GINVOICE_LABEL_IMAGE);

            }
            if (!$res)
				$this->errors[] = $this->l('The configuration could not be updated.');
			else
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminGwadvancedinvoiceconfig', true));

        }elseif (Tools::isSubmit('newfont')){
            $type = Tools::strtolower(Tools::substr(strrchr($_FILES['font']['name'], '.'), 1));
            if (isset($_FILES['font']) &&
					isset($_FILES['font']['tmp_name']) &&
					!empty($_FILES['font']['tmp_name']) &&
					$type == 'ttf'
				)
				{

				    if(Tools::file_exists_no_cache(_PS_MODULE_DIR_ . 'gwadvancedinvoice/views/fonts/'.$_FILES['font']['name'])){
				        $this->errors[] = $this->l('Font already exists.');
				    }else{
				        try {
                            if(move_uploaded_file($_FILES['font']['tmp_name'],dirname(__FILE__).'/../../views/fonts/'.$_FILES['font']['name'])){
                                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
                                    TCPDF_FONTS::addTTFfont(_PS_MODULE_DIR_ . 'gwadvancedinvoice/views/fonts/'.$_FILES['font']['name'], 'TrueTypeUnicode', '', 96);
                                }else {
                                    $pdf_render = new PDFGenerator((bool)Configuration::get('PS_PDF_USE_CACHE'));
                                    $pdf_render->addTTFfont(_PS_MODULE_DIR_ . 'gwadvancedinvoice/views/fonts/'.$_FILES['font']['name'], 'TrueTypeUnicode', '', 96);
                                }
                            }else{
                                $this->errors[] = $this->l('Font could not be uploaded.');
                            }
                        } catch (Exception $e) {
                            $this->errors[] = $e->getMessage();
                        }
				    }
				}else
                    $this->errors[] = $this->l('Font could not be uploaded.');
        }
    }
    public function renderForm() {
        $_templates = gwadvancedinvoicetemplateModel::getAllBlock();
        $templates = array();
        $templates[] = array(
    				'value' => '',
    				'name' => $this->l('-- Choose template --')
    			);
       if($_templates)
        foreach($_templates as $template){
            $templates[] = array(
    				'value' => $template['id_gwadvancedinvoicetemplate'],
    				'name' => $template['title']
    			);
        }
        // since version 1.2.1
        $pdfbackofficelangs = array(
            array('value' => '0','name' => $this->l('Customer\'s language')),
            array('value' => '-1','name' => $this->l('Current Backoffice language'))
        );
        $pagenumber_pos = array(
            array('value' => '1','name' => $this->l('Top Left')),
            array('value' => '2','name' => $this->l('Top Center')),
            array('value' => '3','name' => $this->l('Top Right')),
            array('value' => '4','name' => $this->l('Bottom Left')),
            array('value' => '5','name' => $this->l('Bottom Center')),
            array('value' => '6','name' => $this->l('Bottom Right')),
        );

        $languages = Language::getLanguages(false);
        if($languages)
            foreach($languages as $language){
                $pdfbackofficelangs[] = array('value' => (int)$language['id_lang'],'name' => $language['name']);
            }
        $template_url  = 'index.php?controller=AdminGwadvancedinvoicetemplate&token='.Tools::getAdminTokenLite('AdminGwadvancedinvoicetemplate');
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('General'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'opentab',
                    'name' => 'config_content',
                ),
                array(
					'type' => 'select',
					'label' => $this->l('Invoice - Delivery language(when print in backoffice)'),
					'name' => 'GWADVANCEDINVOICE_BACKOFFICE_LANG',
                    'lang' => false,
					'class' => 'GWADVANCEDINVOICE_BACKOFFICE_LANG',
					'options' => array(
						'query' => $pdfbackofficelangs,
						'id' => 'value',
						'name' => 'name'
					)),
                array(
					'type' => 'select',
					'label' => $this->l('Default invoice'),
					'hint' => $this->l('Select an invoice template that you created. Then it will be used for default invoice for customer group and generate multi invoice'),
					'name' => 'GWADVANCEDINVOICE_TEMPLATE',
                    /* for new version 1.0.5 */
                    'required' => true,
                    /* end*/
                    'desc' => $this->l('The field is require to active the module. If the list is empty, ').'<a href="'.$template_url.'">'.$this->l('Click here').'</a>'.$this->l(' to create template.'),
					'lang' => false,
					'class' => 'GWADVANCEDINVOICE_TEMPLATE',
					'options' => array(
						'query' => $templates,
						'id' => 'value',
						'name' => 'name'
					)
                ),
                array(
                    'type' => 'customergroupselect',
                    'label' => $this->l('Invoice for'),
                    'name' => 'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE',
                    'desc' => $this->l('You can select an invoice for a special customer group, payment method or order status. If you don\'t select then default invoice will be used.'),
                    'options'=>$templates
                ),
                array(
					'type' => 'select',
					'label' => $this->l('Default delivery'),
					'hint' => $this->l('Select an delivery template that you created. Then it will be used for default delivery for customer group and generate multi delivery'),
					'name' => 'GWADVANCEDDELIVERY_TEMPLATE',
                    /* for new version 1.0.5 */
                    'required' => true,
                    /* end*/
                    'desc' => $this->l('The field is require to active the module. If the list is empty, ').'<a href="'.$template_url.'">'.$this->l('Click here').'</a>'.$this->l(' to create template.'),
					'lang' => false,
					'class' => 'GWADVANCEDDELIVERY_TEMPLATE',
					'options' => array(
						'query' => $templates,
						'id' => 'value',
						'name' => 'name'
					)
                ),
                array(
                    'type' => 'customergroupselect',
                    'label' => $this->l('Delivery for'),
                    'name' => 'GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE',
                    'desc' => $this->l('You can select an delivery for a special customer group, payment method or order status. If you don\'t select then default delivery will be used.'),
                    'options'=>$templates
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active Module'),
    				'hint' => $this->l('Disable the module if you want to use default invoice of Prestashop'),
                    'name' => 'GWADVANCEDINVOICE_ACTIVE',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(array(
                            'id' => 'GWADVANCEDINVOICE_ACTIVE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')), array(
                            'id' => 'GWADVANCEDINVOICE_ACTIVE_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'free',
                    'name' => 'warrning_text',
                    'label' => '',
    								'desc' => $this->l('You MUST create template, then select a template before ACTIVE MODULE. So DON\'T active module if you haven\'t yet choose any template.')
                    ),
                array(
                    'type' => 'closetab',
                    'name' => '',
                ),
                array(
                    'type' => 'opentab',
                    'name' => 'extra_content',
                ),
                array(
											'type' => 'text',
											'label' => $this->l('Id Features'),
											'name' => 'GWADVANCEDINVOICE_FEATURES_ID',
	                    'lang' => false,
	                    'desc' => $this->l('Show some features of product on invoice by the variable {$order_detail.features}. The input allow you to set the features that you want to show. Ex: 1,5,7')),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Page number in PDF file'),
                    'name' => 'GWADVANCEDINVOICE_PAGE_NUMBER',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(array(
                            'id' => 'GWADVANCEDINVOICE_PAGE_NUMBER_on',
                            'value' => 1,
                            'label' => $this->l('Yes')), array(
                            'id' => 'GWADVANCEDINVOICE_PAGE_NUMBER_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
					'type' => 'select',
					'label' => $this->l('Page number position'),
					'name' => 'GWADVANCEDINVOICE_PAGE_NUMBER_POS',
                    'lang' => false,
					'class' => 'GWADVANCEDINVOICE_PAGE_NUMBER_POS',
					'options' => array(
						'query' => $pagenumber_pos,
						'id' => 'value',
						'name' => 'name'
					)
                ),
                array(
                    'type' => 'closetab',
                    'name' => '',
                ),
						           
           ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveConfig'
            )
        );
        $this->fields_value = $this->getConfigFieldsValues();
        return parent::renderForm();
    }
    public function getConfigFieldsValues()
	{
		$id_shop_group = Shop::getContextShopGroupID();
		$id_shop = Shop::getContextShopID();
        if($id_shop <= 0) $id_shop = (int)Context::getContext()->shop->id;
        $allgroup = Group::getGroups((int)$this->context->language->id,(int)$id_shop);
        $allstatus = OrderState::getOrderStates((int)$this->context->language->id);
        $payment_modules = PaymentModule::getInstalledPaymentModules();
        if($payment_modules)
            foreach($payment_modules as &$payment_module){
                $payment_module['name'] = Module::getModuleName($payment_module['name']);
            }
        $invoice_pdf_template_type = Tools::getValue('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', Configuration::get('GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE', null, $id_shop_group, $id_shop));
        $delivery_pdf_template_type = Tools::getValue('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', Configuration::get('GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE', null, $id_shop_group, $id_shop));
        $fields = array(
			'GWADVANCEDINVOICE_ACTIVE' => Tools::getValue('GWADVANCEDINVOICE_ACTIVE', Configuration::get('GWADVANCEDINVOICE_ACTIVE', null, $id_shop_group, $id_shop)),
			'GWADVANCEDINVOICE_TEMPLATE' => Tools::getValue('GWADVANCEDINVOICE_TEMPLATE', Configuration::get('GWADVANCEDINVOICE_TEMPLATE', null, $id_shop_group, $id_shop)),
		    'GWADVANCEDDELIVERY_TEMPLATE' => Tools::getValue('GWADVANCEDDELIVERY_TEMPLATE', Configuration::get('GWADVANCEDDELIVERY_TEMPLATE', null, $id_shop_group, $id_shop)),
            'GWADVANCEDINVOICE_BACKOFFICE_LANG' => (int)Tools::getValue('GWADVANCEDINVOICE_BACKOFFICE_LANG', Configuration::get('GWADVANCEDINVOICE_BACKOFFICE_LANG', null, $id_shop_group, $id_shop)),
            'GWADVANCEDINVOICE_FEATURES_ID' => Tools::getValue('GWADVANCEDINVOICE_FEATURES_ID', Configuration::get('GWADVANCEDINVOICE_FEATURES_ID', null, $id_shop_group, $id_shop)),
            'groups'=>$allgroup,
            'payment_modules'=>$payment_modules,
            'allstatus'=>$allstatus,
            'GWADVANCEDINVOICE_CUSTOMER_TEMPLATE_TYPE'=> ($invoice_pdf_template_type != '' ? $invoice_pdf_template_type  : 'customer_group'),
            'GWADVANCEDDELIVERY_CUSTOMER_TEMPLATE_TYPE'=> ($delivery_pdf_template_type != '' ? $delivery_pdf_template_type  : 'customer_group'),
            
            'GWADVANCEDINVOICE_PAGE_NUMBER' => (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER', Configuration::get('GWADVANCEDINVOICE_PAGE_NUMBER', null, $id_shop_group, $id_shop)),
            'GWADVANCEDINVOICE_PAGE_NUMBER_POS' => (int)Tools::getValue('GWADVANCEDINVOICE_PAGE_NUMBER_POS', Configuration::get('GWADVANCEDINVOICE_PAGE_NUMBER_POS', null, $id_shop_group, $id_shop)),
        );
        if($fields['GWADVANCEDINVOICE_PAGE_NUMBER_POS'] == '' || $fields['GWADVANCEDINVOICE_PAGE_NUMBER_POS'] == 0){
            $fields['GWADVANCEDINVOICE_PAGE_NUMBER_POS'] = 6;
        }
        if($allgroup)
            foreach($allgroup as $group)
            {
                $fields['GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group']] = Tools::getValue('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'], Configuration::get('GWADVANCEDINVOICE_GROUP_'.(int)$group['id_group'], null, $id_shop_group, $id_shop));
                $fields['GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group']] = Tools::getValue('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'], Configuration::get('GWADVANCEDDELIVERY_GROUP_'.(int)$group['id_group'], null, $id_shop_group, $id_shop));
            };
        if($payment_modules)
            foreach($payment_modules as $group)
            {
                $fields['GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module']] = Tools::getValue('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'], Configuration::get('GWADVANCEDINVOICE_PAYMENT_TEMPLATE_'.(int)$group['id_module'], null, $id_shop_group, $id_shop));
                $fields['GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module']] = Tools::getValue('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'], Configuration::get('GWADVANCEDDELIVERY_PAYMENT_TEMPLATE_'.(int)$group['id_module'], null, $id_shop_group, $id_shop));
            };
        if($allstatus)
            foreach($allstatus as $status)
            {
                if($status['invoice'])
                    $fields['GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state']] = Tools::getValue('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'], Configuration::get('GWADVANCEDINVOICE_STATUS_TEMPLATE_'.(int)$status['id_order_state'], null, $id_shop_group, $id_shop));
                if($status['delivery'])
                    $fields['GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state']] = Tools::getValue('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'], Configuration::get('GWADVANCEDDELIVERY_STATUS_TEMPLATE_'.(int)$status['id_order_state'], null, $id_shop_group, $id_shop));
            };
		return $fields;
	}
    public function renderForm2() {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Translate for {$tax_tab} variable'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
					'type' => 'text',
					'label' => $this->l('Discounts'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_DISCOUNT'),
                array(
					'type' => 'text',
					'label' => $this->l('image(s):'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_IMAGE'),
                array(
					'type' => 'text',
					'label' => $this->l('Tax Detail'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_TAXDETAIL'),
                array(
					'type' => 'text',
					'label' => $this->l('Tax Rate'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_TAXRATE'),
                array(
					'type' => 'text',
					'label' => $this->l('Total Tax'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_TOTAL'),
                array(
					'type' => 'text',
					'label' => $this->l('Total Tax Excl'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_TOTALEXCL'),
                array(
					'type' => 'text',
					'label' => $this->l('Products'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_PRODUCT'),
                array(
					'type' => 'text',
					'label' => $this->l('Ecotax'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_ECOTAX'),
                array(
					'type' => 'text',
					'label' => $this->l('Shipping'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_SHIPPING'),
                array(
					'type' => 'text',
					'label' => $this->l('Base price'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_BASEPRICE'),
                array(
					'type' => 'text',
					'label' => $this->l('Wrapping'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_WRAPPING'),
                array(
					'type' => 'text',
					'label' => $this->l('No taxes'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_NOTAX'),
                array(
					'type' => 'text',
					'label' => $this->l('Exempt of VAT according to section 259B of the General Tax Code.'),
                    'lang' => true,
					'name' => 'GINVOICE_LABEL_EXEMPT'),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveConfig2'
            )
        );
        $this->fields_value = $this->getConfigFieldsValues2();
        return parent::renderForm();
    }
    public function getConfigFieldsValues2()
	{
	    $fields = array();
        $languages = Language::getLanguages(false);
        if($languages)
            foreach($languages as $language){
                $fields['GINVOICE_LABEL_DISCOUNT'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_DISCOUNT',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_IMAGE'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_IMAGE',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_TAXDETAIL'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_TAXDETAIL',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_TAXRATE'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_TAXRATE',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_TOTAL'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_TOTAL',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_TOTALEXCL'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_TOTALEXCL',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_PRODUCT'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_PRODUCT',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_ECOTAX'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_ECOTAX',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_SHIPPING'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_SHIPPING',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_EXEMPT'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_EXEMPT',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_BASEPRICE'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_BASEPRICE',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_WRAPPING'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_WRAPPING',(int)$language['id_lang']);
                $fields['GINVOICE_LABEL_NOTAX'][(int)$language['id_lang']] = Configuration::get('GINVOICE_LABEL_NOTAX',(int)$language['id_lang']);
            }

		return $fields;
	}
 }
?>
