<?php
class AdditionalCustomerControllerCore extends FrontController {
	public $php_self = 'additionalcustomer';
	public $ssl = true;
	public function initContent()
    {
        $notifications = false;
        parent::initContent();
        $countries = Country::getCountries($this->context->language->id, true);
        $action = $this->context->link->getPageLink('additionalcustomer');
        $this->context->smarty->assign('countries', $countries);
        $this->context->smarty->assign('action', $action);
        $this->setTemplate('additionalcustomer');
        if (Tools::isSubmit('submitMessage')) {
                $this->sendMessage();
                if (!empty($this->context->controller->errors)) {
                        $notifications['messages'] = $this->context->controller->errors;
                        $notifications['nw_error'] = true;
                } elseif (!empty($this->context->controller->success)) {
                        $notifications['messages'] = $this->context->controller->success;
                        $notifications['nw_error'] = false;
                }

        }
        $this->context->smarty->assign('notifications', $notifications);

    }
}
