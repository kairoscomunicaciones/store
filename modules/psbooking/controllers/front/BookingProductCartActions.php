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
class PsBookingBookingProductCartActionsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $result = [];
        $this->display_column_left = false;
        $this->display_column_right = false;
        $action = Tools::getValue('action');
        $result = [];
        $m = $this->module;
        if (isset($action) && $action == 'add_booking_product_to_cart') {
            $bookingType = Tools::getValue('booking_type');
            if ($bookingType == 1) {  // date range type bookings
                $dateFrom = date('Y-m-d', strtotime(Tools::getValue('date_from')));
                $dateTo = date('Y-m-d', strtotime(Tools::getValue('date_to')));
                $quantity = Tools::getValue('quantity');
                $idProduct = Tools::getValue('id_product');
                $currentDate = date('Y-m-d');
                // validate values first
                if (!$idProduct) {
                    $this->errors[] = $m->l('Product id is missing.', 'BookingProductCartActions');
                } elseif ($dateFrom == '' || !Validate::isDate($dateFrom)) {
                    $this->errors[] = $m->l('Invalid date from.', 'BookingProductCartActions');
                } elseif ($dateTo == '' || !Validate::isDate($dateTo)) {
                    $this->errors[] = $m->l('Invalid date to.', 'BookingProductCartActions');
                } elseif ($dateFrom < $currentDate) {
                    $msg = $m->l('Date from should not be before current date.', 'BookingProductCartActions');
                    $this->errors[] = $msg;
                } elseif (!Validate::isUnsignedInt($quantity) || !$quantity) {
                    $this->errors[] = $m->l('Invalid quantity.', 'BookingProductCartActions');
                }
                if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                    if ($dateTo < $dateFrom) {
                        $this->errors[] = $m->l('Date to should be date after date from.', 'BookingProductCartActions');
                    }
                } else {
                    if ($dateTo <= $dateFrom) {
                        $this->errors[] = $m->l('Date to should be date after date from.', 'BookingProductCartActions');
                    }
                }
                $objBookingProductInfo = new WkBookingProductInformation();
                $wkBookingsCart = new WkBookingsCart();
                $wkBookingOrder = new WkBookingsOrders();
                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
                $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
                $maxAvailableQuantity = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;
                if (!$maxAvailableQuantity) {
                    $e = $m->l('Required quantity for this date range not available.', 'BookingProductCartActions');
                    $this->errors[] = $e;
                }
                $productQtyToCart = 0;
                if (!count($this->errors)) {
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
                    $objBookingDisableDates = new WkBookingProductDisabledDates();
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
                        $dateFromS = strtotime($dateFrom);
                        $dateToS = strtotime($dateTo);
                        for ($date = $dateFromS; $date <= $dateToS; $date = ($date + (24 * 60 * 60))) {
                            $currentDate = date('Y-m-d', $date);
                            $prevdate = date('Y-m-d', strtotime($currentDate) - 86400);
                            if (in_array($prevdate, $bookingDisableDates)) {
                                $tempDateFrom = $currentDate;
                            }
                            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                                $lastDateCondition = strtotime($currentDate) == strtotime($dateTo)
                                && !in_array($currentDate, $bookingDisableDates);
                            } else {
                                $lastDateCondition = strtotime($currentDate) == strtotime($dateTo)
                                && !in_array($currentDate, $bookingDisableDates)
                                && !in_array($prevdate, $bookingDisableDates);
                            }
                            if ($lastDateCondition) {
                                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                                $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                    $tempDateFrom,
                                    $dateTo
                                );
                            } elseif (strtotime($currentDate) != strtotime($dateTo)
                                && strtotime($currentDate) != strtotime($dateFrom)
                                && !in_array($prevdate, $bookingDisableDates)
                                && in_array($currentDate, $bookingDisableDates)
                            ) {
                                if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                                    $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                        $tempDateFrom,
                                        $prevdate
                                    );
                                } else {
                                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                                    $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                        $tempDateFrom,
                                        $currentDate
                                    );
                                }
                            }
                        }
                    } else {
                        $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                        $bookingDateRanges[] = [
                            'date_from' => $dateFrom,
                            'date_to' => $dateTo,
                            'price' => Tools::displayPrice($productPrice),
                        ];
                        $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays($dateFrom, $dateTo);
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
                                $wkBookingsCart = new WkBookingsCart($dateRangeCartEntryExists['id']);
                                $wkBookingsCart->quantity += $quantity;
                            } else {
                                $wkBookingsCart = new WkBookingsCart();
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
                        $msg = $m->l('This date range is not available for booking.', 'BookingProductCartActions')
                        . $m->l(' Please select another.', 'BookingProductCartActions');
                        $this->errors[] = $msg;
                    }
                } else {
                    $result['status'] = 'ko';
                    $result['errors'] = $this->errors;
                }
                $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
                $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
                $maxAvailableQuantity = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;

                $result['errors'] = $this->errors;
                $result['product_qty_to_cart'] = $productQtyToCart * $quantity;
                $result['available_qty'] = $maxAvailableQuantity;
            } elseif ($bookingType == 2) { // Time Slots type bookings
                $date = date('Y-m-d', strtotime(Tools::getValue('date')));
                $selectedSlots = Tools::getValue('selected_slots');
                $quantity = Tools::getValue('quantity');
                $idProduct = Tools::getValue('id_product');
                $bookingTimeSlotPrice = [];
                $bookingTimeSlotPrice['price_tax_excl'] = 0;

                // validate values first
                if (!$idProduct) {
                    $this->errors[] = $m->l('Product id is missing.', 'BookingProductCartActions');
                }

                if ($selectedSlots) {
                    foreach ($selectedSlots as $slot) {
                        if (empty($slot['quantity']) || !$slot['quantity']) {
                            $this->errors[] = $m->l('Invalid quantity found.', 'BookingProductCartActions');
                            break;
                        } elseif (!Validate::isInt($slot['quantity'])) {
                            $this->errors[] = $m->l('Invalid quantity found.', 'BookingProductCartActions');
                            break;
                        }
                    }
                }

                $totalProductQty = 0;
                if (!count($this->errors)) {
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
                    $objBookingProductInfo = new WkBookingProductInformation();
                    $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);

                    if ($selectedSlots) {
                        $totalTimeSlotFeaturePrice = [];
                        $totalTimeSlotFeaturePrice['total_price_tax_incl'] = 0;
                        $totalTimeSlotFeaturePrice['total_price_tax_excl'] = 0;
                        $timeSlotsInfo = [];
                        $keySlot = 0;
                        $wkBookingOrder = new WkBookingsOrders();
                        foreach ($selectedSlots as $key => $slot) {
                            $wkBookingsCart = new WkBookingsCart();
                            $objBookingSlot = new WkBookingProductTimeSlotPrices($slot['id_slot']);
                            $bookedSlotQuantity = $wkBookingOrder->getProductTimeSlotOrderedQuantity(
                                $idProduct,
                                $date,
                                $objBookingSlot->time_slot_from,
                                $objBookingSlot->time_slot_to,
                                1
                            );

                            $maxAvailableQuantity = $objBookingSlot->quantity - $bookedSlotQuantity;
                            if ($maxAvailableQuantity >= $slot['quantity']) {
                                $bookingTimeSlotPrice['price_tax_excl'] = $objBookingSlot->price;
                                $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                                $priceTaxIncl = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                                $bookingTimeSlotPrice['price_tax_incl'] = $priceTaxIncl;
                                $timeSlotFeaturePrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $date,
                                    $date,
                                    $bookingTimeSlotPrice,
                                    $this->context->currency->id
                                );
                                $totalTimeSlotFeaturePrice['total_price_tax_incl'] +=
                                $timeSlotFeaturePrice['total_price_tax_incl'] * $slot['quantity'];
                                $totalTimeSlotFeaturePrice['total_price_tax_excl'] +=
                                $timeSlotFeaturePrice['total_price_tax_excl'] * $slot['quantity'];

                                $dateRangeCartEntryExists = $wkBookingsCart->cartProductEntryExistsForTimeSlot(
                                    $this->context->cart->id,
                                    $idProduct,
                                    $date,
                                    $objBookingSlot->time_slot_from,
                                    $objBookingSlot->time_slot_to
                                );
                                if ($dateRangeCartEntryExists) {
                                    $wkBookingsCart = new WkBookingsCart($dateRangeCartEntryExists['id']);
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
                                    $e = $m->l('Some error occurred while saving cart.', 'BookingProductCartActions');
                                    $this->errors[] = $e;
                                }
                                $timeSlotsInfo[$keySlot]['slot_id'] = $slot['id_slot'];
                                $timeSlotsInfo[$keySlot]['slot_from'] = $objBookingSlot->time_slot_from;
                                $timeSlotsInfo[$keySlot]['slot_to'] = $objBookingSlot->time_slot_to;
                                $timeSlotsInfo[$keySlot]['quantity_avail'] = $maxAvailableQuantity - $slot['quantity'];
                                $timeSlotsInfo[$keySlot]['quantity'] = $slot['quantity'];
                                ++$keySlot;
                            } else {
                                $e = $m->l('Required quantity not available for slot ', 'BookingProductCartActions');
                                $this->errors[] = $e . $objBookingSlot->time_slot_from . ' - '
                                . $objBookingSlot->time_slot_to;
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
                        $this->errors[] = $m->l('No time slot is selected.', 'BookingProductCartActions');
                    }
                    $result['status'] = 'ok';
                    $result['totalQty'] = (int) $totalProductQty;
                    $result['errors'] = $this->errors;
                } else {
                    $result['status'] = 'ko';
                    $result['errors'] = $this->errors;
                }
            }
            exit(json_encode($result));
        } elseif (isset($action) && $action == 'booking_product_price_calc') {
            // for price calculation while changing dates and slots
            $dateFrom = date('Y-m-d', strtotime(Tools::getValue('date_from')));
            $dateTo = date('Y-m-d', strtotime(Tools::getValue('date_to')));
            $quantity = Tools::getValue('quantity');
            $idProduct = Tools::getValue('id_product');
            $idCart = Tools::getValue('id_cart');
            $currentDate = date('Y-m-d');
            // validate values first
            if (!$idProduct) {
                $this->errors[] = $m->l('Product id is missing.', 'BookingProductCartActions');
            } elseif ($dateFrom == '' || !Validate::isDate($dateFrom)) {
                $this->errors[] = $m->l('Invalid date from.', 'BookingProductCartActions');
            } elseif ($dateTo == '' || !Validate::isDate($dateTo)) {
                $this->errors[] = $m->l('Invalid date to.', 'BookingProductCartActions');
            } elseif ($dateFrom < $currentDate) {
                $msg = $m->l('Date from should not be before current date.', 'BookingProductCartActions');
                $this->errors[] = $msg;
            }
            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                if ($dateTo < $dateFrom) {
                    $this->errors[] = $m->l('Date to should be date after date from.', 'BookingProductCartActions');
                }
            } else {
                if ($dateTo <= $dateFrom) {
                    $this->errors[] = $m->l('Date to should be date after date from.', 'BookingProductCartActions');
                }
            }
            if (!count($this->errors)) {
                $objBookingProductInfo = new WkBookingProductInformation();
                $wkBookingOrder = new WkBookingsOrders();
                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1, $idCart);
                $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
                $result['max_avail_qty'] = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;

                // Data to show Disables dates (Disable dates/slots tab)
                $objBookingDisableDates = new WkBookingProductDisabledDates();
                // get booking product disable dates
                $bookingDisableDates = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateTo
                );
                $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                $productPriceTotal = 0;
                if ($bookingDisableDates && count($bookingDisableDates)) {
                    $tempDateFrom = $dateFrom;
                    $bookingDateRanges = [];
                    $dateFromS = strtotime($dateFrom);
                    $dateToS = strtotime($dateTo);
                    for ($date = $dateFromS; $date <= $dateToS; $date = ($date + (24 * 60 * 60))) {
                        $currentDate = date('Y-m-d', $date);
                        $prevdate = date('Y-m-d', strtotime($currentDate) - 86400);
                        if (in_array($prevdate, $bookingDisableDates)) {
                            $tempDateFrom = $currentDate;
                        }
                        if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                            $lastDateCondition = (strtotime($currentDate) == strtotime($dateTo)
                            && !in_array($currentDate, $bookingDisableDates));
                        } else {
                            $lastDateCondition = (strtotime($currentDate) == strtotime($dateTo)
                            && !in_array($currentDate, $bookingDisableDates)
                            && !in_array($prevdate, $bookingDisableDates));
                        }
                        if ($lastDateCondition) {
                            $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
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
                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                        $idProduct,
                        $dateFrom,
                        $dateTo,
                        false,
                        $this->context->currency->id
                    );
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                        if ($totalPrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                            $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_incl'] * $quantity;
                            $result['productPriceRegular'] = Tools::displayPrice($productFeaturePriceRegular);
                        }
                    } elseif ($priceDisplay == 1) {
                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                        if ($totalPrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                            $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_excl'];
                            $result['productPriceRegular'] = Tools::displayPrice($productFeaturePriceRegular);
                        }
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
            if (!count($this->errors)) {
                $result['status'] = 'ok';
                $result['dateRangesBookingInfo'] = $bookingDateRanges;
                $result['productPrice'] = Tools::displayPrice($productPriceTotal);
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $this->errors;
            }
            exit(json_encode($result));
        } elseif (isset($action) && $action == 'booking_product_time_slots') {
            $date = date('Y-m-d', strtotime(Tools::getValue('date')));
            $quantity = Tools::getValue('quantity');
            $idProduct = Tools::getValue('id_product');
            $idCart = Tools::getValue('id_cart');
            $objTimeSlots = new WkBookingProductTimeSlotPrices();
            $wkBookingOrder = new WkBookingsOrders();
            $bookingTimeSlots = $objTimeSlots->getProductTimeSlotsOnDate($idProduct, $date, true, 1, false);
            if (empty($bookingTimeSlots)) {
                $bookingTimeSlots = $objTimeSlots->getProductTimeSlotsOnDate($idProduct, $date, true, 1, true);
            }
            if ($bookingTimeSlots) {
                $bookingTimeSlotPrice = false;
                $bookingTimeSlotPriceToday = false;
                $objBookingProductInfo = new WkBookingProductInformation();
                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                $flag = 0;
                $totalSlotsQty = 0;
                foreach ($bookingTimeSlots as $key => $timeSlot) {
                    $bookedSlotQuantity = $wkBookingOrder->getProductTimeSlotOrderedQuantity(
                        $idProduct,
                        $date,
                        $timeSlot['time_slot_from'],
                        $timeSlot['time_slot_to'],
                        1,
                        $idCart
                    );
                    $availableQty = $timeSlot['quantity'] - $bookedSlotQuantity;
                    $bookingTimeSlots[$key]['available_qty'] = $availableQty;
                    $bookingTimeSlots[$key]['price_tax_excl'] = $timeSlot['price'];

                    $totalSlotsQty += $bookingProductInformation['quantity'] - $bookedSlotQuantity;

                    $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                    $bookingTimeSlots[$key]['price_tax_incl'] = $timeSlot['price'] * ((100 + $taxRate) / 100);
                    $bookingTimeSlotPrice['price_tax_excl'] = $bookingTimeSlots[$key]['price_tax_excl'];
                    $bookingTimeSlotPrice['price_tax_incl'] = $bookingTimeSlots[$key]['price_tax_incl'];
                    if ($flag == 0 && $bookingTimeSlots[$key]['available_qty']) {
                        $bookingTimeSlots[$key]['checked'] = 1;
                        $bookingTimeSlotPriceToday['price_tax_excl'] = $bookingTimeSlots[$key]['price_tax_excl'];
                        $bookingTimeSlotPriceToday['price_tax_incl'] = $bookingTimeSlots[$key]['price_tax_incl'];
                        $flag = 1;
                    } else {
                        $bookingTimeSlots[$key]['checked'] = 0;
                    }
                    $totalFeaturePrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                        $idProduct,
                        $date,
                        $date,
                        $bookingTimeSlotPrice,
                        $this->context->currency->id
                    );
                    if ($totalFeaturePrice) {
                        $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                        if (!$priceDisplay || $priceDisplay == 2) {
                            $bookingTimeSlots[$key]['formated_slot_price'] = Tools::displayPrice(
                                $totalFeaturePrice['total_price_tax_incl']
                            );
                            if (Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT') && $totalFeaturePrice['have_price_rule']) {
                                $bookingTimeSlots[$key]['formated_slot_price_regular'] = Tools::displayPrice(
                                    $totalFeaturePrice['regular_total_price_tax_incl']
                                );
                            }
                        } elseif ($priceDisplay == 1) {
                            $bookingTimeSlots[$key]['formated_slot_price'] = Tools::displayPrice(
                                $totalFeaturePrice['total_price_tax_excl']
                            );
                            if (Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT') && $totalFeaturePrice['have_price_rule']) {
                                $bookingTimeSlots[$key]['formated_slot_price_regular'] = Tools::displayPrice(
                                    $totalFeaturePrice['regular_total_price_tax_excl']
                                );
                            }
                        }
                    }
                }
                if ($flag == 0 && !$bookingTimeSlotPriceToday) {
                    $bookingTimeSlotPriceToday['price_tax_excl'] = 0;
                    $bookingTimeSlotPriceToday['price_tax_incl'] = 0;
                }
                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                    $idProduct,
                    $date,
                    $date,
                    $bookingTimeSlotPriceToday,
                    $this->context->currency->id
                );
                if ($totalPrice) {
                    $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productFeaturePrice = Tools::displayPrice($totalPrice['total_price_tax_incl']);
                        if ($totalPrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                            $result['productRegularTotalFeaturePriceFormated'] =
                            Tools::displayPrice($totalPrice['regular_total_price_tax_incl']);
                        }
                    } elseif ($priceDisplay == 1) {
                        $productFeaturePrice = Tools::displayPrice($totalPrice['total_price_tax_excl']);
                        if ($totalPrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                            $result['productRegularTotalFeaturePriceFormated'] =
                            Tools::displayPrice($totalPrice['regular_total_price_tax_excl']);
                        }
                    }
                }
                $result['totalSlotsQty'] = $totalSlotsQty;
                $result['bookingTimeSlots'] = $bookingTimeSlots;
                $result['productTotalFeaturePriceFormated'] = $productFeaturePrice;
            }
            if (!count($bookingTimeSlots)) {
                $result['bookingTimeSlots'] = 'empty';
                $result['productTotalFeaturePriceFormated'] = Tools::displayPrice(0);
            }

            $result['status'] = 'ok';

            exit(json_encode($result));
        } elseif (isset($action) && $action == 'booking_product_time_slots_price_calc') {
            $date = date('Y-m-d', strtotime(Tools::getValue('date')));
            $selectedSlots = Tools::getValue('selected_slots');
            $idProduct = Tools::getValue('id_product');
            $bookingTimeSlotPrice = [];
            $bookingTimeSlotPrice['price_tax_excl'] = 0;
            $totalTimeSlotFeaturePrice['total_price_tax_incl'] = 0;
            $totalTimeSlotFeaturePrice['regular_total_price_tax_incl'] = 0;
            $totalTimeSlotFeaturePrice['total_price_tax_excl'] = 0;
            $totalTimeSlotFeaturePrice['regular_total_price_tax_excl'] = 0;
            foreach ($selectedSlots as $slot) {
                $objBookingSlot = new WkBookingProductTimeSlotPrices($slot['id_slot']);
                $bookingTimeSlotPrice['price_tax_excl'] = $objBookingSlot->price;
                $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                $priceTaxIncl = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                $bookingTimeSlotPrice['price_tax_incl'] = $priceTaxIncl;
                $timeSlotFeaturePrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                    $idProduct,
                    $date,
                    $date,
                    $bookingTimeSlotPrice,
                    $this->context->currency->id
                );
                $totalTimeSlotFeaturePrice['total_price_tax_incl'] +=
                $timeSlotFeaturePrice['total_price_tax_incl'] * $slot['quantity'];
                if ($timeSlotFeaturePrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                    $totalTimeSlotFeaturePrice['regular_total_price_tax_incl'] +=
                    $timeSlotFeaturePrice['regular_total_price_tax_incl'] * $slot['quantity'];
                }
                $totalTimeSlotFeaturePrice['total_price_tax_excl'] +=
                $timeSlotFeaturePrice['total_price_tax_excl'] * $slot['quantity'];
                if ($timeSlotFeaturePrice['have_price_rule'] && Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')) {
                    $totalTimeSlotFeaturePrice['regular_total_price_tax_excl'] +=
                    $timeSlotFeaturePrice['regular_total_price_tax_excl'] * $slot['quantity'];
                }
            }
            $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
            if (!$priceDisplay || $priceDisplay == 2) {
                $productPrice = $totalTimeSlotFeaturePrice['total_price_tax_incl'];
                if ($totalTimeSlotFeaturePrice['regular_total_price_tax_incl'] > 0) {
                    $result['productPriceRegular'] = Tools::displayPrice($totalTimeSlotFeaturePrice['regular_total_price_tax_incl']);
                }
            } elseif ($priceDisplay == 1) {
                $productPrice = $totalTimeSlotFeaturePrice['total_price_tax_excl'];
                if ($totalTimeSlotFeaturePrice['regular_total_price_tax_excl'] > 0) {
                    $result['productPriceRegular'] = Tools::displayPrice($totalTimeSlotFeaturePrice['regular_total_price_tax_excl']);
                }
            }
            if (!count($this->errors)) {
                $result['status'] = 'ok';
                $result['productPrice'] = Tools::displayPrice($productPrice);
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $this->errors;
            }
            exit(json_encode($result));
        } elseif (isset($action) && $action == 'remove_booking_product_from_cart') {
            $idCartBooking = Tools::getValue('id_cart_booking');
            $idProduct = Tools::getValue('id_product');
            $objWkBookingsCart = new WkBookingsCart($idCartBooking);
            $bookingType = $objWkBookingsCart->booking_type;
            if ($bookingType == 1) {
                $daysCount = (int) WkBookingProductFeaturePricing::getNumberOfDays(
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
                if ($objWkBookingsCart->delete()) {
                    exit(
                        json_encode(
                            [
                                'status' => 'ok',
                                'msg' => $m->l('Successfully cart product updated.', 'BookingProductCartActions'),
                            ]
                        )
                    );
                } else {
                    exit(
                        json_encode(
                            [
                                'status' => 'failed',
                                'msg' => $m->l('Error while deleting product from cart.', 'BookingProductCartActions'),
                            ]
                        )
                    );
                }
            } else {
                exit(
                    json_encode(
                        [
                            'status' => 'failed',
                            'msg' => $m->l('Error while updating cart product.', 'BookingProductCartActions'),
                        ]
                    )
                );
            }
        } elseif (isset($action) && $action == 'checkLastProductAdded') {
            $lastProductAdded = $this->context->cart->getLastProduct();
            $lastProductIdAdded = $lastProductAdded['id_product'];
            $objBookingProductInfo = new WkBookingProductInformation();
            if ($objBookingProductInfo->getBookingProductInfoByIdProduct($lastProductIdAdded)) {
                $result['status'] = 'ok';
                $result['last_product_added'] = $lastProductIdAdded;
            } else {
                $result['status'] = 'ko';
                $result['last_product_added'] = $lastProductIdAdded;
            }
            exit(json_encode($result));
        }
    }
}
