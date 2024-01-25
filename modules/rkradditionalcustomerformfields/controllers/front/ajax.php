<?php
/**
 * @author    Rekire <info@rekire.com>
 * @copyright Rekire
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class RkrAdditionalCustomerFormFieldsAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
    }

    public function displayAjaxGetStates()
    {
        header('Content-Type: application/json');

        $idCountry = Tools::getValue('id_country');
        $allStatesData = State::getStatesByIdCountry($idCountry, true, 'name', 'asc');
        $states = [];

        foreach ($allStatesData as $stateData) {
            $states[] = ['value' => $stateData['id_state'], 'name' => $stateData['name']];
        }

        $this->ajaxRender(json_encode([
            'states' => $states,
        ]));
    }
}
