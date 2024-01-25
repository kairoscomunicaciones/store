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
class WkBookingProductDisabledDates extends ObjectModel
{
    public $id_product;
    public $disable_special_days_active;
    public $disabled_dates_slots_active;
    public $disabled_special_days;
    public $disabled_dates_slots;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'wk_booking_product_disabled_dates',
        'primary' => 'id',
        'fields' => [
            'id_product' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
                'shop' => true,
            ],
            'disable_special_days_active' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
                'shop' => true,
            ],
            'disabled_dates_slots_active' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
                'shop' => true,
            ],
            'disabled_special_days' => ['type' => self::TYPE_STRING, 'shop' => true],
            'disabled_dates_slots' => ['type' => self::TYPE_STRING, 'shop' => true],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true],
            'date_upd' => ['type' => self::TYPE_DATE, 'shop' => true],
        ],
    ];

    public function __construct($id = null, $idLang = null, $idShop = null)
    {
        parent::__construct($id, $idLang, $idShop);
        Shop::addTableAssociation('wk_booking_product_disabled_dates', ['type' => 'shop', 'primary' => 'id']);
    }

    public function getBookingProductDisableDates($idProduct)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `' . _DB_PREFIX_ . 'wk_booking_product_disabled_dates` wbpdd
            INNER JOIN `' . _DB_PREFIX_ . 'wk_booking_product_disabled_dates_shop` wbpdds ON (wbpdds.`id` = wbpdd.`id`
            AND wbpdds.`id_shop` = ' . (int) Context::getContext()->shop->id . ')
            WHERE wbpdds.`id_product` = ' . (int) $idProduct
        );
    }

    public function getBookingProductDisableDatesInfoFormatted($idProduct)
    {
        $disableDatesInfo = [];
        $disableDatesInfo['disabledDays'] = [];
        $disableDatesInfo['disabledDates'] = [];
        $bookingDisableDates = $this->getBookingProductDisableDates($idProduct);

        if ($bookingDisableDates) {
            if ($bookingDisableDates['disable_special_days_active'] && $bookingDisableDates['disabled_special_days']) {
                $disableDatesInfo['disabledDays'] = json_decode(
                    $bookingDisableDates['disabled_special_days'],
                    true
                );
            }
            if ($bookingDisableDates['disabled_dates_slots_active'] && $bookingDisableDates['disabled_dates_slots']) {
                $disabledDates = [];
                $totalDaySeconds = 24 * 60 * 60;
                $bookingDisableDatesArr = json_decode($bookingDisableDates['disabled_dates_slots'], true);
                // if product is date range wise booking product
                $objBookingProductInfo = new WkBookingProductInformation();
                $objBookingTimeSlots = new WkBookingProductTimeSlotPrices();
                foreach ($bookingDisableDatesArr as $disableDateRange) {
                    if (($disableDateFrom = $disableDateRange['date_from'])
                        && ($disableDateTo = $disableDateRange['date_to'])
                    ) {
                        if ($bookingProductInfo = $objBookingProductInfo->getBookingProductInfoByIdProduct(
                            $idProduct
                        )) {
                            $disDtFrm = strtotime($disableDateFrom);
                            $disDtTo = strtotime($disableDateTo);
                            for ($date = $disDtFrm; $date <= $disDtTo; $date = ($date + $totalDaySeconds)) {
                                if ($bookingProductInfo['booking_type'] == 1) {
                                    if (!in_array(date('Y-m-d', $date), $disabledDates)) {
                                        $disableDatesInfo['disabledDates'][] = date('Y-m-d', $date);
                                    }
                                } else {
                                    $bookingTimeSlots = $objBookingTimeSlots->getProductTimeSlotsOnDate(
                                        $idProduct,
                                        date('Y-m-d', $date),
                                        true,
                                        2,
                                        false
                                    );

                                    if (empty($bookingTimeSlots)) {
                                        $bookingTimeSlots = $objBookingTimeSlots->getProductTimeSlotsOnDate(
                                            $idProduct,
                                            date('Y-m-d', $date),
                                            true,
                                            2,
                                            true
                                        );
                                    }

                                    $anySlotActive = false;
                                    if ($bookingTimeSlots) {
                                        foreach ($bookingTimeSlots as $slot) {
                                            if ($slot['active']) {
                                                $anySlotActive = 1;
                                            }
                                        }
                                    }

                                    if (!$anySlotActive) {
                                        $disableDatesInfo['disabledDates'][] = date('Y-m-d', $date);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $disableDatesInfo;
    }

    public function getBookingProductDisableDatesInDateRange($idProduct, $dateFrom, $dateTo)
    {
        $bookingDisableDatesInfo = $this->getBookingProductDisableDatesInfoFormatted($idProduct);
        $disabledDays = $bookingDisableDatesInfo['disabledDays'];
        $disabledDates = $bookingDisableDatesInfo['disabledDates'];
        $dateRangeDisabledDates = [];
        if ($idProduct) {
            $objBookingProductInfo = new WkBookingProductInformation();
            if ($bookingProductInfo = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct)) {
                for ($date = strtotime($dateFrom); $date <= strtotime($dateTo); $date = ($date + (24 * 60 * 60))) {
                    if ($bookingProductInfo['booking_type'] == 1
                        && !Configuration::get('WK_CONSIDER_DATE_TO')
                        && $date == strtotime($dateTo)
                    ) {
                        break;
                    }
                    $currentDate = date('Y-m-d', $date);
                    if (!in_array($currentDate, $dateRangeDisabledDates)) {
                        if ($disabledDates) {
                            if (in_array($currentDate, $disabledDates)) {
                                $dateRangeDisabledDates[] = $currentDate;
                            }
                        }
                        if ($disabledDays) {
                            $weekDay = date('w', strtotime($currentDate));
                            if (in_array($weekDay, $disabledDays)) {
                                $dateRangeDisabledDates[] = $currentDate;
                            }
                        }
                    }
                }
            }
        }

        return $dateRangeDisabledDates;
    }

    // return true if date is disabled otherwise false
    public function isDateOrDayDisabled($date, $bookingDisableDatesInfo)
    {
        $disabledDays = $bookingDisableDatesInfo['disabledDays'];
        $disabledDates = $bookingDisableDatesInfo['disabledDates'];
        $flag = false;
        if (!empty($disabledDays)) {
            $weekDay = date('w', $date);
            if (in_array($weekDay, $disabledDays)) {
                $flag = true;
            }
        }
        if (!empty($disabledDates)) {
            if (in_array(date('Y-m-d', $date), $disabledDates)) {
                $flag = true;
            }
        }

        return $flag;
    }
}
