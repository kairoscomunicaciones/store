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

namespace MrAPPs\MrShopApi\Handler;

use Configuration;
use Context;
use Db;
use Group;
use Guest;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Service\PriceFormatter;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use StockAvailable;
use Tools;
use Validate;

class BookingHandler
{
    private $context;

    private $module;

    private $priceFormatter;

    /**
     * BookingHandler constructor.
     *
     * @param $dataHandler
     */
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->module = ApiUtils::getModule();
        $this->priceFormatter = new PriceFormatter();

        if ($this->isModuleActive()) {
            include_once _PS_MODULE_DIR_.'psbooking/classes/WkBookingRequiredClasses.php';
        }
    }

    /**
     * Check if module psbooking is active
     *
     * @return bool|mixed
     */
    public function isModuleActive()
    {
        return Module::isEnabled('psbooking');
    }

    /**
     * Check if product type is booking (psbooking module)
     *
     * @param $idProduct
     *
     * @return bool
     */
    public function isBookingProduct($idProduct)
    {
        $isBookingProduct = false;
        if ($this->isModuleActive()) {
            $WkBookingProductInformation = new \WkBookingProductInformation();
            $isBookingProduct = $WkBookingProductInformation->isBookingProduct((int) $idProduct);
        }

        return $isBookingProduct;
    }

    /**
     * Get all booking data by product
     *
     * @param $idProduct
     *
     * @return array
     */
    public function getBookingData($idProduct)
    {
        if (!$this->isBookingProduct($idProduct)) {
            return null;
        }

        $WkBookingProductInformation = new \WkBookingProductInformation();

        $booking = [];

        /** Classes */
        $WkBookingsCart = new \WkBookingsCart();
        $WkBookingProductFeaturePricing = new \WkBookingProductFeaturePricing();
        $WkBookingProductDisabledDates = new \WkBookingProductDisabledDates();

        $filter = Tools::getIsset('filter') ? Tools::getValue('filter') : [];
        $bookingDateFrom = !empty($filter) && isset($filter['booking_date_from']) ? date('d-m-Y', (int) $filter['booking_date_from']) : null;
        $bookingDateTo = !empty($filter) && isset($filter['booking_date_to']) ? date('d-m-Y', (int) $filter['booking_date_to']) : null;

        $bookingProductInfo = $WkBookingProductInformation->getBookingProductInfoByIdProduct($idProduct);

        /* Booking type */
        $booking['type'] = (int) $bookingProductInfo['booking_type'];

        /** Calandar interval */
        $calendarFrom = date('d-m-Y');
        $calendarTo = date('d-m-Y', strtotime('+1 year'));
        $booking['calendar_from'] = strtotime($calendarFrom);
        $booking['calendar_to'] = strtotime($calendarTo);

        /* Booking disabled days and product date */
        $booking['disabled_dates'] = [];
        $booking['disabled_days'] = [];
        $disabledInfo = $WkBookingProductDisabledDates->getBookingProductDisableDates(
            $idProduct
        );

        /* Int Array (0 sunday, 1 monday, ... 6 saturday) */
        if ((bool) $disabledInfo['disable_special_days_active']) {
            $disabledDays = json_decode($disabledInfo['disabled_special_days']);
            foreach ($disabledDays as $disabledDay) {
                $booking['disabled_days'][] = (int) $disabledDay;
            }
        }

        /* Json Array (dates intervals) */
        if ((bool) $disabledInfo['disabled_dates_slots_active']) {
            $disabledDates = json_decode($disabledInfo['disabled_dates_slots'], true);
            $disabledDates = array_values($disabledDates);
            $formattedDisabledDates = [];
            foreach ($disabledDates as $disabledDate) {
                $formattedDisabledDates[] = [
                    'date_from' => strtotime($disabledDate['date_from']),
                    'date_to' => strtotime($disabledDate['date_to']),
                ];
            }
            $booking['disabled_dates'] = $formattedDisabledDates;
        }

        $booking['consider_date_to'] = (bool) Configuration::get('WK_CONSIDER_DATE_TO');

        if ($bookingDateFrom && $bookingDateTo) {
            /* Booking date interval requested */
            $booking['date_from'] = strtotime($bookingDateFrom);
            $booking['date_to'] = strtotime($bookingDateTo);

            /** Booking available qty */
            $quantityAvailable = $bookingProductInfo['quantity'] -
                (int) $WkBookingsCart->getProductBookingQuantityInDateRange(
                    $idProduct,
                    date('Y-m-d', strtotime($bookingDateFrom)),
                    date('Y-m-d', strtotime($bookingDateTo))
                );

            $booking['quantity_available'] = $quantityAvailable;

            /* Booking total days */
            $booking['days'] = $WkBookingProductFeaturePricing->getNumberOfDays($bookingDateFrom, $bookingDateTo) -
                count($WkBookingProductDisabledDates->getBookingProductDisableDatesInDateRange($idProduct, $bookingDateFrom, $bookingDateTo));

            $quantity = 1;

            $bookingProductPriceCalc = $this->calcBookingProductPrice($idProduct, $bookingDateFrom, $bookingDateTo, $quantity);

            if ('ok' === $bookingProductPriceCalc['status']) {
                /* Booking date range info */
                if ($bookingProductPriceCalc['dateRangesBookingInfo']) {
                    $booking['date_range_info'] = [];
                    foreach ($bookingProductPriceCalc['dateRangesBookingInfo'] as $dateRangeInfo) {
                        $booking['date_range_info'][] = [
                            'date_from' => strtotime($dateRangeInfo['date_from']),
                            'date_to' => strtotime($dateRangeInfo['date_to']),
                            'price' => $dateRangeInfo['price'],
                        ];
                    }
                }

                /* Booking product price */
                if ($bookingProductPriceCalc['productPrice']) {
                    $booking['product_price'] = $bookingProductPriceCalc['productPrice'];
                }
            }
        }

        return $booking;
    }

    /**
     * Add or update the booking product on cart
     *
     * @param $idProduct
     * @param $params
     * @param $quantity
     * @param $idCustomer
     * @param $cart
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function updateOrCreate($params, $cart)
    {
        $quantity = array_key_exists('quantity', $params) ? (int) $params['quantity'] : null;
        $idProduct = array_key_exists('id_product', $params) ? (int) $params['id_product'] : null;
        $dateFrom = array_key_exists('booking_date_from', $params) ? (int) $params['booking_date_from'] : null;
        $dateTo = array_key_exists('booking_date_to', $params) ? (int) $params['booking_date_to'] : null;
        $WkBookingProductInformation = new \WkBookingProductInformation();
        $productBookingInfo = $WkBookingProductInformation->getBookingProductInfoByIdProduct($idProduct);
        $bookingType = $productBookingInfo['booking_type'];

        $idBookingCart = (int) Db::getInstance()->getValue(
            'SELECT * FROM `'._DB_PREFIX_.'wk_bookings_cart`
                WHERE `id_product`='.(int) $idProduct.' 
                AND `booking_type`='.(int) $bookingType.' 
                AND `date_from` =\''.date('Y-m-d', ($dateFrom)).'\' 
                AND `date_to` =\''.date('Y-m-d', ($dateTo)).'\'
                AND `id_cart` ='.(int) $cart->id
        );

        /*
         * To prevent error on update booking product cart quantity,
         * the standard product quantity must always be present.
         */
        $this->setUnlimitedQuantity($idProduct);

        /*
         * Create
         */
        if (!$idBookingCart) {
            $productAdded = $this->addBookingProduct(
                $idProduct,
                $bookingType,
                $dateFrom,
                $dateTo,
                $quantity
            );
            if ('ok' === $productAdded['status']) {
                if ($cart->updateQty(
                    (int) $productAdded['product_qty_to_cart'],
                    $idProduct,
                    null,
                    false,
                    $quantity > 0 ? 'up' : 'down',
                    0,
                    null,
                    true,
                    true
                )) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            /**
             * Update/Delete
             */
            $currentQuantity = (int) Db::getInstance()->getValue(
                'SELECT quantity FROM `'._DB_PREFIX_.'wk_bookings_cart`
                WHERE `id`='.(int) $idBookingCart
            );

            $newQuantity = $currentQuantity + $quantity;
            /*
             * Delete
             */
            if ($quantity == 0 || $newQuantity < 1) {
                return $this->removeBookingProduct($idProduct, $idBookingCart);
            } else {
                /*
                 * Update
                 */
                return $this->updateBookingProduct($idProduct, $idBookingCart, $quantity);
            }
        }
    }

    /**
     * Add booking product to cart
     *
     * @param $idProduct
     * @param $booking_type
     * @param $dateFrom
     * @param $dateTo
     * @param $quantity
     *
     * @return mixed
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function addBookingProduct($idProduct, $booking_type, $dateFrom, $dateTo, $quantity)
    {
        $errors = [];
        $result = [];
        if ($booking_type == 1) {  // date range type bookings
            $dateFrom = date('Y-m-d', $dateFrom);
            $dateTo = date('Y-m-d', $dateTo);
            $currentDate = date('Y-m-d');

            // validate values first
            if (!$idProduct) {
                $errors[] = $this->module->l('Product Id is missing.', 'bookinghandler');
            } elseif ($dateFrom == '' || !Validate::isDate($dateFrom)) {
                $errors[] = $this->module->l('Invalid Date From.', 'bookinghandler');
            } elseif ($dateTo == '' || !Validate::isDate($dateTo)) {
                $errors[] = $this->module->l('Invalid Date To.', 'bookinghandler');
            } elseif ($dateFrom < $currentDate) {
                $errors[] = $this->module->l(
                    'Date from should not be before current date.',
                    'bookinghandler'
                );
            } elseif (!Validate::isUnsignedInt($quantity) || !$quantity) {
                $errors[] = $this->module->l('Invalid quantity.', 'bookinghandler');
            }
            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                if ($dateTo < $dateFrom) {
                    $errors[] = $this->module->l(
                        'Date to should be a date after date from.',
                        'bookinghandler'
                    );
                }
            } else {
                if ($dateTo <= $dateFrom) {
                    $errors[] = $this->module->l(
                        'Date to should be a date after date from.',
                        'bookinghandler'
                    );
                }
            }
            $objBookingProductInfo = new \WkBookingProductInformation();
            $wkBookingsCart = new \WkBookingsCart();
            $wkBookingOrder = new \WkBookingsOrders();
            $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
            $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
            $maxAvailableQuantity = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;
            if (!$maxAvailableQuantity) {
                $errors[] = $this->module->l(
                    'Required quantity for this date range not available.',
                    'bookinghandler'
                );
            }
            $productQtyToCart = 0;
            if (!count($errors)) {
                if (!$this->context->cart->id) {
                    if (Context::getContext()->cookie->id_guest) {
                        $guest = new Guest(Context::getContext()->cookie->id_guest);
                        $this->context->cart->mobile_theme = $guest->mobile_theme;
                    }
                    $this->context->cart->add();
                    if ($this->context->cart->id) {
                        $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    }
                }
                // Data to show Disables dates (Disable dates/slots tab)
                $objBookingDisableDates = new \WkBookingProductDisabledDates();
                // get booking product disable dates
                $bookingDisableDates = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateTo
                );
                $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                if ($bookingDisableDates && count($bookingDisableDates)) {
                    $tempDateFrom = $dateFrom;
                    $bookingDateRanges = [];
                    for ($date = strtotime($dateFrom); $date <= (strtotime($dateTo)); $date = ($date + (24 * 60 * 60))) {
                        $currentDate = date('Y-m-d', $date);
                        $prevdate = date('Y-m-d', strtotime($currentDate) - 86400);
                        if (in_array($prevdate, $bookingDisableDates)) {
                            $tempDateFrom = $currentDate;
                        }
                        if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                            $lastDateCondition = strtotime($currentDate) == strtotime($dateTo) && !in_array($currentDate, $bookingDisableDates);
                        } else {
                            $lastDateCondition = strtotime($currentDate) == strtotime($dateTo) && !in_array($currentDate, $bookingDisableDates) && !in_array($prevdate, $bookingDisableDates);
                        }
                        if ($lastDateCondition) {
                            $totalPrice = call_user_func(
                                'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                $idProduct,
                                $tempDateFrom,
                                $dateTo,
                                false,
                                $this->context->currency->id
                            );
                            if ($totalPrice) {
                                if (!$priceDisplay || $priceDisplay == 2) {
                                    $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                } elseif ($priceDisplay == 1) {
                                    $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                }
                            }
                            $bookingDateRanges[] = [
                                'date_from' => $tempDateFrom,
                                'date_to' => $dateTo,
                                'price' => Tools::displayPrice($productPrice),
                            ];
                            $productQtyToCart += call_user_func(
                                'WkBookingProductFeaturePricing::getNumberOfDays',
                                $tempDateFrom,
                                $dateTo
                            );
                        } elseif (strtotime($currentDate) != strtotime($dateTo)
                            && strtotime($currentDate) != strtotime($dateFrom)
                            && !in_array($prevdate, $bookingDisableDates)
                            && in_array($currentDate, $bookingDisableDates)
                        ) {
                            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                                $totalPrice = call_user_func(
                                    'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                    $idProduct,
                                    $tempDateFrom,
                                    $prevdate,
                                    false,
                                    $this->context->currency->id
                                );
                                if ($totalPrice) {
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                    }
                                }
                                $bookingDateRanges[] = [
                                    'date_from' => $tempDateFrom,
                                    'date_to' => $prevdate,
                                    'price' => Tools::displayPrice($productPrice),
                                ];
                                $productQtyToCart += call_user_func(
                                    'WkBookingProductFeaturePricing::getNumberOfDays',
                                    $tempDateFrom,
                                    $prevdate
                                );
                            } else {
                                $totalPrice = call_user_func(
                                    'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                    $idProduct,
                                    $tempDateFrom,
                                    $currentDate,
                                    false,
                                    $this->context->currency->id
                                );
                                if ($totalPrice) {
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                    }
                                }
                                $bookingDateRanges[] = [
                                    'date_from' => $tempDateFrom,
                                    'date_to' => $currentDate,
                                    'price' => Tools::displayPrice($productPrice),
                                ];
                                $productQtyToCart += call_user_func(
                                    'WkBookingProductFeaturePricing::getNumberOfDays',
                                    $tempDateFrom,
                                    $currentDate
                                );
                            }
                        }
                    }
                } else {
                    $totalPrice = call_user_func(
                        'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                        $idProduct,
                        $dateFrom,
                        $dateTo,
                        false,
                        $this->context->currency->id
                    );
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                    } elseif ($priceDisplay == 1) {
                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                    }
                    $bookingDateRanges[] = ['date_from' => $dateFrom, 'date_to' => $dateTo, 'price' => Tools::displayPrice($productPrice)];
                    $productQtyToCart += call_user_func('WkBookingProductFeaturePricing::getNumberOfDays', $dateFrom, $dateTo);
                }
                if (isset($bookingDateRanges) && count($bookingDateRanges)) {
                    foreach ($bookingDateRanges as $dateRange) {
                        $dateRangeCartEntryExists = $wkBookingsCart->cartProductEntryExistsForDateRange(
                            $this->context->cart->id,
                            $idProduct,
                            $dateRange['date_from'],
                            $dateRange['date_to']
                        );
                        if ($dateRangeCartEntryExists) {
                            $wkBookingsCart = new \WkBookingsCart($dateRangeCartEntryExists['id']);
                            $wkBookingsCart->quantity += $quantity;
                        } else {
                            $wkBookingsCart = new \WkBookingsCart();
                            $wkBookingsCart->id_cart = $this->context->cart->id;
                            $wkBookingsCart->id_order = 0;
                            $wkBookingsCart->id_product = $idProduct;
                            $wkBookingsCart->booking_type = 1;
                            $wkBookingsCart->quantity = $quantity;
                            $wkBookingsCart->date_from = $dateRange['date_from'];
                            $wkBookingsCart->date_to = $dateRange['date_to'];
                            $wkBookingsCart->time_from = '';
                            $wkBookingsCart->time_to = '';
                            $wkBookingsCart->consider_last_date = Configuration::get('WK_CONSIDER_DATE_TO');
                        }
                        $wkBookingsCart->save();
                    }
                    $result['status'] = 'ok';
                } else {
                    $result['status'] = 'ko';
                    $errors[] = $this->module->l(
                        'This date range is not available for booking. Please select another.',
                        'bookinghandler'
                    );
                }
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $errors;
            }
            $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
            $maxAvailableQuantity = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;

            $result['errors'] = $errors;
            $result['product_qty_to_cart'] = $productQtyToCart * $quantity;
            $result['available_qty'] = $maxAvailableQuantity;
        } elseif ($booking_type == 2) { // Time Slots type bookings
            $date = date('Y-m-d', strtotime(Tools::getValue('date')));
            $selectedSlots = Tools::getValue('selected_slots');
            $quantity = Tools::getValue('quantity');
            $idProduct = Tools::getValue('id_product');
            $bookingTimeSlotPrice = [];
            $bookingTimeSlotPrice['price_tax_excl'] = 0;

            // validate values first
            if (!$idProduct) {
                $errors[] = $this->module->l('Product Id is missing.', 'bookinghandler');
            }
            if ($selectedSlots) {
                foreach ($selectedSlots as $slot) {
                    if (empty($slot['quantity']) || !$slot['quantity']) {
                        $errors[] = $this->module->l('invalid quantity found.', 'bookinghandler');

                        break;
                    } elseif (!Validate::isInt($slot['quantity'])) {
                        $errors[] = $this->module->l('invalid quantity found.', 'bookinghandler');

                        break;
                    }
                }
            }
            $totalProductQty = 0;
            if (!count($errors)) {
                if (!$this->context->cart->id) {
                    if (Context::getContext()->cookie->id_guest) {
                        $guest = new Guest(Context::getContext()->cookie->id_guest);
                        $this->context->cart->mobile_theme = $guest->mobile_theme;
                    }
                    $this->context->cart->add();
                    if ($this->context->cart->id) {
                        $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    }
                }
                $objBookingProductInfo = new \WkBookingProductInformation();
                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                if ($selectedSlots) {
                    $totalTimeSlotFeaturePrice = [];
                    $totalTimeSlotFeaturePrice['total_price_tax_incl'] = 0;
                    $totalTimeSlotFeaturePrice['total_price_tax_excl'] = 0;
                    $timeSlotsInfo = [];
                    $keySlot = 0;
                    $wkBookingOrder = new \WkBookingsOrders();
                    foreach ($selectedSlots as $key => $slot) {
                        $wkBookingsCart = new \WkBookingsCart();
                        $objBookingSlot = new \WkBookingProductTimeSlotPrices($slot['id_slot']);
                        $bookedSlotQuantity = $wkBookingOrder->getProductTimeSlotOrderedQuantity(
                            $idProduct,
                            $date,
                            $objBookingSlot->time_slot_from,
                            $objBookingSlot->time_slot_to,
                            1
                        );
                        $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedSlotQuantity;
                        if ($maxAvailableQuantity >= $slot['quantity']) {
                            $bookingTimeSlotPrice['price_tax_excl'] = ($objBookingSlot->price);
                            $taxRate = (float) call_user_func('WkBookingProductInformation::getAppliedProductTaxRate', $idProduct);
                            $bookingTimeSlotPrice['price_tax_incl'] = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                            $timeSlotFeaturePrice = call_user_func(
                                'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                $idProduct,
                                $date,
                                $date,
                                $bookingTimeSlotPrice,
                                $this->context->currency->id
                            );
                            $totalTimeSlotFeaturePrice['total_price_tax_incl'] += $timeSlotFeaturePrice['total_price_tax_incl'] * $slot['quantity'];
                            $totalTimeSlotFeaturePrice['total_price_tax_excl'] += $timeSlotFeaturePrice['total_price_tax_excl'] * $slot['quantity'];

                            $dateRangeCartEntryExists = $wkBookingsCart->cartProductEntryExistsForTimeSlot(
                                $this->context->cart->id,
                                $idProduct,
                                $date,
                                $objBookingSlot->time_slot_from,
                                $objBookingSlot->time_slot_to
                            );
                            if ($dateRangeCartEntryExists) {
                                $wkBookingsCart = new \WkBookingsCart($dateRangeCartEntryExists['id']);
                                $wkBookingsCart->quantity += $slot['quantity'];
                            } else {
                                $wkBookingsCart->id_cart = $this->context->cart->id;
                                $wkBookingsCart->id_product = $idProduct;
                                $wkBookingsCart->booking_type = 2;
                                $wkBookingsCart->quantity = $slot['quantity'];
                                $wkBookingsCart->date_from = $date;
                                $wkBookingsCart->date_to = '';
                                $wkBookingsCart->time_from = $objBookingSlot->time_slot_from;
                                $wkBookingsCart->time_to = $objBookingSlot->time_slot_to;
                            }
                            $totalProductQty += $slot['quantity'];
                            if (!$wkBookingsCart->save()) {
                                $errors[] = $this->module->l(
                                    'Some error occurred while saving slot cart data.',
                                    'bookinghandler'
                                );
                            }
                            $timeSlotsInfo[$keySlot]['slot_id'] = $slot['id_slot'];
                            $timeSlotsInfo[$keySlot]['slot_from'] = $objBookingSlot->time_slot_from;
                            $timeSlotsInfo[$keySlot]['slot_to'] = $objBookingSlot->time_slot_to;
                            $timeSlotsInfo[$keySlot]['quantity_avail'] = $maxAvailableQuantity - $slot['quantity'];
                            $timeSlotsInfo[$keySlot]['quantity'] = $slot['quantity'];
                            ++$keySlot;
                        } else {
                            $internationalError = $this->module->l(
                                'Required quantity not available for slot %s - %s',
                                'bookinghandler'
                            );
                            $errors[] = sprintf($internationalError, $objBookingSlot->time_slot_from, $objBookingSlot->time_slot_to);
                        }
                    }
                    $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productPrice = $totalTimeSlotFeaturePrice['total_price_tax_incl'];
                    } elseif ($priceDisplay == 1) {
                        $productPrice = $totalTimeSlotFeaturePrice['total_price_tax_excl'];
                    }
                    $result['totalPrice'] = $productPrice;
                    $result['totalPriceFormatted'] = Tools::displayPrice($productPrice);
                    $result['timeSlotsInfo'] = $timeSlotsInfo;
                } else {
                    $errors[] = $this->module->l('No time slot is selected.', 'bookinghandler');
                }
                $result['status'] = 'ok';
                $result['totalQty'] = (int) $totalProductQty;
                $result['errors'] = $errors;
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $errors;
            }
        }

        return $result;
    }

    /**
     * Remove booking product from cart
     *
     * @param $idProduct
     * @param $idCartBooking
     *
     * @return bool
     */
    public function removeBookingProduct($idProduct, $idCartBooking)
    {
        $objWkBookingsCart = new \WkBookingsCart($idCartBooking);
        $objWkBookingsCart->deleteBookingProductCartByIdProductIdCart($idProduct, $idCartBooking);
        $bookingType = $objWkBookingsCart->booking_type;
        if ($bookingType == 1) {
            $daysCount = (int) call_user_func(
                'WkBookingProductFeaturePricing::getNumberOfDays',
                $objWkBookingsCart->date_from,
                $objWkBookingsCart->date_to
            );
        } else {
            $daysCount = 1;
        }
        $quantityToReduce = ($daysCount * (int) $objWkBookingsCart->quantity);
        if ($this->context->cart->updateQty(
            (int) $quantityToReduce,
            (int) $idProduct,
            null,
            false,
            'down',
            0,
            null,
            true
        )) {
            $deleted = Db::getInstance()->delete(
                'wk_bookings_cart',
                '`id_product`='.(int) $idProduct.' AND `id`='.(int) $idCartBooking
            );
            if ($deleted) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Update the booking product quantity
     *
     * @param $idProduct
     * @param $idCartBooking
     * @param $quantity
     *
     * @return bool|string
     *
     * @throws PrestaShopDatabaseException
     */
    public function updateBookingProduct($idProduct, $idCartBooking, $quantity)
    {
        // check availability
        // update quantity
        // update cart
        $WkBookingsCart = new \WkBookingsCart();
        $WkBookingProductFeaturePricing = new \WkBookingProductFeaturePricing();
        $WkBookingProductInformation = new \WkBookingProductInformation();
        $productInformation = $WkBookingProductInformation->getBookingProductInfoByIdProduct($idProduct);
        $currentCartBooking = Db::getInstance()->executeS(
            'SELECT * FROM `'._DB_PREFIX_.'wk_bookings_cart`
                WHERE `id`='.(int) $idCartBooking
        );

        if (!empty($currentCartBooking) && count($currentCartBooking)) {
            $currentCartBooking = $currentCartBooking[0];
            $bookedQuantity = (int) $WkBookingsCart->getProductBookingQuantityInDateRange($idProduct, $currentCartBooking['date_from'], $currentCartBooking['date_to']);
            $availableQuantity = $productInformation['quantity'] - $bookedQuantity;
            $days = $WkBookingProductFeaturePricing->getNumberOfDays($currentCartBooking['date_from'], $currentCartBooking['date_to']);
            $newQuantity = (int) $currentCartBooking['quantity'] + $quantity;

            if ($quantity > $availableQuantity) {
                return false;
            } else {
                if ($this->context->cart->updateQty(
                    abs((int) $quantity * (int) $days),
                    (int) $idProduct,
                    null,
                    false,
                    $quantity > 0 ? 'up' : 'down',
                    0,
                    null,
                    true
                )) {
                    $update = Db::getInstance()->update(
                        'wk_bookings_cart',
                        ['quantity' => $newQuantity],
                        'id = '.(int) $idCartBooking
                    );
                    if ($update) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Calculate the booking product price by date and quantity
     *
     * @param $idProduct
     * @param $dateFrom
     * @param $dateTo
     * @param $quantity
     *
     * @return mixed
     */
    public function calcBookingProductPrice($idProduct, $dateFrom, $dateTo, $quantity)
    {
        $dateFrom = date('Y-m-d', strtotime($dateFrom));
        $dateTo = date('Y-m-d', strtotime($dateTo));
        $currentDate = date('Y-m-d');
        $errors = [];
        $result = [];
        if (!$idProduct) {
            $errors[] = $this->module->l('Product Id is missing.', 'bookinghandler');
        } elseif ($dateFrom == '' || !Validate::isDate($dateFrom)) {
            $errors[] = $this->module->l('Invalid Date From.', 'bookinghandler');
        } elseif ($dateTo == '' || !Validate::isDate($dateTo)) {
            $errors[] = $this->module->l('Invalid Date To.', 'bookinghandler');
        } elseif ($dateFrom < $currentDate) {
            $errors[] = $this->module->l(
                'Date from should not be before current date.',
                'bookinghandler'
            );
        }
        if (Configuration::get('WK_CONSIDER_DATE_TO')) {
            if ($dateTo < $dateFrom) {
                $errors[] = $this->module->l(
                    'Date to should be a date after date from.',
                    'bookinghandler'
                );
            }
        } else {
            if ($dateTo <= $dateFrom) {
                $errors[] = $this->module->l(
                    'Date to should be a date after date from.',
                    'bookinghandler'
                );
            }
        }

        if (!count($errors)) {
            $objBookingProductInfo = new \WkBookingProductInformation();
            $wkBookingOrder = new \WkBookingsOrders();
            $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
            $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
            $result['max_avail_qty'] = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;

            // Data to show Disables dates (Disable dates/slots tab)
            $objBookingDisableDates = new \WkBookingProductDisabledDates();
            // get booking product disable dates
            $bookingDisableDates = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                $idProduct,
                $dateFrom,
                $dateTo
            );
            $priceDisplay = \Group::getPriceDisplayMethod(Group::getCurrent()->id);
            $productPriceTotal = 0;
            if ($bookingDisableDates && count($bookingDisableDates)) {
                $tempDateFrom = $dateFrom;
                $bookingDateRanges = [];
                for ($date = strtotime($dateFrom); $date <= (strtotime($dateTo)); $date = ($date + (24 * 60 * 60))) {
                    $currentDate = date('Y-m-d', $date);
                    $prevdate = date('Y-m-d', strtotime($currentDate) - 86400);
                    if (in_array($prevdate, $bookingDisableDates)) {
                        $tempDateFrom = $currentDate;
                    }
                    if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                        $lastDateCondition = strtotime($currentDate) == strtotime($dateTo) && !in_array($currentDate, $bookingDisableDates);
                    } else {
                        $lastDateCondition = strtotime($currentDate) == strtotime($dateTo) && !in_array($currentDate, $bookingDisableDates) && !in_array($prevdate, $bookingDisableDates);
                    }
                    if ($lastDateCondition) {
                        $totalPrice = call_user_func(
                            'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                            $idProduct,
                            $tempDateFrom,
                            $dateTo,
                            false,
                            $this->context->currency->id
                        );
                        if ($totalPrice) {
                            if (!$priceDisplay || $priceDisplay == 2) {
                                $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                            } elseif ($priceDisplay == 1) {
                                $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                            }
                            $productPriceTotal += $productPrice;
                        }
                        $bookingDateRanges[] = [
                            'date_from' => $tempDateFrom,
                            'date_to' => $dateTo,
                            'price' => Tools::displayPrice($productPrice),
                        ];
                    } elseif (strtotime($currentDate) != strtotime($dateTo)
                        && strtotime($currentDate) != strtotime($dateFrom)
                        && !in_array($prevdate, $bookingDisableDates)
                        && in_array($currentDate, $bookingDisableDates)
                    ) {
                        if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                            $totalPrice = call_user_func(
                                'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                $idProduct,
                                $tempDateFrom,
                                $prevdate,
                                false,
                                $this->context->currency->id
                            );
                            if ($totalPrice) {
                                if (!$priceDisplay || $priceDisplay == 2) {
                                    $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                } elseif ($priceDisplay == 1) {
                                    $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                }
                                $productPriceTotal += $productPrice;
                            }
                            $bookingDateRanges[] = [
                                'date_from' => $tempDateFrom,
                                'date_to' => $prevdate,
                                'price' => Tools::displayPrice($productPrice),
                            ];
                        } else {
                            $totalPrice = call_user_func(
                                'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                                $idProduct,
                                $tempDateFrom,
                                $currentDate,
                                false,
                                $this->context->currency->id
                            );
                            if ($totalPrice) {
                                if (!$priceDisplay || $priceDisplay == 2) {
                                    $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                } elseif ($priceDisplay == 1) {
                                    $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                }
                                $productPriceTotal += $productPrice;
                            }
                            $bookingDateRanges[] = [
                                'date_from' => $tempDateFrom,
                                'date_to' => $currentDate,
                                'price' => Tools::displayPrice($productPrice),
                            ];
                        }
                    }
                }
                $result['showBookings'] = 1;
            } else {
                $totalPrice = call_user_func(
                    'WkBookingProductFeaturePricing::getBookingProductTotalPrice',
                    $idProduct,
                    $dateFrom,
                    $dateTo,
                    false,
                    $this->context->currency->id
                );
                if (!$priceDisplay || $priceDisplay == 2) {
                    $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                } elseif ($priceDisplay == 1) {
                    $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                }
                $productPriceTotal += $productPrice;
                $bookingDateRanges[] = [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'price' => Tools::displayPrice($productPrice),
                ];
                $result['showBookings'] = 0;
            }
        }
        if (!count($errors)) {
            $result['status'] = 'ok';
            $result['dateRangesBookingInfo'] = $bookingDateRanges;
            $result['productPrice'] = Tools::displayPrice($productPriceTotal);
        } else {
            $result['status'] = 'ko';
            $result['errors'] = $errors;
        }

        return $result;
    }

    /**
     * Converte a single booking cart item into many cart items (each for date interval)
     *
     * @param $product
     *
     * @return array
     */
    public function getCartItem($product)
    {
        $productId = (int) $product['id_product'];
        $WkBookingProductInformation = new \WkBookingProductInformation();
        $WkBookingsCart = new \WkBookingsCart();
        $WkBookingProductFeaturePricing = new \WkBookingProductFeaturePricing();
        $bookingProductInfo = $WkBookingProductInformation->getBookingProductInfoByIdProduct($productId);
        $bookingProductCartInfo = $WkBookingsCart->getBookingProductCartInfo(
            $productId,
            $this->context->cart->id
        );
        $items = [];
        foreach ($bookingProductCartInfo as $bookingProductCartItem) {
            $description = sprintf(
                '%s %s %s %s',
                $this->module->l('From ', 'bookinghandler'),
                date('d-m-Y', strtotime($bookingProductCartItem['date_from'])),
                $this->module->l(' To ', 'bookinghandler'),
                date('d-m-Y', strtotime($bookingProductCartItem['date_to']))
            );

            $item = [
                'id' => $productId,
                'name' => $product['name'],
                'attributes_description' => $description,
                'minimum_quantity' => $product['minimal_quantity'] ? (int) $product['minimal_quantity'] : 1,
                'id_product_attribute' => null,
                'booking_type' => (int) $bookingProductCartItem['booking_type'],
                'date_from' => strtotime($bookingProductCartItem['date_from']),
                'date_to' => strtotime($bookingProductCartItem['date_to']),
                'quantity' => (int) $bookingProductCartItem['quantity'],
                'quantity_available' => max(
                    (int) $bookingProductInfo['quantity'],
                    (int) $WkBookingsCart->getProductBookingQuantityInDateRange($productId, $bookingProductCartItem['date_from'], $bookingProductCartItem['date_to'])
                ),
                'total_price' => $this->priceFormatter->format(
                    $WkBookingProductFeaturePricing->getBookingProductTotalPrice(
                        $productId,
                        $bookingProductCartItem['date_from'],
                        $bookingProductCartItem['date_to'],
                        false,
                        $this->context->currency->id
                    )['total_price_tax_incl'] * (int) $bookingProductCartItem['quantity']
                ),
                'full_price' => $this->priceFormatter->format(Tools::ps_round(
                    $product['price_without_reduction'],
                    2
                )),
                'full_price_val'       => $this->priceFormatter->getPriceData($product['price_without_reduction']),
                'discounted_price_val' => $this->priceFormatter->getPriceData($product['price_with_reduction']),
                'discounted_price' => $this->priceFormatter->format(Tools::ps_round(
                    $product['price_with_reduction'],
                    2
                )),
                'image' => BaseWS::getProductImageUrl(
                    ['link_rewrite' => $product['link_rewrite']],
                    (int) explode('-', $product['id_image'])[1],
                    BaseWS::getImageFormattedName('cart')
                ),
            ];
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Converte a single booking order item into many order items (each for date interval)
     *
     * @param $product
     *
     * @return array
     */
    public function getOrderItem($detail, $currency, $idOrder)
    {
        $bookingProducts = [];
        $WkBookingsOrders = new \WkBookingsOrders();
        $bookingOrderProducts = $WkBookingsOrders->getBookingProductOrderInfo($detail['product_id'], $idOrder);
        foreach ($bookingOrderProducts as $bookingOrderProduct) {
            $bookingProducts[] = [
                'id' => (int) $bookingOrderProduct['id'],
                'product_id' => (int) $bookingOrderProduct['id_product'],
                'description' => $this->module->l('From ', 'bookinghandler').
                    date('d M,Y', strtotime($bookingOrderProduct['date_from'])).
                    $this->module->l(' To ', 'bookinghandler').
                    date('d M,Y', strtotime($bookingOrderProduct['date_to'])),
                'name' => $detail['name'],
                'quantity' => (int) $bookingOrderProduct['quantity'],
                'unit_price' => $this->priceFormatter->format(
                    Tools::ps_round(
                        $bookingOrderProduct['product_real_price_tax_incl'],
                        2
                    ),
                    $currency
                ),
                'unit_price_val' => $this->priceFormatter->getPriceData($bookingOrderProduct['product_real_price_tax_incl'], $currency),
                'total_price' => $this->priceFormatter->format(
                    Tools::ps_round(
                        $bookingOrderProduct['range_feature_price_tax_incl'] * (int) $bookingOrderProduct['quantity'],
                        2
                    ),
                    $currency
                ),
                'total_price_val' => $this->priceFormatter->getPriceData($bookingOrderProduct['range_feature_price_tax_incl'] * (int) $bookingOrderProduct['quantity'], $currency),
                'id_currency' => $detail['id_currency'],
            ];
        }

        return $bookingProducts;
    }

    private function setUnlimitedQuantity($idProduct)
    {
        $id_stock_available = (int) StockAvailable::getStockAvailableIdByProductId($idProduct);
        if ($id_stock_available) {
            $stock_available = new StockAvailable($id_stock_available);
            $stock_available->quantity = 999999999;
            $stock_available->update();
        }
    }
}
