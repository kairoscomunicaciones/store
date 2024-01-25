<?php
/**
* The file is controller. Do not modify the file if you want to upgrade the module in future
*
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright  2021 Globo., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

class G_ordermanagerLoginModuleFrontController extends ModuleFrontControllerCore {
    public $ssl = true;
    public function initContent() {
        parent::initContent();
        $id_customer = (int) Tools::getValue('id_customer');
        $gtoken = $this->module->getgToken($id_customer);
        $token = Tools::getValue('gtoken');
        if ($id_customer > 0 && $token !='' && $gtoken == $token) {


            
            $customer = new Customer((int) $id_customer);
            if (Validate::isLoadedObject($customer)) {
                
                if($this->context->customer->isLogged()){
                    $this->context->customer->logout();
                }
                $this->context->cookie->id_customer = (int)($customer->id);
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $customer->logged = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;

                // Add customer to the context
                $this->context->customer = $customer;

                if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id)) {
                    $this->context->cart = new Cart($id_cart);
                } else {
                    $this->context->cart->id_carrier = 0;
                    $this->context->cart->setDeliveryOption(null);
                    $this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
                    $this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
                }
                $this->context->cart->id_customer = (int)$customer->id;
                $this->context->cart->secure_key = $customer->secure_key;
                $this->context->cart->save();
                $this->context->cookie->id_cart = (int)$this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();
                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);
                if (method_exists('Cookie', 'registerSession')) $this->context->cookie->registerSession(new CustomerSession());
                Tools::redirect('index.php?controller=my-account');
            }
        }
        /** login failed */
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $this->setTemplate('module:g_ordermanager/views/templates/front/login_failed.tpl');
        else $this->setTemplate('login_failed_ps16.tpl');
    }
}