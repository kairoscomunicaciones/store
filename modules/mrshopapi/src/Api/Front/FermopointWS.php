<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Api\Front;

use Context;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Utils\FermopointUtils;
use MrshopApi;

class FermopointWS extends BaseWS
{
    /** @var \fermoPointApi */
    protected $fermopointApi;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->callableGate();
        $this->cacheEnabled = false;
        $this->fermopointApi = FermopointUtils::getFermopointApi();
    }

    /**
     * Checks if the this controller api are callable
     */
    protected function callableGate()
    {
        if (false == $this->isModuleActive('fermopoint') || false == $this->dataHandler->isApiVersionMin(MrshopApi::FERMOPOINT_MIN_VERSION)) {
            ResponseHandler::badRequest($this->module->l('Module not installed', 'fermopointws'));
        }
    }

    /**
     * Search for fermopoint in a range, near a location
     * @param array $params
     * @param int $id_customer
     */
    public function getList($params, $id_customer = null)
    {
        $this->callableGate();
        $required  = ['lat', 'lng', 'radius'];
        $numeric   = ['lat', 'lng', 'radius', 'day', 'from', 'to'];
        $ranges    = ['day' => [0,6], 'from' => [0,24], 'to' => [0,24]];

        $fillables = ['lat', 'lng', 'radius', 'day', 'from', 'to'];

        $errors = [];

        /**
         * Validation
         */
        foreach ($required as $r) {
            if (false == isset($params[$r])) {
                $errors[$r]['required'] = "$r è richiesto";
            }
        }

        foreach ($numeric as $n) {
            if (isset($params[$n]) && false == is_numeric($params[$n])) {
                $errors[$n]['numeric'] = "$n deve essere numerico";
            }
        }

        foreach ($ranges as $key => $a) {
            list($min, $max) = $a;
            if (isset($params[$key]) && is_numeric($params[$key]) && ($params[$key] < $min || $params[$key] > $max)) {
                $errors[$key]['range'] = "$key deve essere fra $min e $max";
            }
        }

        if (false == empty($errors)) {
            $this->response(false, $errors);
        }
        // end of validation

        /**
         * filters search filters
         */
        $data = [];

        foreach ($fillables as $f) {
            if (isset($params[$f])) {
                $data[$f] = $params[$f];
            }
        }

        $response = $this->fermopointApi->remoteRequest('/points/search', $data);
        if (false == isset($response['status']) || $response['status'] !== 'OK') {
            $this->response(false, 'Si è verificato un problema durante la ricerca');
        } else {
            $this->response(true, null, $response['result'], true);
        }
    }

    /**
     * Saves the selected fermopoint
     * @param array $params
     */
    public function updateOrCreate($params)
    {
        $this->callableGate();
        if (false == isset($params['point'])) {
            $this->response(false, ['point' => ['required' => 'Non è stato specificato alcun punto']]);
        }

        $point = $params['point'];
        if (false == isset($point['id'])) {
            $this->response(false, ['point.id' => ['required' => 'Il punto specificato non ha un identificativo']]);
        }

        $point = json_decode(json_encode($point));

        $result = FermopointUtils::persistPoint(Context::getContext()->cart->id, $point);

        if ($result) {
            $fp_address = FermopointUtils::getAddress($point);
            $ret = [
                'success' => true,
                'choice'  => [
                    // company name
                    'name'    => $point->n,
                    'address' => false == empty($fp_address)
                        ? $fp_address
                        : ''
                ]
            ];
            $this->response(true, null, $ret);
        } else {
            $this->response(false, 'Si è verificato un problema durante il salvataggio del fermopoint');
        }
    }
}
