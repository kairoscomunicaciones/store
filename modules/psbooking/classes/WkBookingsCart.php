<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.txt
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
*/
class WkBookingsCart extends ObjectModel
{
    public $id_cart;
    public $id_product;
    public $booking_type;
    public $quantity;
    public $date_from;
    public $date_to;
    public $time_from;
    public $time_to;
    public $consider_last_date;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'wk_bookings_cart',
        'primary' => 'id',
        'fields' => [
            'id_cart' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'shop' => true],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'shop' => true],
            'booking_type' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'shop' => true],
            'quantity' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'shop' => true],
            'date_from' => ['type' => self::TYPE_DATE, 'shop' => true],
            'date_to' => ['type' => self::TYPE_DATE, 'shop' => true],
            'time_from' => ['type' => self::TYPE_STRING, 'shop' => true],
            'time_to' => ['type' => self::TYPE_STRING, 'shop' => true],
            'consider_last_date' => ['type' => self::TYPE_INT, 'shop' => true],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'shop' => true],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'shop' => true],
        ],
    ];

    public function __construct($id = null, $idLang = null, $idShop = null)
    {
        parent::__construct($id, $idLang, $idShop);
        Shop::addTableAssociation('wk_bookings_cart', ['type' => 'shop', 'primary' => 'id']);
    }

    public function getBookingProductCartInfo($idProduct, $idCart)
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`id_cart` = ' . (int) $idCart
        );
    }

    public function getProductLastEnteredCartRow($idProduct, $idCart)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`id_cart` = ' . (int) $idCart . '
            ORDER BY wbcs.`id` DESC'
        );
    }

    public function getCartInfoByProduct($idProduct, $idCart)
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`id_cart` = ' . (int) $idCart . '
            ORDER BY wbcs.`id` DESC'
        );
    }

    public function getProductBookingInfoInDateRange($idProduct, $dateFrom, $dateTo)
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`date_from` <= \'' . pSql($dateTo) . '\'
            AND wbcs.`date_to` >= \'' . pSql($dateFrom) . '\''
        );
    }

    public function getProductBookingQuantityInDateRange($idProduct, $dateFrom, $dateTo)
    {
        $totalDaySeconds = 24 * 60 * 60;
        if (!Configuration::get('WK_CONSIDER_DATE_TO')) {
            $dateTo = date('Y-m-d', strtotime($dateTo) - $totalDaySeconds);
        }
        $cartBookings = Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`date_from` <= \'' . pSql($dateTo) . '\'
            AND IF(wbcs.consider_last_date = 1, wbcs.`date_to` >= \'' . pSql($dateFrom) . '\', wbcs.`date_to` > \'' .
            pSql($dateFrom) . '\')'
        );
        $bookedCount = 0;
        if ($cartBookings) {
            $dateCovered = [];
            foreach ($cartBookings as $booking) {
                $dateClashed = 0;
                if ($booking['consider_last_date']) {
                    $traverseToDate = strtotime($booking['date_to']);
                } else {
                    $traverseToDate = strtotime($booking['date_to']) - $totalDaySeconds;
                }
                $dateFrom = strtotime($booking['date_from']);
                for ($date = $dateFrom; $date <= $traverseToDate; $date = ($date + $totalDaySeconds)) {
                    if (!count($dateCovered)) {
                        $bookedCount = $booking['quantity'];
                    }
                    if (!in_array($date, $dateCovered)) {
                        $dateCovered[] = $date;
                    } else {
                        $dateClashed = 1;
                    }
                }
                if ($dateClashed) {
                    $bookedCount += $booking['quantity'];
                } elseif ($booking['quantity'] > $bookedCount) {
                    $bookedCount = $booking['quantity'];
                }
            }
        }

        return $bookedCount;
    }

    public function cartProductEntryExistsForDateRange($idCart, $idProduct, $dateFrom, $dateTo)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_cart` = ' . (int) $idCart . '
            AND wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`date_from` = \'' . pSql($dateFrom) . '\'
            AND wbcs.`date_to` = \'' . pSql($dateTo) . '\''
        );
    }

    public function cartProductEntryExistsForTimeSlot($idCart, $idProduct, $date, $timeFrom, $timeTo)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_cart` = ' . (int) $idCart . ' AND wbcs.`id_product`=' . (int) $idProduct . '
            AND wbcs.`date_from` = \'' . pSql(date('Y-m-d', strtotime($date))) . '\'
            AND wbcs.`time_from` = \'' . pSql($timeFrom) . '\'
            AND wbcs.`time_to` = \'' . pSql($timeTo) . '\''
        );
    }

    public function getProductTimeSlotBookedQuantity($id_product, $date, $time_from, $time_to)
    {
        $date = date('Y-m-d', strtotime($date));

        return Db::getInstance()->getValue(
            'SELECT SUM(wbcs.`quantity`) FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $id_product . '
            AND wbcs.`date_from` = \'' . pSql($date) . '\'
            AND wbcs.`time_from` = \'' . pSql($time_from) . '\'
            AND wbcs.`time_to` = \'' . pSql($time_to) . '\''
        );
    }

    public function getCartInfo($idCart)
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_cart` = ' . (int) $idCart
        );
    }

    public function deleteBookingProductCartByIdProductIdCart($idProduct, $idCart)
    {
        $bookingCarts = Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbcs.`id_product` = ' . (int) $idProduct . '
            AND wbcs.`id_cart` = ' . (int) $idCart
        );
        if (!empty($bookingCarts)) {
            foreach ($bookingCarts as $bookingCart) {
                $bookingCartObj = new WkBookingsCart((int) $bookingCart['id']);
                if (!$bookingCartObj->delete()) {
                    return false;
                }
            }
        }
    }

    public function getBookingProductDateWiseAvailabilityAndRates($idProduct, $date)
    {
        $objBookingProductInformation = new WkBookingProductInformation();
        $bookingProductInformation = $objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct);
        $bookingInfo = [];
        if ($bookingProductInformation) {
            $adminCurrency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
            $bookingInfo['booking_type'] = $bookingProductInformation['booking_type'];
            $bookingInfo['booking_info'] = [];
            $objBookingOrders = new WkBookingsOrders();

            if ($bookingProductInformation['booking_type'] == 1) {
                $dateTo = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                $bookedQuantity = $objBookingOrders->getProductOrderedQuantityInDateRange($idProduct, $date, $dateTo);
                $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQuantity;

                $bookingPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice($idProduct, $date, $date);

                $bookingInfo['booking_info']['price'] = $bookingPrice;
                $bookingInfo['booking_info']['available_qty'] = ($maxAvailableQuantity > 0) ? $maxAvailableQuantity : 0;
                $bookingInfo['booking_info']['booked_qty'] = $bookedQuantity;
                $bookingInfo['booking_info']['price']['total_price_tax_incl_formatted'] = Tools::displayPrice(
                    $bookingInfo['booking_info']['price']['total_price_tax_incl'],
                    $adminCurrency
                );
                $bookingInfo['booking_info']['price']['total_price_tax_excl_formatted'] = Tools::displayPrice(
                    $bookingInfo['booking_info']['price']['total_price_tax_excl'],
                    $adminCurrency
                );
                if (isset($bookingInfo['booking_info']['available_qty'])
                    && $bookingInfo['booking_info']['available_qty']
                ) {
                    $bookingInfo['calendarCssClass'] = 'booking_available';
                } else {
                    $bookingInfo['calendarCssClass'] = 'booking_unavailable';
                }
            } elseif ($bookingProductInformation['booking_type'] == 2) {
                $objBookingTimeSlots = new WkBookingProductTimeSlotPrices();
                $bookingTimeSlots = $objBookingTimeSlots->getProductTimeSlotsOnDate(
                    $idProduct,
                    $date,
                    true,
                    2,
                    false
                );
                if (empty($bookingTimeSlots)) {
                    $bookingTimeSlots = $objBookingTimeSlots->getProductTimeSlotsOnDate(
                        $idProduct,
                        $date,
                        true,
                        2,
                        true
                    );
                }
                $anySlotAvail = false;

                if ($bookingTimeSlots) {
                    foreach ($bookingTimeSlots as &$slot) {
                        $slotBookedQty = $objBookingOrders->getProductTimeSlotOrderedQuantity(
                            $idProduct,
                            $date,
                            $slot['time_slot_from'],
                            $slot['time_slot_to']
                        );
                        if (!$slotBookedQty) {
                            $slotBookedQty = 0;
                        }
                        $slotAvailQty = $slot['quantity'] - $slotBookedQty;
                        $slot['available_qty'] = $slotAvailQty > 0 ? $slotAvailQty : 0;
                        $slot['booked_qty'] = $slotBookedQty;
                        $slot['price_formatted'] = Tools::displayPrice($slot['price'], $adminCurrency);
                        if ($slot['available_qty']) {
                            $anySlotAvail = 1;
                        }
                    }
                    $bookingInfo['booking_info'] = $bookingTimeSlots;
                }
                if ($anySlotAvail) {
                    $bookingInfo['calendarCssClass'] = 'booking_available';
                } else {
                    $bookingInfo['calendarCssClass'] = 'booking_unavailable';
                }
            }
        }

        return $bookingInfo;
    }

    public function deleteCurrentCustomerCarts($shopId)
    {
        $bookingCarts = Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_bookings_cart` wbc
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_cart_shop` wbcs ON (wbcs.`id` = wbc.`id`
            AND wbcs.`id_shop` = ' . (int) $shopId . ')
            WHERE wbc.`id_cart` NOT IN (SELECT wbos.`id_cart` FROM `' . _DB_PREFIX_ . 'wk_bookings_orders` wbo
            INNER JOIN `' . _DB_PREFIX_ . 'wk_bookings_orders_shop` wbos ON (wbos.`id` = wbo.`id`
            AND wbos.`id_shop` = ' . (int) Context::getContext()->shop->id . '))'
        );
        if (!empty($bookingCarts)) {
            foreach ($bookingCarts as $bookingCart) {
                $bookingCartObj = new WkBookingsCart((int) $bookingCart['id']);
                if (!$bookingCartObj->delete()) {
                    return false;
                }
            }
        }

        $conditionPrestashopCart = '`id_cart` NOT IN (SELECT `id_cart` FROM `' . _DB_PREFIX_ . 'orders`)
        AND `id_product` IN (SELECT wbpi.`id_product` FROM `' . _DB_PREFIX_ . 'wk_booking_product_info` wbpi
        INNER JOIN `' . _DB_PREFIX_ . 'wk_booking_product_info_shop` wbpis ON (wbpis.`id` = wbpi.`id`
        AND wbpis.`id_shop` = ' . (int) $shopId . '))';

        $deleteCoreBookingCartProducts = Db::getInstance()->delete(
            'cart_product',
            $conditionPrestashopCart
        );

        return $deleteCoreBookingCartProducts;
    }
}
