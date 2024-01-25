<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  @version  Release: $Revision$
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;
class Ets_homecategories extends Module
{
    private $errorMessage;
    public $configs;
    public $baseAdminPath;
    private $_html;
    public $templates;
    public $is17 = false;
    public function __construct()
	{
		$this->name = 'ets_homecategories';
		$this->tab = 'front_office_features';
		$this->version = '1.0.2';
		$this->author = 'ETS-Soft';
        $this->module_key='fbff6e0c6d66cd58bc9898cafb7a420c';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);        
		$this->bootstrap = true;
        $this->is17 = version_compare(_PS_VERSION_, '1.7', '>=');
		parent::__construct();
        $this->displayName = $this->l('Home product categories PRO');
		$this->description = $this->l('Display products on home page in category tabs or category rows');
		$this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
        if(isset($this->context->controller->controller_type) && $this->context->controller->controller_type =='admin')
            $this->baseAdminPath = $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        
        //Config fields        
        $this->configs = array(                     
            'ETS_HOMECAT_TABS_GROUPED' => array(
                'label' => $this->l('Display type'),
                'type' => 'radio',
                'values' => array(
                    array(
                        'label' => $this->l('Group main categories into 1 product tab'),
                        'id' => 'ETS_HOMECAT_TABS_GROUPED_1',
                        'value' => 1,
                    ),
                    array(
                        'label' => $this->l('Display each main category on a line'),
                        'id' => 'ETS_HOMECAT_TABS_GROUPED_0',
                        'value' => 0,
                    )
                ),                
                'default' => 0,               
            ),
            'ETS_HOMECAT_INITIATE_FIRST_TAB_ONLY' => array(
                'label' => $this->l('Initiate the first products tab only'),
                'type' => 'switch',                
                'default' => 1,   
                'desc' => $this->l('Load your home page faster'),            
            ),
            'ETS_HOMECAT_DISPLAY_CATEGORY_BANNER' => array(
                'label' => $this->l('Display category banner'),                
                'type' => 'select',                                      
				'options' => array(
        			 'query' => array(  
                            array(
                                'id_option' => 'below', 
                                'name' => $this->l('Below product list')
                            ), 
                            array(
                                'id_option' => 'above', 
                                'name' => $this->l('Above product list')
                            ),
                            array(
                                'id_option' => 'inline', 
                                'name' => $this->l('In line width product list')
                            ),                          
                            array(
                                'id_option' => 'none', 
                                'name' => $this->l('Do not display category banner')
                            ),
                        ),                             
                     'id' => 'id_option',
        			 'name' => 'name'  
                ),    
                'default' => 'inline',           
            ),
            'ETS_HOMECAT_CATEGORIES' => array(
                'label' => $this->l('Display categories'),
                'type' => 'categories',
                'tree'  => array(
					'id'      => 'categories-tree',
					'selected_categories' => Tools::isSubmit('ETS_HOMECAT_CATEGORIES') ? Tools::getValue('ETS_HOMECAT_CATEGORIES') : explode(',',Configuration::get('ETS_HOMECAT_CATEGORIES')),
					'disabled_categories' => null,
                    'use_checkbox' => true,
                    'use_search' => true,
                    'root_category' => 2
				),
            ),
            'ETS_HOMECAT_SORT_CATEGORIES_BY' => array(
                'label' => $this->l('Sort categories by'),                
                'type' => 'select',                                      
				'options' => array(
        			 'query' => array(  
                            array(
                                'id_option' => 'c.level_depth asc, cs.position asc', 
                                'name' => $this->l('Default (category position)')
                            ),                          
                            array(
                                'id_option' => 'cl.name asc', 
                                'name' => $this->l('Category name')
                            ),
                        ),                             
                     'id' => 'id_option',
        			 'name' => 'name'  
                ),    
                'default' => 'c.level_depth asc, cs.position asc',           
            ),
            'ETS_HOMECAT_INCLUDE_SUB' => array(
                'label' => $this->l('Include products in all sub categories'),
                'type' => 'switch',                
                'default' => 1,               
            ),
            'ETS_HOMECAT_DISPLAY_SUB' => array(
                'label' => $this->l('Display sub category tabs'),
                'type' => 'switch',                
                'default' => 1,               
            ),
            'ETS_HOMECAT_ENBLE_CAROUSEL' => array(
                'label' => $this->l('Enable carousel slider'),
                'type' => 'switch',                
                'default' => 1,            
            ), 
            'ETS_HOMECAT_ENBLE_LOAD_MORE' => array(
                'label' => $this->l('Enable "Load more products" button'),
                'type' => 'switch',                
                'default' => 0,            
            ),
            'ETS_HOMECAT_ENBLE_ALL_PRODUCT_TAB' => array(
                'label' => $this->l('Enable "All products" tab'),
                'type' => 'switch',                
                'default' => 1,            
            ),  
            'ETS_HOMECAT_PER_PAGE' => array(
                'label' => $this->l('Product count'),
                'type' => 'text',
                'default' => 12,  
                'required' => true,  
                'desc' => $this->l('The number of products to display per ajax load'),
                'validate' => 'isInt',                       
            ), 
            'ETS_HOMECAT_SORT_PRODUCTS_BY' => array(
                'label' => $this->l('Default product sort by'),                
                'type' => 'select',                                      
				'options' => array(
        			 'query' => array(  
                            array(
                                'id_option' => 'cp.position asc', 
                                'name' => $this->l('Popularity')
                            ), 
                            array(
                                'id_option' => 'rand', 
                                'name' => $this->l('Random products')
                            ),                          
                            array(
                                'id_option' => 'pl.name asc', 
                                'name' => $this->l('Product name: A-Z')
                            ),
                            array(
                                'id_option' => 'pl.name desc', 
                                'name' => $this->l('Product name: Z-A')
                            ),
                            array(
                                'id_option' => 'orderprice asc', 
                                'name' => $this->l('Price: Lowest first')
                            ),
                            array(
                                'id_option' => 'orderprice desc', 
                                'name' => $this->l('Price: Highest first')
                            ),
                            array(
                                'id_option' => 'p.id_product desc', 
                                'name' => $this->l('Newest items first')
                            ),
                        ),                             
                     'id' => 'id_option',
        			 'name' => 'name'  
                ),    
                'default' => 'cp.position asc',           
            ),
            'ETS_HOMECAT_ALLOW_SORT' => array(
                'label' => $this->l('Enable "Sort by" feature on the front page'),
                'type' => 'switch',                
                'default' => 0,            
            ),             
            'ETS_HOMECAT_OPEN_CAT_BY_LINK' => array(
                'label' => $this->l('Open category by link'),
                'type' => 'switch',                
                'default' => 0,            
            ), 
            'ETS_HOMECAT_LOADING_ENABLED' => array(
                'label' => $this->l('Enable loading icon'),
                'type' => 'switch',                
                'default' => 1,            
            ),            
        );
        if(Tools::isSubmit('homecateajax')&& Tools::isSubmit('id_category'))
         {
            $id_category = Tools::getValue('id_category')!='all' && (int)Tools::getValue('id_category') >= 0 ? (int)Tools::getValue('id_category') : 0;
            die(Tools::jsonEncode(array(
                'html' => $this->hookDisplayProductList(array('id_category' => $id_category, 'wrapper' => true)),
                'id_category' => $id_category ? $id_category : 'all',
                'id_parent' => Tools::getValue('id_parent')!='all' && (int)Tools::getValue('id_parent') ? (int)Tools::getValue('id_parent') : 'all',
            )));
         }
         if(Tools::isSubmit('homecateajaxsort')&&Tools::isSubmit('id_category'))
         {            
            if(Configuration::get('ETS_HOMECAT_ALLOW_SORT') && ($sortBy = Tools::strtolower(trim(Tools::getValue('sort_by')))))
            {                
                if($sortBy && !in_array($sortBy, array('orderprice asc','orderprice desc','pl.name asc','pl.name desc','cp.position asc','p.id desc','rand')))
                    $sortBy = 'cp.position asc';
                if($sortBy)
                {
                  $this->context->cookie->ets_homecat_order = $sortBy;
                  $this->context->cookie->write();
                }
                $id_category = Tools::getValue('id_category')!='all' && (int)Tools::getValue('id_category') >= 0 ? (int)Tools::getValue('id_category') : 0;
                die(Tools::jsonEncode(array(
                    'html' => $this->hookDisplayProductList(array('id_category' => $id_category, 'wrapper' => true)),
                    'id_category' => $id_category ? $id_category : 'all',
                    'id_parent' => Tools::getValue('id_parent')!='all' && (int)Tools::getValue('id_parent') ? (int)Tools::getValue('id_parent') : 'all',
                )));
            }
            else
            {
                die(Tools::jsonEncode(array('error' => 1)));
            }
         } 
         if(Tools::isSubmit('homecateajaxloadmore')&& Tools::isSubmit('id_category'))
         {
            $id_category = Tools::getValue('id_category')!='all' && (int)Tools::getValue('id_category') >= 0 ? (int)Tools::getValue('id_category') : 0;
            $productCount = (int)Tools::getValue('product_count') > 0 ? (int)Tools::getValue('product_count') : 0;
            $arg = $this->hookDisplayProductList(array('id_category' => $id_category, 'wrapper' => false,'returnArg' => true,'productCount' => $productCount));
            die(Tools::jsonEncode(array(
                'html' => $arg['html'] ? $arg['html'] : false,
                'nomore' => $arg['nomore'],
                'hasProducts' => isset($arg['hasProducts']) && $arg['hasProducts'] ? true : false,
                'productCount' => $arg['productCount'],
                'id_category' => $id_category ? $id_category : 'all',
                'id_parent' => Tools::getValue('id_parent')!='all' && (int)Tools::getValue('id_parent') ? (int)Tools::getValue('id_parent') : 'all',
            )));
         }  
     }
    /**
	 * @see Module::install()
	 */
    public function install()
	{
	    return parent::install()        
        && $this->registerHook('displayHeader')        
        && $this->registerHook('displayHome')
        && $this->registerHook('displaySubCategories')
        && $this->registerHook('displayProductList')
        && $this->registerHook('displayCategoryBanner')
        && $this->_installDb();        
    }
    /**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
        return parent::uninstall() && $this->_uninstallDb();
    }    
    public function _installDb()
    {
        $languages = Language::getLanguages(false);
        if($this->configs)
        {
            foreach($this->configs as $key => $config)
            {
                if(isset($config['lang']) && $config['lang'])
                {
                    $values = array();
                    foreach($languages as $lang)
                    {
                        $values[$lang['id_lang']] = isset($config['default']) ? $config['default'] : '';
                    }
                    Configuration::updateValue($key, $values,true);
                }
                else
                    Configuration::updateValue($key, isset($config['default']) ? $config['default'] : '',true);
            }
        }        
        return true;
    }    
    private function _uninstallDb()
    {
        if($this->configs)
        {
            foreach($this->configs as $key => $config)
            {
                Configuration::deleteByName($key);
            }
            unset($config);
        } 
        $dirs = array('config');
        foreach($dirs as $dir)
        {
            $files = glob(dirname(__FILE__).'/views/img/'.$dir.'/*'); 
            foreach($files as $file){ 
              if(is_file($file) && $file != dirname(__FILE__).'/views/img/'.$dir.'/index.php')
                @unlink($file); 
            }
        }       
        return true;
    }    
    public function getContent()
	{	   
	   $this->_postConfig();   
       $this->_html .= $this->displayHomeCatAdmin();    
       //Display errors if have
       if($this->errorMessage)
            $this->_html .= $this->errorMessage;       
       //Render views
       $this->renderConfig(); 
       return $this->_html.$this->displayIframe();
    }
    public function displayIframe()
    {
        switch($this->context->language->iso_code) {
          case 'en':
            $url = 'https://cdn.prestahero.com/prestahero-product-feed?utm_source=feed_'.$this->name.'&utm_medium=iframe';
            break;
          case 'it':
            $url = 'https://cdn.prestahero.com/it/prestahero-product-feed?utm_source=feed_'.$this->name.'&utm_medium=iframe';
            break;
          case 'fr':
            $url = 'https://cdn.prestahero.com/fr/prestahero-product-feed?utm_source=feed_'.$this->name.'&utm_medium=iframe';
            break;
          case 'es':
            $url = 'https://cdn.prestahero.com/es/prestahero-product-feed?utm_source=feed_'.$this->name.'&utm_medium=iframe';
            break;
          default:
            $url = 'https://cdn.prestahero.com/prestahero-product-feed?utm_source=feed_'.$this->name.'&utm_medium=iframe';
        }
        $this->smarty->assign(
            array(
                'url_iframe' => $url
            )
        );
        return $this->display(__FILE__,'iframe.tpl');
    } 
    public function renderConfig()
    {
        $configs = $this->configs;
        $fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Home product categories configuration'),
					'icon' => 'icon-AdminAdmin'
				),
				'input' => array(),
                'submit' => array(
					'title' => $this->l('Save'),
				)
            ),
		);
        if($configs)
        {
            foreach($configs as $key => $config)
            {
                $confFields = array(
                    'name' => $key,
                    'type' => $config['type'],
                    'label' => $config['label'],
                    'desc' => isset($config['desc']) ? $config['desc'] : false,
                    'required' => isset($config['required']) && $config['required'] ? true : false,
                    'autoload_rte' => isset($config['autoload_rte']) && $config['autoload_rte'] ? true : false,
                    'options' => isset($config['options']) && $config['options'] ? $config['options'] : array(),
                    'suffix' => isset($config['suffix']) && $config['suffix'] ? $config['suffix']  : false,
                    'values' => isset($config['values']) ? $config['values'] : array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
                    'lang' => isset($config['lang']) ? $config['lang'] : false
                );
                if(isset($config['tree']))
                    $confFields['tree'] = $config['tree'];
                if(!$confFields['suffix'])
                    unset($confFields['suffix']);
                if($config['type'] == 'file')
                {
                    if($imageName = Configuration::get($key))
                    {
                        $confFields['display_img'] = $this->_path.'images/config/'.$imageName;
                        if(!isset($config['required']) || (isset($config['required']) && !$config['required']))
                            $confFields['img_del_link'] = $this->baseAdminPath.'&delimage=yes&image='.$key; 
                    }
                }
                $fields_form['form']['input'][] = $confFields;
            }
        }        
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'saveConfig';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&control=config';
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));        
        $fields = array();        
        $languages = Language::getLanguages(false);
        $helper->override_folder = '/';
        if(Tools::isSubmit('saveConfig'))
        {            
            if($configs)
            {                
                foreach($configs as $key => $config)
                {
                    if(isset($config['lang']) && $config['lang'])
                        {                        
                            foreach($languages as $l)
                            {
                                $fields[$key][$l['id_lang']] = Tools::getValue($key.'_'.$l['id_lang'],isset($config['default']) ? $config['default'] : '');
                            }
                        }
                        else
                            $fields[$key] = Tools::getValue($key,isset($config['default']) ? $config['default'] : '');
                }
            }
        }
        else
        {
            if($configs)
            {
                    foreach($configs as $key => $config)
                    {
                        if(isset($config['lang']) && $config['lang'])
                        {                    
                            foreach($languages as $l)
                            {
                                $fields[$key][$l['id_lang']] = Configuration::get($key,$l['id_lang']);
                            }
                        }
                        else
                            $fields[$key] = Configuration::get($key);                   
                    }
            }
        }
        $helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $fields,
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,                     
        );
        
        $this->_html .= $helper->generateForm(array($fields_form));		
     }
     private function _postConfig()
     {
        $errors = array();
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $configs = $this->configs;        
        //Delete image
        if(Tools::isSubmit('delimage'))
        {
            $image = Tools::getValue('image');
            if(isset($configs[$image]) && !isset($configs[$image]['required']) || (isset($configs[$image]['required']) && !$configs[$image]['required']))
            {
                $imageName = Configuration::get($image);
                $imagePath = dirname(__FILE__).'/images/config/'.$imageName;
                if($imageName && file_exists($imagePath))
                {
                    @unlink($imagePath);
                    Configuration::updateValue($image,'');
                }
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=4&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
            }
            else
                $errors[] = $configs[$image]['label'].$this->l(' is required');
        }
        if(Tools::isSubmit('saveConfig'))
        {            
            if($configs)
            {
                foreach($configs as $key => $config)
                {
                    if(isset($config['lang']) && $config['lang'])
                    {
                        if(isset($config['required']) && $config['required'] && $config['type']!='switch' && trim(Tools::getValue($key.'_'.$id_lang_default) == ''))
                        {
                            $errors[] = $config['label'].' '.$this->l('is required');
                        }                        
                    }
                    else
                    {
                        if(isset($config['required']) && $config['required'] && isset($config['type']) && $config['type']=='file')
                        {
                            if(Configuration::get($key)=='' && !isset($_FILES[$key]['size']))
                                $errors[] = $config['label'].' '.$this->l('is required');
                            elseif(isset($_FILES[$key]['size']))
                            {
                                $fileSize = round((int)$_FILES[$key]['size'] / (1024 * 1024));
                    			if($fileSize > 100)
                                    $errors[] = $config['label'].$this->l(' can not be larger than 100Mb');
                            }   
                        }
                        else
                        {
                            if(isset($config['required']) && $config['required'] && $config['type']!='switch' && trim(Tools::getValue($key) == ''))
                            {
                                $errors[] = $config['label'].' '.$this->l('is required');
                            }
                            elseif(isset($config['validate']) && method_exists('Validate',$config['validate']))
                            {
                                $validate = $config['validate'];
                                if(!Validate::$validate(trim(Tools::getValue($key))))
                                    $errors[] = $config['label'].' '.$this->l('is invalid');
                                unset($validate);
                            }
                            elseif(!is_array(Tools::getValue($key)) && !Validate::isCleanHtml(trim(Tools::getValue($key))))
                            {
                                $errors[] = $config['label'].' '.$this->l('is invalid');
                            } 
                        }                          
                    }                    
                }
            }            
            
            //Custom validation
            
            if(!$errors)
            {
                if($configs)
                {
                    foreach($configs as $key => $config)
                    {
                        if(isset($config['lang']) && $config['lang'])
                        {
                            $valules = array();
                            foreach($languages as $lang)
                            {
                                if($config['type']=='switch')                                                           
                                    $valules[$lang['id_lang']] = (int)trim(Tools::getValue($key.'_'.$lang['id_lang'])) ? 1 : 0;                                
                                else
                                    $valules[$lang['id_lang']] = trim(Tools::getValue($key.'_'.$lang['id_lang'])) ? trim(Tools::getValue($key.'_'.$lang['id_lang'])) : trim(Tools::getValue($key.'_'.$id_lang_default));
                            }
                            Configuration::updateValue($key,$valules,true);
                        }
                        else
                        {
                            if($config['type']=='switch')
                            {                           
                                Configuration::updateValue($key,(int)trim(Tools::getValue($key)) ? 1 : 0,true);
                            }
                            if($config['type']=='file')
                            {
                                //Upload file
                                if(isset($_FILES[$key]['tmp_name']) && isset($_FILES[$key]['name']) && $_FILES[$key]['name'])
                                {
                                    $salt = sha1(microtime());
                                    $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$key]['name'], '.'), 1));
                                    $imageName = $salt.'.'.$type;
                                    $fileName = dirname(__FILE__).'/images/config/'.$imageName;                
                                    if(file_exists($fileName))
                                    {
                                        $errors[] = $config['label'].$this->l(' already exists. Try to rename the file then reupload');
                                    }
                                    else
                                    {
                                        
                            			$imagesize = @getimagesize($_FILES[$key]['tmp_name']);
                                        
                                        if (!$errors && isset($_FILES[$key]) &&				
                            				!empty($_FILES[$key]['tmp_name']) &&
                            				!empty($imagesize) &&
                            				in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
                            			)
                            			{
                            				$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');    				
                            				if ($error = ImageManager::validateUpload($_FILES[$key]))
                            					$errors[] = $error;
                            				elseif (!$temp_name || !move_uploaded_file($_FILES[$key]['tmp_name'], $temp_name))
                            					$errors[] = $this->l('Can not upload the file');
                            				elseif (!ImageManager::resize($temp_name, $fileName, null, null, $type))
                            					$errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
                            				if (isset($temp_name))
                            					@unlink($temp_name);
                                            if(!$errors)
                                            {
                                                if(Configuration::get($key)!='')
                                                {
                                                    $oldImage = dirname(__FILE__).'/images/config/'.Configuration::get($key);
                                                    if(file_exists($oldImage))
                                                        @unlink($oldImage);
                                                }                                                
                                                Configuration::updateValue($key, $imageName,true);                                                                                               
                                            }
                                        }
                                    }
                                }
                                //End upload file
                            }
                            elseif($config['type']=='categories')
                                Configuration::updateValue($key,implode(',',Tools::getValue($key)),true); 
                            else
                                Configuration::updateValue($key,trim(Tools::getValue($key)),true);   
                        }                        
                    }
                }                
            }
            if (count($errors))
            {
               $this->errorMessage = $this->displayError($errors);  
            }
            else
               Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=4&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);            
        }
     }     
     public function getTreeIds($id_root)
     {
        $ids = array();
        $ids[] = $id_root;
        if($children = $this->getChildren($id_root))
            foreach($children as $child)
            {
                $ids = array_merge($ids,$this->getTreeIds($child['id_category']));
            }
        return array_unique($ids);
     }     
     public function getCategory($id_category)
     {
        return Db::getInstance()->getRow("
            SELECT c.id_category, cl.name 
            FROM "._DB_PREFIX_."category c
            LEFT JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=".(int)$this->context->language->id."
            WHERE c.active=1 AND c.id_category=".(int)$id_category."
        ");
     }
     public function getChildren($id_category)
     {
        $orderBy = Configuration::get('ETS_HOMECAT_SORT_CATEGORIES_BY');        
            if(!in_array($orderBy,array('c.level_depth asc, cs.position asc','cl.name asc')))
                $orderBy = 'cl.name asc';
        return Db::getInstance()->executeS("
            SELECT c.id_category, cl.name 
            FROM "._DB_PREFIX_."category c
            LEFT JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=".(int)$this->context->language->id."
            INNER JOIN "._DB_PREFIX_."category_shop cs ON cs.id_category=c.id_category AND cs.id_shop=".(int)$this->context->shop->id."
            WHERE c.active=1 AND c.id_parent=".(int)$id_category."
            ORDER BY ".pSQL($orderBy)."
        ");
     }
     public function getProducts($id_category = false, $from = 0, $to = 12, $order_by = 'cp.position')
     {        
            $from = (int)$from;
            if($from < 0)
                $from = 0;
            $to = (int)$to;
            if($to < 0)
                $to = 0;
            $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
            $id_lang = (int)$this->context->language->id;
            if (!Validate::isUnsignedInt($nb_days_new_product)) {
                $nb_days_new_product = 20;
            }
            
            if($order_by && !in_array($order_by, array('orderprice asc','orderprice desc','pl.name asc','pl.name desc','cp.position asc','p.id_product desc','rand')))
                $order_by = 'cp.position asc';
            if((int)Tools::getValue('ets_homecat_order_seed') > 0 && (int)Tools::getValue('ets_homecat_order_seed') <= 10000)
                $randSeed = (int)Tools::getValue('ets_homecat_order_seed');
            elseif((int)$this->context->cookie->ets_homecat_order_seed > 0 && (int)$this->context->cookie->ets_homecat_order_seed <= 10000)
                $randSeed = (int)$this->context->cookie->ets_homecat_order_seed;
            else
                $randSeed = 1;  
            $active = true;
            $front = true;
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity'.(Combination::isFeatureActive() ? ', IFNULL(product_attribute_shop.id_product_attribute, 0) AS id_product_attribute,
    					product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity' : '').', pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image` id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
    					INTERVAL '.(int)$nb_days_new_product.' DAY)) > 0 AS new, product_shop.price AS orderprice
    				FROM `'._DB_PREFIX_.'category_product` cp
    				LEFT JOIN `'._DB_PREFIX_.'product` p
    					ON p.`id_product` = cp.`id_product`
    				'.Shop::addSqlAssociation('product', 'p').
                    (Combination::isFeatureActive() ? ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
    				ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')':'').'
    				'.Product::sqlStock('p', 0).'
    				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
    					ON (product_shop.`id_category_default` = cl.`id_category`
    					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
    				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
    					ON (p.`id_product` = pl.`id_product`
    					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
    				LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
    					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$this->context->shop->id.')
    				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
    					ON (image_shop.`id_image` = il.`id_image`
    					AND il.`id_lang` = '.(int)$id_lang.')
    				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
    					ON m.`id_manufacturer` = p.`id_manufacturer`
    				WHERE product_shop.`id_shop` = '.(int)$this->context->shop->id.'
    					'.($id_category ? ' AND '.(!Configuration::get('ETS_HOMECAT_INCLUDE_SUB') ? 'cp.`id_category` = '.(int)$id_category : 'cp.`id_category` IN('.implode(',',$this->getTreeIds((int)$id_category)).')') : '')
                        .($active ? ' AND product_shop.`active` = 1' : '')
                        .($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                        .' GROUP BY p.id_product'
                        .($order_by ? ( $order_by !='rand' ? ' ORDER BY '.pSQL($order_by) : ' ORDER BY RAND('.(Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_ENBLE_CAROUSEL') ? (int)$randSeed : '').')') : '').'
    			     LIMIT '.(int)$from.','.(int)$to;
            //die($sql);            
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
    
            if (!$result) {
                return array();
            }
    
            if ($order_by == 'orderprice asc') {
                Tools::orderbyPrice($result, 'asc');
            }            
            elseif ($order_by == 'orderprice desc') {
                Tools::orderbyPrice($result, 'desc');
            }            
            /** Modify SQL result */
            $products = Product::getProductsProperties($id_lang, $result);
            if($this->is17 && $products)
            {
                $assembler = new ProductAssembler($this->context);

                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
                    new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                        $this->context->link
                    ),
                    $this->context->link,
                    new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
                    new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
                    $this->context->getTranslator()
                );
        
                $products_for_template = array();
                
                foreach ($products as $rawProduct) {
                    $products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct($rawProduct),
                        $this->context->language
                    );
                }                
                return $products_for_template;
            }
            return $products;
     }  
     public function getConfiguredCategory()
     {
        if(($idStr = Configuration::get('ETS_HOMECAT_CATEGORIES')) && ($categoryIds = array_unique(explode(',',$idStr))))
        {            
            $orderBy = Configuration::get('ETS_HOMECAT_SORT_CATEGORIES_BY');
            if(!in_array($orderBy,array('c.level_depth asc, cs.position asc','cl.name asc')))
                $orderBy = 'cl.name asc';            
            return Db::getInstance()->executeS("
                SELECT c.id_category, cl.name 
                FROM "._DB_PREFIX_."category c
                LEFT JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=".(int)$this->context->language->id."
                INNER JOIN "._DB_PREFIX_."category_shop cs ON cs.id_category=c.id_category AND cs.id_shop=".(int)$this->context->shop->id."
                WHERE c.active=1 AND c.id_category IN(".implode(',',$categoryIds).")
                ORDER BY ".pSQL($orderBy)."
            ");            
        }
        return array();
     }   
     public function hookDisplayHeader()
     {        
        $this->context->controller->addCSS($this->_path.'views/css/homecategories.css','all');
        if($this->is17)
            $this->context->controller->addCSS($this->_path.'views/css/fix17.css','all');            
        else
        {
            $this->context->controller->addCSS($this->_path.'views/css/fix16.css','all');
            if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index')
			     $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
        }             
        if(Configuration::get('ETS_HOMECAT_ENBLE_CAROUSEL'))
        {
            //$this->context->controller->addCSS($this->_path.'views/css/owl.carousel.css','all'); 
            //$this->context->controller->addCSS($this->_path.'views/css/owl.theme.default.css','all');
            //$this->context->controller->addJS($this->_path.'views/js/owl.carousel.js');
        }              
        if(Configuration::get('ETS_HOMECAT_TABS_GROUPED'))
            $this->context->controller->addJS($this->_path.'views/js/homecat-grouped.js');
        else
            $this->context->controller->addJS($this->_path.'views/js/homecat-list.js');     
     }
     public function assignConfig()
     {
        $configs = array();
        foreach($this->configs as $key => $val)
        {
            $configs[$key] = !isset($val['lang']) || isset($val['lang']) && !$val['lang'] ? Configuration::get($key) : Configuration::get($key,$this->context->language->id);
        }        
        unset($val);        
        $this->smarty->assign($configs);
     }
     public function hookDisplayHome()
     {
          if(Configuration::get('ETS_HOMECAT_ALLOW_SORT') && (!isset($this->context->cookie->ets_homecat_order) || isset($this->context->cookie->ets_homecat_order) && !$this->context->cookie->ets_homecat_order))
          {
              $this->context->cookie->ets_homecat_order = Configuration::get('ETS_HOMECAT_SORT_PRODUCTS_BY');
              $this->context->cookie->write();
          }          
          $configuredCategories = $this->getConfiguredCategory();
          $sortBy = $this->context->cookie->ets_homecat_order && Configuration::get('ETS_HOMECAT_ALLOW_SORT') ? $this->context->cookie->ets_homecat_order : Configuration::get('ETS_HOMECAT_SORT_PRODUCTS_BY');
          $this->context->cookie->ets_homecat_order_seed = rand(1,10000);
          $this->context->cookie->write();
          $assign = array(
             'categories' => $configuredCategories,
             'homecat_ajax_link' => $this->context->link->getModuleLink('ets_homecategories', 'ajax',array(),true),
             'sort_options' => Configuration::get('ETS_HOMECAT_ALLOW_SORT') ? $this->configs['ETS_HOMECAT_SORT_PRODUCTS_BY']['options']['query'] : false,
             'sort_by' => $sortBy,    
             'link' => $this->context->link,
             'ets_homecat_order_seed' => (int)$this->context->cookie->ets_homecat_order_seed,
             'ETS_HOMECAT_ENBLE_CAROUSEL' => (int)Configuration::get('ETS_HOMECAT_ENBLE_CAROUSEL'), 
             'ETS_HOMECAT_TABS_GROUPED' => Configuration::get('ETS_HOMECAT_TABS_GROUPED') ? true : false, 
             'ETS_HOMECAT_LOADING_ENABLED' => Configuration::get('ETS_HOMECAT_LOADING_ENABLED') ? true : false,                
          );
          if($this->is17)
            $assign['base_dir'] = $this->context->link->getPageLink('index', true);
          $this->smarty->assign($assign);
          $this->assignConfig();
          return Configuration::get('ETS_HOMECAT_TABS_GROUPED') ? $this->display(__FILE__, 'homecat-grouped.tpl') : $this->display(__FILE__, 'homecat-list.tpl');
     }
     public function hookDisplayProductList($params)
     {    
          $id_category = isset($params['id_category']) && $params['id_category']!='all' ? (int)$params['id_category'] : 0;
          if(Configuration::get('ETS_HOMECAT_ALLOW_SORT') && Tools::getValue('ets_homecat_order'))
            $sortBy = Tools::getValue('ets_homecat_order');
          else
            $sortBy = Configuration::get('ETS_HOMECAT_ALLOW_SORT') && $this->context->cookie->ets_homecat_order ? $this->context->cookie->ets_homecat_order : Configuration::get('ETS_HOMECAT_SORT_PRODUCTS_BY');
          $from = isset($params['productCount']) && (int)$params['productCount'] >= 1 ? (int)$params['productCount'] : 0;
          $perpage = (int)Configuration::get('ETS_HOMECAT_PER_PAGE') > 0 ? (int)Configuration::get('ETS_HOMECAT_PER_PAGE') : 12;
          
          if(!$id_category)
            $products = $this->getProducts(false,$from,(int)Configuration::get('ETS_HOMECAT_PER_PAGE'),$sortBy);
          else
            $products = $this->getProducts($id_category,$from,(int)Configuration::get('ETS_HOMECAT_PER_PAGE'),$sortBy);
                               
          $this->smarty->assign(array(
            'products' => $products,
            'allProductsLink' => true,
            'class' => 'homecat-tab-'.($id_category ? $id_category : 'all'),
            'homeSize' => Image::getSize($this->is17 ? ImageType::getFormattedName('home') : ImageType::getFormatedName('home')),
            'wrapper' => isset($params['wrapper']) && $params['wrapper'],
            'ETS_HOMECAT_PER_PAGE' => $perpage,
            'ETS_HOMECAT_ENBLE_LOAD_MORE' => Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') && !(int)Configuration::get('ETS_HOMECAT_ENBLE_CAROUSEL'),
            'id_category' => $id_category ? (int)$id_category : 'all',     
          ));
          if(!isset($params['returnArg']))
            return $this->display(__FILE__, 'product-list'.($this->is17 ? '-17' : '').'.tpl');
          else
            return array(
                'html' => $this->display(__FILE__, 'product-list'.($this->is17 ? '-17' : '').'.tpl'),
                'productCount' => $products ? $from + count($products) : $from,
                'nomore' => !$products || $products && count($products) < (int)Configuration::get('ETS_HOMECAT_PER_PAGE'),
                'hasProducts' => $products ? true : false,
            );
     }
     public function displayHomeCatAdmin()
     {
          $this->smarty->assign(array(
             'admin_js_url' => $this->_path.'views/js/homecat-admin.js',
          ));
          return $this->display(__FILE__, 'admin.tpl'); 
     }     
     public function hookDisplaySubCategories($params)
     {                            
          if(isset($params['id_category']) && $params['id_category'])
          {
              $this->smarty->assign(array(
                 'children' => $this->getChildren((int)$params['id_category']),                 
                 'ETS_HOMECAT_TABS_GROUPED' => Configuration::get('ETS_HOMECAT_TABS_GROUPED') ? true : false,  
              ));
              return $this->display(__FILE__, 'sub-categories.tpl'); 
          }          
     }
     public function hookDisplayCategoryBanner($params)
     {        
          if(Configuration::get('ETS_HOMECAT_DISPLAY_CATEGORY_BANNER')=='none' || Configuration::get('ETS_HOMECAT_TABS_GROUPED'))
            return;
          $id_category = (int)$params['id_category'];                   
          $this->smarty->assign(array(
             'category' => new Category($id_category,$this->context->language->id),
             'link' => $this->context->link,                 
          ));
          return $this->display(__FILE__, 'category-banner.tpl');          
     }
}