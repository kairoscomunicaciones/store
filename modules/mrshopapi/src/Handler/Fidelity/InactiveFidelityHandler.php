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
 *
 */

namespace MrAPPs\MrShopApi\Handler\Fidelity;

class InactiveFidelityHandler implements FidelityHandlerInterface
{
    public function getAskForPaymentUrl($customerId)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function getCartFidelityData($cart, $id_customer, $currency)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function getProductFidelityData($idProduct, $idProductAttribute)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function getProductsGiftsList($page, $limit, $orderBy, $orderWay)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function getRewardsAccount($idCustomer, $actions = true)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function getRewardsHistory($customerId, $page, $limit)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public static function isActive()
    {
        return false;
    }

    public function isFidelityProgramActive()
    {
        return false;
    }

    public function isModuleActive()
    {
        return false;
    }

    public function isFidelityAvailable()
    {
        return false;
    }

    public function purchaseWithRewards($idProduct, $idProductAttribute)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }

    public function transformCredits($customerId, $params)
    {
        throw new UnsupportedFidelityActionException('Unsupported Action');
    }
}
