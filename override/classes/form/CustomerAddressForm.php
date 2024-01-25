<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
/**
 * StarterTheme TODO: FIXME:
 * In the old days, when updating an address, we actually:
 * - checked if the address was used by an order
 * - if so, just mark it as deleted and create a new one
 * - otherwise, update it like a normal entity
 * I *think* this is not necessary now because the invoicing thing
 * does its own historization. But this should be checked more thoroughly.
 */
class CustomerAddressForm extends CustomerAddressFormCore
{
    /*
    * module: rkradditionalcustomerformfields
    * date: 2023-09-20 15:02:17
    * version: 1.1.1
    */
    private function isEnabledType($type = 'CountrySelect')
    {
        return (bool) Db::getInstance()->ExecuteS(
            'SELECT id_form_field FROM ' . _DB_PREFIX_ . "rkr_additional_form_fields WHERE 
                    active=1 AND type='$type';"
        );
    }
    /*
    * module: rkradditionalcustomerformfields
    * date: 2023-09-20 15:02:17
    * version: 1.1.1
    */
    private function getValueCountry($id_customer)
    {
        $query = new DbQuery();
        $query->select('cffv.value');
        $query->from('rkr_additional_form_fields', 'aff');
        $query->innerJoin('rkr_customer_form_fields_values', 'cffv', 'aff.id_form_field = cffv.id_form_field');
        $query->where('aff.active = 1');
        $query->where("aff.name = 'id_country'");
        $query->where("cffv.id_customer = $id_customer");
        return Db::getInstance()->getValue($query);
    }
    /*
    * module: rkradditionalcustomerformfields
    * date: 2023-09-20 15:02:17
    * version: 1.1.1
    */
    public function fillWith(array $params = [])
    {
        $context = Context::getContext();
        if (Module::isEnabled('rkradditionalcustomerformfields')
            && $context->controller->php_self == 'address'
            && $this->isEnabledType()
            && ($id_pais = (int) json_decode($this->getValueCountry((int) $context->customer->id))
            )
        ) {
            if (!isset($params['id_country'])
                && $id_pais != $this->formatter->getCountry()->id
            ) {
                $this->formatter->setCountry(new Country(
                    $id_pais,
                    $context->language->id
                ));
            }
        }
        return parent::fillWith($params);
    }
    /*
    * module: rkradditionalcustomerformfields
    * date: 2023-09-20 15:02:17
    * version: 1.1.1
    */
    public function getTemplateVariables()
    {
        $parentValues = parent::getTemplateVariables();
        $formFields = &$parentValues['formFields'];
        $context = Context::getContext();
        if (Module::isEnabled('rkradditionalcustomerformfields')
            && $context->controller->php_self == 'address'
        ) {
            $query = new DbQuery();
            $query->select('aff.*, cffv.value');
            $query->from('rkr_additional_form_fields', 'aff');
            $query->innerJoin('rkr_customer_form_fields_values', 'cffv', 'aff.id_form_field = cffv.id_form_field');
            $query->where('aff.active = 1 OR cffv.value is not null');
            $query->where('cffv.id_customer = ' . (int) $context->customer->id);
            $query->orderBy('aff.position');
            $campos = Db::getInstance()->ExecuteS($query);
            if ($campos != false) {
                foreach ($campos as $campo) {
                    if (isset($formFields[$campo['name']]) && ($valor = json_decode($campo['value']))
                        && empty($formFields[$campo['name']]['value'])) {
                        if ($campo['type'] != 'CountrySelect') {
                            $formFields[$campo['name']]['value'] = $valor;
                        }
                    }
                }
            }
        }
        return $parentValues;
    }
}
