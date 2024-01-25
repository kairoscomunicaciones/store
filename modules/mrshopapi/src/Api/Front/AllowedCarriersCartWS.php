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

use Address;
use Carrier;
use Context;
use Customer;
use Group;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\FermopointUtils;
use MrShopApi;
use Product;
use Validate;

class AllowedCarriersCartWS extends BaseWS
{
    /** @var CartHandler $handler */
    private $handler = null;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = false;
        $this->handler = new CartHandler($this->dataHandler->getGuestId());
    }

    private function refactorCarrier($item)
    {
        $data = [];

        $data['id_carrier'] = (int) $item['id_carrier'];
        $data['id_reference'] = (int) $item['id_reference'];
        $data['name'] = $item['name'];
        $data['delay'] = $item['delay'];
        $data['logo'] = ApiUtils::nullifyIfEmpty($item['logo']);
        $data["is_fermopoint"] = $this->isItemFermopoint($item['id_carrier']);

        if ($data['logo'] != false) {
            $data['logo'] = ApiUtils::getFullDomain(false).$data['logo'];
        }

        $displayMethod = Group::getPriceDisplayMethod(Customer::getDefaultGroupId(Context::getContext()->customer->id));
        $free = $this->module->l('Free', 'allowedcarrierscartws');

        if ($displayMethod) {
            $data['price'] = $item['price_without_tax'] === 0
                ? $free
                : $this->priceFormatter->format($item['price_without_tax'], $this->getCurrencyId()).' ('.$this->module->l('tax excl.', 'allowedcarrierscartws').')';
            $priceVal = $item['price_without_tax'];
        } else {
            $data['price'] = $item['price'] === 0
                ? $free
                : $this->priceFormatter->format($item['price'], $this->getCurrencyId()).' ('.$this->module->l('tax incl.', 'allowedcarrierscartws').')';
            $priceVal = $item['price'];
        }

        $data['price_val'] = $this->priceFormatter->getPriceData($priceVal, $this->getCurrencyId());

        return $data;
    }

    private function isItemFermopoint($carrierId)
    {
        return FermopointUtils::getFermopointCarrierId() == $carrierId;
    }

    public function getList($params, $id_customer = null)
    {
        $cart = $this->handler->getCart($id_customer, false, $this->getCurrencyId());

        if ($cart != null && Validate::isLoadedObject($cart)) {
            $data = $cart->getDeliveryOptionList();
            if (empty($data[$cart->id_address_delivery])) {
                return $this->response(true, null, []);
            }

            $data = $data[$cart->id_address_delivery];

            $checkGlsRelay = \Module::isEnabled('nkmgls');
            $languageId = (string) Context::getContext()->language->id;
            $result = [];

            foreach ($data as $key => $deliveryOption) {
                $carriersId = array_filter(explode(',', $key), function ($val) {
                    return $val !== '';
                });

                foreach ($deliveryOption['carrier_list'] as $carrierId => $carrier) {
                    if (in_array((string) $carrierId, $carriersId)) {
                        $carrierInstance = $carrier['instance'];

                        if ($checkGlsRelay) {
                            if ((int) $carrierId == (int) \Configuration::get('GLS_GLSRELAIS_ID')) {
                                continue;
                            }
                        }

                        if (false == $this->dataHandler->isApiVersionMin(MrShopApi::FERMOPOINT_MIN_VERSION) && $this->isItemFermopoint($carrierId)) {
                            continue;
                        }

                        $current = [
                            'id_carrier' => $carrierId,
                            'id_reference' => $carrierId,
                            'name' => $carrierInstance->name,
                            'delay' => $carrierInstance->delay[$languageId],
                            'price' => $carrier['price_with_tax'],
                            'price_without_tax' => $carrier['price_without_tax'],
                            'logo' => $carrier['logo'] ?: null
                        ];
                        $result[] = $this->refactorCarrier($current);
                    }

                    break;
                }
            }

            $this->response(true, null, $result);
        } else {
            $this->response(false, $this->module->l('Cart not found', 'allowedcarrierscartws'), null);
        }
    }
}
