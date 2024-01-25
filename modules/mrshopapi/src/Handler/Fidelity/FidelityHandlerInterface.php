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

interface FidelityHandlerInterface
{
    /**
     * Returns a message concerning the number of points collectable with the current cart
     * @param \Cart $cart
     * @param int $id_customer
     * @param \Currency $currency
     * @return array
     */
    public function getCartFidelityData($cart, $id_customer, $currency);

    /**
     * Checks if the module is active
     * @return bool
     */
    public function isModuleActive();

    /**
     * Checks if the handler is active and should be used
     * @return bool
     */
    public static function isActive();

    /**
     * Returns the ask for payment url
     * @param int $customerId
     * @return string
     */
    public function getAskForPaymentUrl($customerId);

    /**
     * Checks if the fidelity program is active
     * @return bool
     */
    public function isFidelityProgramActive();

    /**
     * Checks if the fidelity program is available
     * @return bool
     */
    public function isFidelityAvailable();

    /**
     * Transforms the current user credits into a coupon
     * @param int $customerId
     * @params array $params
     * @return bool
     */
    public function transformCredits($customerId, $params);

    /**
     * Gets the rewards history for the customer id
     * @param int $customerId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getRewardsHistory($customerId, $page, $limit);

    /**
     * Gets the current reward status for customer id
     * @param int $idCustomer
     * @param bool $actions if available actions should be included
     * @return array
     */
    public function getRewardsAccount($idCustomer, $actions = true);

    /**
     * Gets the list of products generating rewards
     * @param int $page
     * @param int $limit
     * @param string $orderFieldForModule
     * @param string $orderWay (ASC|DESC|asc|desc)
     * @return array
     */
    public function getProductsGiftsList($page, $limit, $orderBy, $orderWay);

    /**
    * Purchase the product with the current rewards.
    * @param $idProduct
    * @param $idProductAttribute
    * @return array
    */
    public function purchaseWithRewards($idProduct, $idProductAttribute);

    /**
     * Gets the rewards related to the product
     * @param type $id
     * @param type $id_product_attribute
     * @return array
     */
    public function getProductFidelityData($idProduct, $idProductAttribute);
}
