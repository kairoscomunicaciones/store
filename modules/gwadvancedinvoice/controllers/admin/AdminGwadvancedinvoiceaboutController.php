<?php
/**
* The file is controller. Do not modify the file if you want to upgrade the module in future
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

class AdminGwadvancedinvoiceaboutController extends ModuleAdminController
{
	public function __construct()
	{
		$this->bootstrap = true;
		$this->display = 'view';
        parent::__construct();
		$this->meta_title = $this->l('Advanced Invoice Template Builder');
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}
    public function setMedia($isNewTheme = false)
	{
		return parent::setMedia($isNewTheme);
	}
    public function initToolBarTitle()
	{
		$this->toolbar_title[] = $this->l('Advanced Invoice Template Builder');
		$this->toolbar_title[] = $this->l('Document');
	}
    public function initPageHeaderToolbar()
	{
        $this->page_header_toolbar_btn = array(

            'cogs' => array(

                'href' => $this->context->link->getAdminLink('AdminGwadvancedinvoiceconfig'),

                'desc' => $this->l('General Settings'),

                'icon' => 'process-icon-cogs'

            ),
            'new' => array(

                'href' => $this->context->link->getAdminLink('AdminGwadvancedinvoicetemplate'),

                'desc' => $this->l('Manage Templates'),

                'icon' => 'process-icon-duplicate'

            ),

        );
		parent::initPageHeaderToolbar();
	}
    public function renderView()
	{
	  
	   if (version_compare(_PS_VERSION_, '1.5.6.0', '>'))
			$this->base_tpl_view = 'about.tpl';
		return parent::renderView();
	}
}
?>