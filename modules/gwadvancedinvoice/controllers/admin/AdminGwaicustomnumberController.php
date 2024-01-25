<?php
/**
* The file is controller. Do not modify the file if you want to upgrade the module in future
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link      http://www.globosoftware.net
*/

class AdminGwaicustomnumberController extends ModuleAdminController
{
    public function __construct()
	{
		$this->bootstrap = true;
		$this->display = 'view';
        parent::__construct();
		$this->meta_title = $this->l('Advanced custom number');
		
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}
    public function initToolBarTitle()
	{
		$this->toolbar_title[] = $this->l('Advanced Invoice Builder');
		$this->toolbar_title[] = $this->l('Advanced custom number');
	}
    public function initBreadcrumbs($tab_id = null, $tabs = null)
    {
        parent::initBreadcrumbs($tab_id,$tabs);
        $this->display = 'view';
    }
    public function renderView(){
        $this->tpl_view_vars = array(
            'tabs'=> array(
                array('label'=>$this->l('Invoice Number'),'id'=>'cus_invoice','class'=>'tab_link','active'=>true),
                array('label'=>$this->l('Delivery Number'),'id'=>'cus_delivery','class'=>'tab_link'),
                array('label'=>$this->l('Order Reference'),'id'=>'cus_order','class'=>'tab_link')
            ),
            'contents'=>array(
                'cus_invoice'=>array(
                    'content'=>$this->renderFormTab('invoice',$this->l('Custom invoice number')),
                    'active'=>true
                ),
                'cus_delivery'=>array(
                    'content'=>$this->renderFormTab('delivery',$this->l('Custom delivery number'))),
                'cus_order'=>array(
                    'content'=>$this->renderFormTab('order',$this->l('Custom order reference'))),
            )
        );
        return parent::renderView();
    }
    public function postProcess()
	{
	   if (Tools::isSubmit('customunerajax')){
	       $action = Tools::getValue('action');
           $warrning = array();
           $results = array();
           switch ($action) {
                case 'addgrouprule':
                    // get customer group assign
                   $_type = Tools::getValue('type_rule');
                   $type = '';
                   $codition = 0;
                   switch ($_type) {
                        case 'customergroupconfig_delivery':
                            $type = 'D';
                            $codition = (int)Tools::getValue('customergroupconfig_delivery_order');
                            break;
                        case 'customergroupconfig_invoice':
                            $type = 'I';
                            $codition = (int)Tools::getValue('customergroupconfig_invoice_order');
                            break;
                        case 'customergroupconfig_order':
                            $type = 'O';
                            $codition = (int)Tools::getValue('customergroupconfig_order_order');
                            break;
                    }
                   $sql = 'SELECT groups FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` WHERE type = "'.pSql($type).'"';
                   $id_rule = (int)Tools::getValue('id_rule');
                   if($id_rule > 0) $sql.=' AND id_gwaicustomnumber !='.(int)$id_rule;
                   $shops = Shop::getContextListShopID();
                   foreach ($shops as $shop_id)
                   {
                        // check assign group customer by shop
                       $_sql = $sql.' AND id_shop = '.(int)$shop_id;
                       $groups = Db::getInstance()->executeS($_sql);
                       if($groups)
                        foreach($groups as $group){
                            $_groups = array();
                            if($group !='')
                                $_groups = array_map('intval', explode(',', $group['groups']));
                            if($_groups)
                                foreach($_groups as $_group)
                                    $groups[]= $_group;
                                 
                        }
                   }
                   $assign_group = Tools::getValue('groupBox_'.pSQL($_type));
                   $_assign_group = array();
                   if(is_array($assign_group))
                        $_assign_group = array_map('intval', $assign_group);
                   $assign_ok = true;
                   if($_assign_group){
                       foreach($_assign_group as $_assign){
                            if($assign_ok && in_array($_assign,$groups)){$assign_ok = false;$warrning[] = $this->l('Please check group customer assign.');} 
                       }
                   }
                   $start = (int)Tools::getValue('start');
                        if($start < 1) $warrning[] = $this->l('Start number invalid.');
                   $step = (int)Tools::getValue('step');
                        if($step < 1) $warrning[] = $this->l('Step number invalid.');
                   $length = (int)Tools::getValue('length');
                        if($step < 1) $warrning[] = $this->l('Length number invalid( < 2).');
                   $numberformat = Tools::getValue('numberformat');
                        if($numberformat == '') $warrning[] = $this->l('Number format invalid.');
                   $groups = implode(',',$_assign_group);
                        if($groups == '') $warrning[] = $this->l('Groups customer invalid.');
                   $resettype = (int)Tools::getValue($_type.'_reset');
                        if($resettype < 0 || $resettype > 5) $warrning[] = $this->l('Type reset invalid.');
                   $resetnumber = (int)Tools::getValue('resetnumber');
                        if($resetnumber < 0) $warrning[] = $this->l('Number reset invalid.');
                   if($resettype == 1 && $resetnumber == 0) $warrning[] = $this->l('Number reset invalid.');
                   $resetdate = Tools::getValue('resetdate');
                   $current = (int)Tools::getValue('current');
                   if($current < 0) $warrning[] = $this->l('Current counter number invalid.');
                   if($resettype !=5){
                        if(!Validate::isDate($resetdate)) $resetdate = NULL;
                        
                   }else{
                        if(!Validate::isDate($resetdate)) $warrning[] = $this->l('Date reset invalid.');
                   }
                   if($assign_ok && count($warrning) < 1){
                        foreach ($shops as $shop_id){
                            $sql = '';
                            if($id_rule <= 0){
                                if ($current == '') {
                                    $current = $start;
                                }
                                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'gwaicustomnumber`(type,start,step,length,numberformat,groups,resettype,resetnumber,resetdate,id_shop,current_counter_nbr)
                                        VALUES("'.pSql($type).'","'.(int)$start.'","'.(int)$step.'","'.(int)$length.'","'.pSql($numberformat).'","'.pSql($groups).'","'.(int)$resettype.'","'.(int)$resetnumber.'","'.pSql($resetdate).'","'.(int)$shop_id.'",'.(int)$current.')';
                                Db::getInstance()->execute($sql);
                                $id_rule = (int)Db::getInstance()->Insert_ID();
                            }else{
                                $sql = 'UPDATE `' . _DB_PREFIX_ . 'gwaicustomnumber` SET
                                    start = "'.(int)$start.'",
                                    step = "'.(int)$step.'",
                                    length = "'.(int)$length.'",
                                    numberformat = "'.pSql($numberformat).'",
                                    groups = "'.pSql($groups).'",
                                    resettype = "'.(int)$resettype.'",
                                    resetnumber = "'.(int)$resetnumber.'",
                                    resetdate = "'.pSql($resetdate).'" '.($codition == 1 ? ',current_counter_nbr = '.(int)$current : '').' 
                                    WHERE id_gwaicustomnumber = '.(int)$id_rule.' AND id_shop = '.(int)$shop_id;
                                Db::getInstance()->execute($sql);
                            }
                            
                        }
                        $warrning[] = $this->l('Add the format successful');
                        $groups_name = '';
                        if($groups !=''){
                            $allgroup = Group::getGroups((int)$this->context->language->id,(int)$this->context->shop->id);
                            $_groups = explode(',',$groups);
                            if($allgroup && $_groups){
                                $_groups_name = array();
                                foreach($allgroup as $group){
                                    if(in_array($group['id_group'],$_groups)){
                                        $_groups_name[(int)$group['id_group']] = $group['name'];
                                    }
                                }
                                $groups_name = implode(',',$_groups_name);
                            }
                        }
                        $results = array(
                            'id_rule' =>(int)$id_rule,
                            'start' =>(int)$start,
                            'step' =>(int)$step,
                            'length' =>(int)$length,
                            'type'=>$type,
                            'numberformat' =>pSql($numberformat),
                            'groups' =>pSql($groups),
                            'groups_name'=>pSql($groups_name),
                            'resettype' =>(int)$resettype,
                            'resetnumber' =>(int)$resetnumber,
                            'resetdate' => pSql($resetdate),
                            'current'=>(int)$current,
                            'success' =>$assign_ok,
                            'warrning'=>$warrning
                       );
                   }else{
                        $results = array(
                            'id_rule' =>(int)$id_rule,
                            'success' =>false,
                            'warrning'=>$warrning
                       );
                   }
                    break;
                case 'removerule':
                    $id_gwaicustomnumber = (int)Tools::getValue('id');
                    $type = Tools::getValue('type');
                    $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` WHERE id_gwaicustomnumber ='.(int)$id_gwaicustomnumber.' AND type="'.pSql($type).'"';
                    $shops = Shop::getContextListShopID();
                    foreach ($shops as $shop_id)
                    {
                        $_sql = $sql.' AND id_shop = '.(int)$shop_id;
                        Db::getInstance()->execute($_sql);
                    }
                    $results = array(
                        'success' =>true,
                        'warrning'=>$this->l('The format is removed')
                   );
                    break;
                case 'saveActive':
        	       $shops = Shop::getContextListShopID();
                   $CUS_INVOICE_ACTIVE = (bool)Tools::getValue('cus_invoice_active');
                   $CUS_DELIVERY_ACTIVE = (bool)Tools::getValue('cus_delivery_active');
                   $CUS_ORDER_ACTIVE = (bool)Tools::getValue('cus_order_active');
                   foreach ($shops as $shop_id)
                   {
                        Configuration::updateValue('CUS_INVOICE_ACTIVE',$CUS_INVOICE_ACTIVE,false,null,(int)$shop_id);
                        Configuration::updateValue('CUS_ORDER_ACTIVE',$CUS_ORDER_ACTIVE,false,null,(int)$shop_id);
                        Configuration::updateValue('CUS_DELIVERY_ACTIVE',$CUS_DELIVERY_ACTIVE,false,null,(int)$shop_id);
                    }
                    $results = array(
                        'success' =>true,
                        'warrning'=>$this->l('Update successfull')
                   );
                    break;
                case 'getnextnumber':
                   $_type = Tools::getValue('type_rule');
                   $type = '';
                   switch ($_type) {
                        case 'customergroupconfig_delivery':
                            $type = 'D';
                            break;
                        case 'customergroupconfig_invoice':
                            $type = 'I';
                            break;
                        case 'customergroupconfig_order':
                            $type = 'O';
                            break;
                    }
                    $numberformat = '';
                    $invoiceObj = Module::getInstanceByName('gwadvancedinvoice');
                    $numberconfig = $invoiceObj->customizeNumber($type,null,true);
                    if(is_array($numberconfig) && isset($numberconfig['next_number'])){
                        $numberformat = $invoiceObj->formatNumber($type,(int)$numberconfig['next_number'],null,true,$numberconfig);
                        $results = array(
                            'success' =>true,
                            'numberformat' =>$numberformat,
                            'warrning'=>''
                        );
                    }else{
                        $results = array(
                            'success' =>false,
                            'numberformat' =>'',
                            'warrning'=>$this->l('Has issue, Please check formart and try again.'),
                            'configs'=>$numberconfig
                        );
                    }
                    break;
           }
           die(Tools::jsonEncode($results));
	   }
        parent::postProcess();
	}
    public function getAllRule($key = 'invoice'){
        $type = '';
        if($key == 'order') $type = 'O';
        elseif($key == 'delivery') $type = 'D';
        elseif($key == 'invoice') $type = 'I';
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gwaicustomnumber` WHERE type="'.pSql($type).'" AND id_shop = '.(int)$this->context->shop->id;
        return Db::getInstance()->executeS($sql);
    }
    public function renderFormTab($tab='',$label=''){
        $this->fields_form = 
        array(
            'form' =>
                array(
        			'legend' => array(
        				'title' => $label,
        				'icon' => 'icon-list'
        			),
        			'input' => array(
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Active?'),
                            'name' => 'cus_'.$tab.'_active',
                            'required' => false,
                            'class' => 't',
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'cus_'.$tab.'_active_on',
                                    'value' => 1
                                ),
                                array(
                                    'id' => 'cus_'.$tab.'_active_off',
                                    'value' => 0
                                )
                            ),
                        ),
        				array(
        					'type' => 'customergroupconfig',
        					'label' => '',
        					'name' => 'customergroupconfig_'.$tab,
        				),
        			),
        			'submit' => array(
        				'title' => $this->l('Submit'),
        				'id' => 'submitCustomnumber',
        				'icon' => 'process-icon-save'
        			)
           )
		);
        $fields_value = array();
        $tpl_vars = array();
        $allgroup = Group::getGroups((int)$this->context->language->id,(int)$this->context->shop->id);
        $fields_value['cus_'.$tab.'_active'] = (bool)Configuration::get('CUS_'.Tools::strtoupper($tab).'_ACTIVE',null,null,(int)$this->context->shop->id);
        $allrule = $this->getAllRule($tab);
        
        $allgroupname = array();
        if($allgroup)
            foreach($allgroup as $rule){
                $allgroupname[(int)$rule['id_group']] = $rule['name'];
            }
        if($allrule)
            foreach($allrule as &$rule){
                $rule['groups_name'] = '';
                $_group = array();
                if($rule['groups'] !=''){
                    $groups = explode(',',$rule['groups']);
                    foreach($groups as $group){
                        if(isset($allgroupname[(int)$group]))
                            $_group[] = $allgroupname[(int)$group];
                    }
                    $rule['groups_name'] = implode(',',$_group);  
                }
            }
        $fields_value['allrule'] = $allrule;
        $fields_value['allgroup'] = $allgroup;
        return $this->renderGenericForm(array('form' => $this->fields_form), $fields_value, $tpl_vars);
        
  }
  public function renderGenericForm($fields_form, $fields_value, $tpl_vars = array())
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $helper->module =  $this->module;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->tpl_vars = array_merge(array(
                'fields_value' => $fields_value,
                'languages' => $this->getLanguages(),
                'id_language' => $this->context->language->id
            ), $tpl_vars);
        $helper->override_folder = '/gwaicustomnumber/';

        return $helper->generateForm($fields_form);
    }
}