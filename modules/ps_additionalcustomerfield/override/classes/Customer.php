<?php
use PrestaShop\PrestaShop\Adapter\ServiceLocator;
use PrestaShop\PrestaShop\Adapter\CoreException;
/***
 * Class CustomerCore
 */
class Customer extends CustomerCore
{
	/*
    * module: ps_additionalcustomerfield
    * date: 2022-11-04 15:49:55
    * version: 1.0.1
    */
	
    public $telephone;
    public $validator;
   // public $identity_id;
    public function __construct($id = null)
    {
        self::$definition['fields']['telephone'] = array('type' => self::TYPE_STRING, 'validate' => 'isGenericName');
        self::$definition['fields']['validator'] = array('type' => self::TYPE_STRING, 'validate' => 'isGenericName');
    //    self::$definition['fields']['identity_id'] = array('type' => self::TYPE_INT, 'validate' => 'isInt');
        parent::__construct($id);
    }
}
