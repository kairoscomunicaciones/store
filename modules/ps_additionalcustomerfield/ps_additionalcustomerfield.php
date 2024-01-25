<?php
use PrestaShop\PrestaShop\Core\Domain\Customer\Exception\CustomerException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Ps_AdditionalCustomerfield extends Module
{
    public function __construct()
    {
        $this->name = 'ps_additionalcustomerfield';
        $this->author = 'Jeevan';
        $this->version = '1.0.0';
        $this->need_instance= 0;
        $this->bootstrap = true;
        $this->tab = 'others';
        parent::__construct();

        $this->displayName = $this->l('Addtional customer fields');
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->description = $this->l('Add extra customer fields');
    }

    /**
     * Install module
     *
     * @return bool true if success
     */
    public function install()
    {
	 return parent::install() &&
            $this->registerHook('actionCustomerFormBuilderModifier') &&
            $this->registerHook('actionAfterCreateCustomerFormHandler') &&
            $this->registerHook('actionAfterUpdateCustomerFormHandler') &&
    //        $this->registerHook('actionCustomerAccountAdd') &&
      //b      $this->registerHook('validateCustomerFormFields') &&
	    $this->registerHook('additionalCustomerFormFields') &&
	 //   $this->registerHook('actionAuthentication') &&
            $this->alterCustomerTable()
        ;
    }
    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallAlterCustomerTable();
    }
    private function uninstallAlterCustomerTable()
    {
        return true;
    }
    public function hookActionAfterUpdateCustomerFormHandler(array $params)
    {
        $this->updateCustomerDatas($params);
    }
    public function hookActionAfterCreateCustomerFormHandler(array $params)
    {
        $this->updateCustomerDatas($params);
    }
    private function updateCustomerDatas(array $params)
    {
        $customerId = (int)$params['id'];
        /** @var array $customerFormData */
        $customerFormData = $params['form_data'];
        $telephone = $customerFormData['telephone'];
        $validator = $customerFormData['validator'];
        try {

            $customer = new Customer($customerId);
            $customer->telephone = $telephone;
            $customer->validator = $validator;
            $customer->update();

        } catch (ReviewerException $exception) {
            throw new \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException($exception);
        }
    }
    public function hookActionCustomerFormBuilderModifier(array $params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder->add('telephone', TextType::class, [
            'label' => $this->getTranslator()->trans('Teléfono móvil', [], 'Modules.ps_additionalcustomerfield.Admin'),
            'required' => false,
        ]);
        $formBuilder->add('validator', TextType::class, [
            'label' => $this->getTranslator()->trans('Validator', [], 'Modules.ps_additionalcustomerfield.Admin'),
            'required' => false,
        ]);
        
        $customer = new Customer($params['id']);
        $params['data']['telephone'] = $customer->telephone;
        $params['data']['validator'] = $customer->validator;
        
        $formBuilder->setData($params['data']);

    } 
    protected function alterCustomerTable()
    {
        Db::getInstance()->execute('ALTER TABLE `'. _DB_PREFIX_.'customer` ADD `telephone` text,ADD `validator` text  NULL');
        return true;
    }
    protected function readModuleValues()
    {
        $id_customer = Context::getContext()->customer->id;
        $query = 'SELECT c.`telephone`'
            .' FROM `'. _DB_PREFIX_.'customer` c '
            .' WHERE c.id_customer = '.(int)$id_customer;
        return  Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    }
    public function hookAdditionalCustomerFormFields($params)
    {
        $module_fields = $this->readModuleValues();

        $telephone = Tools::getValue('telephone');
        $validator = Tools::getValue('validator');
        if (isset($module_fields['telephone'])) {
            $telephone = $module_fields['telephone'];
        }
	 $sql ='select * from type_user';
        $results =Db::getInstance()->executes($sql);
        $extra_fields = array();
        $extra_fields['telephone'] = (new FormField)
            ->setName('telephone')
            ->setType('text')
            ->setRequired('true')
            ->setValue($telephone)
            ->setLabel($this->l('Teléfono móvil'));
           /*	$extra_fields['identity_id'] = (new FormField)
                ->setName('identity_id')
                ->setType('select')
                ->setLabel($this->l('Type of user'));
                foreach($results as $result){
                $extra_fields['identity_id']->addAvailableValue($result['id_type_user'],$result['user_type']); */
        $extra_fields['validator'] = (new FormField)
            ->setName('validator')
            ->setType('text')
            ->setRequired('true')
            ->setValue($validator)
	    ->setLabel($this->l('Validador'));

                
        return $extra_fields;
    }
    /*public function hookActionCustomerAccountAdd($params)
    {
        //if e-mail of the created user address has already been added to the newsletter through the ps_emailsubscription module,
        //we delete it from ps_emailsubscription table to prevent duplicates
        if (empty($params['newCustomer'])) {
            return false;
        }
        $identity_id = $params['newCustomer']->identity_id;
        $national_id = $params['newCustomer']->national_id;
        $telephone = $params['newCustomer']->telephone;
	if ('V' != $national_id && 'E' != $national_id) {
		$register = "fail";
	}
	// 0412/0414/0424/0416/0426'
        $telephone_code = substr($telephone,0,4);
	
	if ('0412' != $telephone_code && '0414' != $telephone_code && '0424' != $telephone_code && '0416' != $telephone_code && '0426' != $telephone_code) {
		$register = "fail";
	}
	if (strlen($identity_id) <=4 || strlen($identity_id) >= 8){
		$register = "fail";
	}
	$context = Context::getContext();
        $controller = $context->controller;
	if($register == 'fail'){
		 $cust = $params['newCustomer'];
		 $cust->active= 0;
		 $cust->update();
		 Tools::redirect('prices-drop');
	}
        //return true;
    }*/
   public function hookActionAuthentication($params)
   {
    }
    /*public function hookvalidateCustomerFormFields($params)
    {
	    //0412/0414/0424/0416/0426
	$module_fields = $params['fields'];
        if ('V' != $module_fields[1]->getValue() && 'E' != $module_fields[1]->getValue() ) {
            $module_fields[1]->addError(
                $this->l('Only "V" or "E"')
            );
	}
	$telephone_codes = $module_fields[0]->getValue();
	$telephone_code = substr($telephone_codes,0,5);
        if ('0412' != $telephone_code && '0414' != $telephone_code && '0424' != $telephone_code && '0416' != $telephone_code && '0426' != $telephone_code ) {
            $module_fields[1]->addError(
                $this->l('Only 0412/0414/0424/0416/0426')
            );
	}
	$identity_id = $module_fields[2]->getValue();

	if (is_string($identity_id) && 1 === preg_match("/^[0-9]{4-8}$/", $identity_id)) {
        // update record in database
	} else {
		$module_fields[1]->addError($this->l("The ZIP code must be a 5-digit number."));
    }	
        return array(
            $module_fields
        );
    }*/
}
