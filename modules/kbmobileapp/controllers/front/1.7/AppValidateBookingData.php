<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to get states for selected country
 */

require_once 'AppCore.php';
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingFacilities.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomCategory.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingProduct.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomType.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingPriceRule.php');

class AppValidateBookingData extends AppCore
{

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if (!(int) Tools::getValue('id_hotel_room', 0)) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Hotel Room id is missing'),
                'AppValidateBookingData'
            )
            );
        } if (!(int) Tools::getValue('id_booking_product', 0)) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Booking product id is missing'),
                'AppValidateBookingData'
            )
            );
        } if (!(int) Tools::getValue('id_product', 0)) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Product id is missing'),
                'AppValidateBookingData'
            )
            );
        } else if (!(int) Tools::getValue('quantity', 0)) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Quantity is missing'),
                'AppValidateBookingData'
            )
            );
        } else if (!Tools::getValue('product_type')) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Product type is missing'),
                'AppValidateBookingData'
            )
            );
        } else if (!Tools::getValue('period_type')) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Period type is missing'),
                'AppValidateBookingData'
            )
            );
        } else if (!Tools::getValue('checkin_date') || !Tools::getValue('checkin_month') || !Tools::getValue('checkin_year')) {
            $this->content['result'] = array(
            'status' => 'failure',
            'message' => parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Checkin date is missing'),
                'AppValidateBookingData'
            )
            );
//        } if (!Tools::getValue('checkout_date') || !Tools::getValue('checkout_month') || !Tools::getValue('checkout_year')) {
//            $this->content['result'] = array(
//                'status' => 'failure',
//                'message' => parent::getTranslatedTextByFileAndISO(
//                        Tools::getValue('iso_code', false), $this->l('Checkout date is missing'), 'AppValidateBookingData'
//                )
//            );
        } else {
            $data = array();
            $id_product = Tools::getValue('id_product');
            $product_type = Tools::getValue('product_type');
            $qty = (int) Tools::getValue('quantity');
            $selected_checkin_day = (int)Tools::getValue('checkin_date', 0);
            $selected_checkin_month = (int)Tools::getValue('checkin_month', 0);
            $selected_checkin_year = (int)Tools::getValue('checkin_year', 0);
            $selected_checkin_hours = (int)Tools::getValue('checkin_hours', 0);
            $selected_checkin_minutes = (int)Tools::getValue('checkin_minutes', 0);
            $selected_checkin_seconds = (int)Tools::getValue('checkin_seconds', 0);
            $checkin_date = array();
            $checkin_date['day'] = $selected_checkin_day;
            $checkin_date['months'] = $selected_checkin_month;
            $checkin_date['year'] = $selected_checkin_year;
            $checkin_date['hours'] = $selected_checkin_hours;
            $checkin_date['minutes'] = $selected_checkin_minutes;
            $checkin_date['seconds'] = $selected_checkin_seconds;
            //die(print_r($checkin_date));
            //$checkin_date = $selected_checkin_year . '-' . $selected_checkin_month . '-' . $selected_checkin_day;
            $selected_checkout_day = (int)Tools::getValue('checkout_date', 0);
            $selected_checkout_month = (int)Tools::getValue('checkout_month', 0);
            $selected_checkout_year = (int)Tools::getValue('checkout_year', 0);
            $selected_checkout_hours = (int)Tools::getValue('checkout_hours', 0);
            $selected_checkout_minutes = (int)Tools::getValue('checkout_minutes', 0);
            $selected_checkout_seconds = (int)Tools::getValue('checkout_seconds', 0);
            $checkout_date = array();
            $checkout_date['day'] = $selected_checkout_day;
            $checkout_date['months'] = $selected_checkout_month;
            $checkout_date['year'] = $selected_checkout_year;
            $checkout_date['hours'] = $selected_checkout_hours;
            $checkout_date['minutes'] = $selected_checkout_minutes;
            $checkout_date['seconds'] = $selected_checkout_seconds;
            //$checkout_date = $selected_checkout_year . '-' . $selected_checkout_month . '-' . $selected_checkout_day;
            $kbselected_date = $checkin_date;
            $time_slot = array();
            $kb_checkin_selected = $checkin_date;
            $kb_checkout_selected = $checkout_date;
            $time_slot_data = Tools::getValue('time_slot');
            if (!empty($time_slot_data)) {
                $return_pos = Tools::strpos($time_slot_data, '( ');
                $returnValue = Tools::substr($time_slot_data, $return_pos);
                $time_slot_price = preg_replace('/[^0-9\.]/', "", $returnValue);
                $time_slot['price'] = $time_slot_price;
                $time_slot_price = Tools::substr($time_slot_data, 0, strpos($time_slot_data, ' ('));
                $time_slot['time'] = $time_slot_price;
            }
            $booking_product_details = KbBookingProduct::getProductDetailsByID($id_product);
            if (!empty($booking_product_details)) {
                if ($booking_product_details['product_type'] == 'hotel_booking') {
                    $this->content['result'] = $this->validateHotelCheckIn($checkin_date, $checkout_date);
                } else {
                    $date_details = json_decode($booking_product_details['date_details'], true);
                    
                    if (!empty($date_details)) {
                        foreach ($date_details as $rec_date) {
                            $from_date = strtotime($rec_date['from_date']);
                            $to_date = strtotime($rec_date['to_date']);
                            $kb_date = date('Y-m-d', mktime($kbselected_date['hours'], $kbselected_date['minutes'], $kbselected_date['seconds'], $kbselected_date['months'], $kbselected_date['day'], $kbselected_date['year']));
                            if ($booking_product_details['period_type'] == 'date_time') {
                                if (strtotime($kb_date) >= $from_date && strtotime($kb_date) <= $to_date) {
                                    if (!empty($qty)) {
                                        if ($product_type == 'daily_rental') {
                                            if (empty($time_slot)) {
                                                $data['status'] = 'failure';
                                                $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                    Tools::getValue('iso_code', false),
                                                    $this->l('Kindly select the time slot.'),
                                                    'AppValidateBookingData'
                                                );
                                                        //$this->module->l('Kindly select the time slot.', 'cart');
                                                break;
                                            }
                                            $kbtime_slot = explode(' - ', $time_slot['time']);
                                            
                                            $kb_date_checkin = date('Y-m-d ' . $kbtime_slot[0], strtotime($kb_date));
                                            $kb_date_checkout = date('Y-m-d ' . $kbtime_slot[1], strtotime($kb_date));
                                            $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_date_checkin, $kb_date_checkout, $qty);
                                        } else {
                                            $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_date, $kb_date, $qty);
                                        }
                                        $disable_days = json_decode($booking_product_details['disable_days'], true);
                                        $dayOfWeek = date("l", strtotime($kb_date));
                                        $day_num = date('N', strtotime($dayOfWeek));
                                        $disable_string = array();
                                        if (!empty($disable_days)) {
                                            $disable_string = array();
                                            for ($i = 0; $i < 7; $i++) {
                                                if (isset($disable_days['disable_days_'.$i])) {
                                                     $disable_string[] = $i;
                                                }
                                            }
                                        }
                                        if (in_array($day_num, $disable_string)) {
                                            $data['status'] = 'failure';
                                            $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                Tools::getValue('iso_code', false),
                                                $this->l('Booking is not allowed on '),
                                                'AppValidateBookingData'
                                            ) . $dayOfWeek ;
                                            break;
                                        }
                                        if (!$available_qty) {
                                            $data['status'] = 'failure';
                                            $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                Tools::getValue('iso_code', false),
                                                $this->l('The item is not available in this quantity. Kindly adjusted the quantity.'),
                                                'AppValidateBookingData'
                                            );

                                            break;
                                        } elseif ($available_qty) {
                                            $data['status'] = 'success';
                                            $price = $this->applyKbRule($id_product, $kb_date, $kb_date, $time_slot['price']);
                                            $data['price'] = Tools::convertPrice($price);
                                            $data['display_price'] =  $this->formatPrice(Tools::convertPrice($price));
                                            break;
                                        }
                                    }
                                }
                            } else {
                                if ($booking_product_details['product_type'] == 'appointment') {
                                    $disable_days = json_decode($booking_product_details['disable_days'], true);
                                    $dayOfWeek = date("l", strtotime($kb_date));
                                    $day_num = date('N', strtotime($dayOfWeek));
                                    $disable_string = array();
                                    if (!empty($disable_days)) {
                                        $disable_string = array();
                                        for ($i = 0; $i < 7; $i++) {
                                            if (isset($disable_days['disable_days_'.$i])) {
                                                 $disable_string[] = $i;
                                            }
                                        }
                                    }
                                    if (in_array($day_num, $disable_string)) {
                                        $data['status'] = 'failure';
                                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                                            Tools::getValue('iso_code', false),
                                            $this->l('Booking is not allowed on '),
                                            'AppValidateBookingData'
                                        ) . $dayOfWeek ;
                                        break;
                                    }
                                    if (strtotime($kb_date) >= $from_date && strtotime($kb_date) <= $to_date) {
                                        if (!empty($qty)) {
                                            $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_date, $kb_date, $qty);
                                            if (!$available_qty) {
                                                $data['status'] = 'failure';
                                                $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                    Tools::getValue('iso_code', false),
                                                    $this->l('The item is not available in this quantity. Kindly adjusted the quantity.'),
                                                    'AppValidateBookingData'
                                                );
                                                break;
                                            } else if ($available_qty) {
                                                $data['status'] = 'success';
                                                $price = $this->applyKbRule($id_product, $kb_date, $kb_date, $rec_date['price']);
                                                $data['price'] =  Tools::convertPrice($price);
                                                $data['display_price'] =  $this->formatPrice(Tools::convertPrice($price));
                                                break;
                                            }
                                        }
                                    }
                                } else if ($product_type == 'daily_rental' ||
                                        $product_type == 'hourly_rental') {
                                    $kb_checkin_date = date('Y-m-d', mktime($kb_checkin_selected['hours'], $kb_checkin_selected['minutes'], $kb_checkin_selected['seconds'], $kb_checkin_selected['months'], $kb_checkin_selected['day'], $kb_checkin_selected['year']));
                                    $kb_checkout_date = date('Y-m-d', mktime($kb_checkout_selected['hours'], $kb_checkout_selected['minutes'], $kb_checkout_selected['seconds'], $kb_checkout_selected['months'], $kb_checkout_selected['day'], $kb_checkout_selected['year']));
//                                    print_r(strtotime($kb_checkin_date). '::' . $from_date);echo '        ';
//                                    print_r(strtotime($kb_checkin_date). '::' . $from_date);echo '
//                                            ';
                                    if (!empty($kb_checkout_date) && !empty($kb_checkin_date)) {
                                        if ((strtotime($kb_checkin_date) >= $from_date && strtotime($kb_checkin_date) <= $to_date) &&
                                                (strtotime($kb_checkout_date) <= $to_date && strtotime($kb_checkout_date) >= $from_date)) {
                                            if ($product_type == 'daily_rental') {
                                                $datediff = strtotime($kb_checkout_date) - strtotime($kb_checkin_date);
                                                $total_days = round($datediff / (60 * 60 * 24));
                                                $min_days = $booking_product_details['min_days'];
                                                $max_days = $booking_product_details['max_days'];
                                                $days_exceed = true;
                                                if (!empty($min_days) && !empty($max_days)) {
                                                    if (((int) $total_days >= (int) $min_days) &&
                                                            ((int) $total_days <= (int) $max_days)) {
                                                        $days_exceed = false;
                                                    }
                                                    if ($days_exceed) {
                                                        $data['status'] = 'failure';
                                                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                            Tools::getValue('iso_code', false),
                                                            $this->l('Booking is not allowed as Min Days should be'),
                                                            'AppValidateBookingData'
                                                        ) . ' ' . $min_days . ' ' .
                                                            parent::getTranslatedTextByFileAndISO(
                                                                Tools::getValue('iso_code', false),
                                                                $this->l('and Max. days should be'),
                                                                'AppValidateBookingData'
                                                            ) . ' ' . $max_days;
                                                        break;
                                                    }
                                                }
                                                $disable_days = json_decode($booking_product_details['disable_days'], true);
                                                $dayOfWeek = date("l", strtotime($kb_date));
                                                $day_num = date('N', strtotime($dayOfWeek));
                                                $disable_string = array();
                                                if (!empty($disable_days)) {
                                                    $disable_string = array();
                                                    for ($i = 0; $i < 7; $i++) {
                                                        if (isset($disable_days['disable_days_'.$i])) {
                                                             $disable_string[] = $i;
                                                        }
                                                    }
                                                }
                                                if (in_array($day_num, $disable_string)) {
                                                    $data['status'] = 'failure';
                                                    $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                        Tools::getValue('iso_code', false),
                                                        $this->l('Booking is not allowed on '),
                                                        'AppValidateBookingData'
                                                    ) . $dayOfWeek ;
                                                    break;
                                                }
                                                if (!empty($qty)) {
                                                    $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_checkin_date, $kb_checkout_date, $qty);
                                                    if (!$available_qty) {
                                                        $data['status'] = 'failure';
                                                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                            Tools::getValue('iso_code', false),
                                                            $this->l('The item is not available in this quantity. Kindly adjusted the quantity.'),
                                                            'AppValidateBookingData'
                                                        );
                                                        break;
                                                    } elseif ($available_qty) {
                                                        $data['status'] = 'success';
                                                        $price = $this->applyKbRule($id_product, $kb_checkin_date, $kb_checkout_date, $rec_date['price']);
                                                        $data['price'] = Tools::convertPrice($price);
                                                        $data['display_price'] = Tools::displayPrice(Tools::convertPrice($price));
                                                        break;
                                                    }
                                                }
                                            } elseif ($product_type == 'hourly_rental') {
                                                $kb_hour_checkin = date('Y-m-d H:i:s', mktime($kb_checkin_selected['hours'], $kb_checkin_selected['minutes'], $kb_checkin_selected['seconds'], $kb_checkin_selected['months'], $kb_checkin_selected['day'], $kb_checkin_selected['year']));
                                                
                                                $kb_hour_checkout = date('Y-m-d H:i:s', mktime($kb_checkout_selected['hours'], $kb_checkout_selected['minutes'], $kb_checkout_selected['seconds'], $kb_checkout_selected['months'], $kb_checkout_selected['day'], $kb_checkout_selected['year']));
                                                $datediff = strtotime($kb_hour_checkout) - strtotime($kb_hour_checkin);
                                                $total_hours = round($datediff / (60 * 60));
                                                
                                                $min_hours = $booking_product_details['min_hours'];
                                                $max_hours = $booking_product_details['max_hours'];
                                                $hrs_exceed = true;
                                                $disable_days = json_decode($booking_product_details['disable_days'], true);
                                                $dayOfWeek = date("l", strtotime($kb_date));
                                                $day_num = date('N', strtotime($dayOfWeek));
                                                $disable_string = array();
                                                if (!empty($disable_days)) {
                                                    $disable_string = array();
                                                    for ($i = 0; $i < 7; $i++) {
                                                        if (isset($disable_days['disable_days_'.$i])) {
                                                             $disable_string[] = $i;
                                                        }
                                                    }
                                                }
                                                if (in_array($day_num, $disable_string)) {
                                                    $data['status'] = 'failure';
                                                    $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                        Tools::getValue('iso_code', false),
                                                        $this->l('Booking is not allowed on '),
                                                        'AppValidateBookingData'
                                                    ) . $dayOfWeek ;
                                                    break;
                                                }
                                                if (!empty($min_hours) && !empty($max_hours)) {
                                                    if (((int) $total_hours >= (int) $min_hours) &&
                                                            ((int) $total_hours <= (int) $max_hours)) {
                                                        $hrs_exceed = false;
                                                    }
                                                    if ($hrs_exceed) {
                                                        $data['status'] = 'failure';
                                                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                            Tools::getValue('iso_code', false),
                                                            $this->l('Booking is not allowed as Min Hours should be'),
                                                            'AppValidateBookingData'
                                                        ) . ' ' . $min_hours . ' ' . parent::getTranslatedTextByFileAndISO(
                                                            Tools::getValue('iso_code', false),
                                                            $this->l('and Max. hours should be'),
                                                            'AppValidateBookingData'
                                                        ) . ' ' . $max_hours;
                                                        break;
                                                    }
                                                }
                                                if (!empty($qty)) {
                                                    $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_hour_checkin, $kb_hour_checkout, $qty);
                                                    if (!$available_qty) {
                                                        $data['status'] = 'failure';
                                                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                                                            Tools::getValue('iso_code', false),
                                                            $this->l('The item is not available in this quantity. Kindly adjusted the quantity.'),
                                                            'AppValidateBookingData'
                                                        );
                                                        break;
                                                    } else {
                                                        $kb_date_rang = $this->kbDateRange($kb_hour_checkin, $kb_hour_checkout, '+1 hours', 'Y-m-d H:i');
                                                        $kb_price = 0;
                                                        if (!empty($kb_date_rang)) {
                                                            foreach ($kb_date_rang as $kb_date_rec) {
                                                                $kb_price += $this->applyKbRule($id_product, $kb_date_rec, $kb_date_rec, $rec_date['price']);
                                                            }
                                                        }
                                                        if ($product_type == 'hourly_rental') {
                                                            $price = $kb_price;
                                                        }
                                                        $data['status'] = 'success';
                                                        $data['price'] = Tools::convertPrice($price);
                                                        $data['display_price'] = $this->formatPrice(Tools::convertPrice($price));
                                                        break;
                                                    }
                                                }
                                            }
                                        }
//                                        else {
//                                            $data['status'] = 'failure';
//                                            $data['message'] = parent::getTranslatedTextByFileAndISO(
//                                            Tools::getValue('iso_code', false), $this->l('The booking is not available on the selected date'), 'AppValidateBookingData'
//                                        );
//                                            break;
//                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (empty($data)) {
                        $data['status'] = 'failure';
                        $data['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false),
                            $this->l('The booking is not available on the selected date'),
                            'AppValidateBookingData'
                        );
                    }
                    $this->content['result'] = $data;
                }
            }
        }
        //$this->content['result'] = $data;
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }

    public function getAvailableQuantityByProduct($id_product, $checkin, $checkout, $qty, $id_room = null)
    {
        $used_qty = 0;
        $available_qty = 0;
        $id_cart = Context::getContext()->cart->id;
        $product_details = KbBookingProduct::getProductDetailsByID($id_product);
        if (!empty($product_details)) {
            $product_type = $product_details['product_type'];
            $product_qty = $product_details['quantity'];
            $period_type = $product_details['period_type'];
            //for appointment
            if ($product_type == 'appointment') {
                $checkin = date('Y-m-d 00:00:00', strtotime($checkin));
                $checkout = date('Y-m-d 23:59:59', strtotime($checkout));
                $order_placed = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'kb_booking_product_order d on (d.id_cart=c.id_cart AND c.id_product=d.`id_product` AND c.id_customization=d.id_customization) WHERE c.id_product=' . (int) $id_product . ' AND c.check_in >= "' . pSQL($checkin) . '" AND c.check_out <="' . pSQL($checkout) . '"');
                if (!empty($order_placed)) {
                    foreach ($order_placed as $order) {
                        $used_qty += $order['qty'];
                    }
                }
                if (!empty($id_cart)) {
                    $cart_details = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'cart_product co on (co.id_cart=c.id_cart AND co.id_product=c.id_product) WHERE c.id_product=' . (int) $id_product . ' AND c.id_cart=' . (int) $id_cart . ' AND c.check_in >= "' . pSQL($checkin) . '" AND c.check_out <="' . pSQL($checkout) . '" GROUP BY c.id_customization');
                    if (!empty($cart_details)) {
                        foreach ($cart_details as $cart_detail) {
                            $used_qty += $cart_detail['qty'];
                        }
                    }
                }
                $available_qty = $product_qty - $used_qty;
                if ($available_qty < 0) {
                    $available_qty = 0;
                }
                if ($available_qty <= 0) {
                    return false;
                } elseif ($qty > $available_qty) {
                    return false;
                }

                return true;
            } elseif ($product_type == 'daily_rental') {
                $checkin = date('Y-m-d', strtotime($checkin));
                $checkout = date('Y-m-d', strtotime($checkout));
                $date_range = $this->kbDateRange($checkin, $checkout, '+1 day', 'Y-m-d', 'hourly');
                $datediff = strtotime($checkout) - strtotime($checkin);
                $total_days = (int) round($datediff / (60 * 60 * 24)) + 1;
                $flag_days = $total_days;
                if (!empty($date_range)) {
                    foreach ($date_range as $range) {
                        $used_qty = 0;
                        $kb_range_checkin = date('Y-m-d', strtotime($range));
                        $order_placed = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'kb_booking_product_order d on (d.id_cart=c.id_cart AND c.id_product=d.`id_product` AND c.id_customization=d.id_customization) WHERE c.id_product=' . (int) $id_product . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out)');
                        if (!empty($order_placed)) {
                            foreach ($order_placed as $order) {
                                $used_qty += $order['qty'];
                            }
                        }
                        if (!empty($id_cart)) {
                            $cart_details = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'cart_product co on (co.id_cart=c.id_cart AND co.id_product=c.id_product) WHERE c.id_product=' . (int) $id_product . ' AND c.id_cart=' . (int) $id_cart . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out) GROUP BY c.id_customization');
                            if (!empty($cart_details)) {
                                foreach ($cart_details as $cart_detail) {
                                    $used_qty += $cart_detail['qty'];
                                }
                            }
                        }
                        $available_qty = (int) $product_qty - (int) $used_qty;
                        if ((int) $available_qty < 0) {
                            $available_qty = 0;
                        }
                        if ((int) $available_qty < (int) $qty) {
                            $flag_days--;
                        }
                    }
                }
                if ($flag_days < $total_days) {
                    return false;
                }
                return true;
            } elseif ($product_type == 'hourly_rental') {
                $date_range = $this->kbDateRange($checkin, $checkout, '+1 hours', 'Y-m-d H:i', 'hourly');
                $datediff = strtotime($checkout) - strtotime($checkin);
                $total_hours = (int) round($datediff / (60 * 60 ));
                $flag_hours = $total_hours;
                if (!empty($date_range)) {
                    foreach ($date_range as $range) {
                        $used_qty = 0;
                        $kb_range_checkin = date('Y-m-d H:i', strtotime($range));
                        $order_placed = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'kb_booking_product_order d on (d.id_cart=c.id_cart AND c.id_product=d.`id_product` AND c.id_customization=d.id_customization) WHERE c.id_product=' . (int) $id_product . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out)');
                        if (!empty($order_placed)) {
                            foreach ($order_placed as $order) {
                                $used_qty += $order['qty'];
                            }
                        }
                        if (!empty($id_cart)) {
                            $cart_details = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'cart_product co on (co.id_cart=c.id_cart AND co.id_product=c.id_product) WHERE c.id_product=' . (int) $id_product . ' AND c.id_cart=' . (int) $id_cart . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out) GROUP BY c.id_customization');
                            if (!empty($cart_details)) {
                                foreach ($cart_details as $cart_detail) {
                                    $used_qty += $cart_detail['qty'];
                                }
                            }
                        }
                        $available_qty = (int) $product_qty - (int) $used_qty;
                        if ((int) $available_qty < 0) {
                            $available_qty = 0;
                        }
                        if ((int) $available_qty < (int) $qty) {
                            $flag_hours--;
                        }
                    }
                }

                if ($flag_hours < $total_hours) {
                    return false;
                }
                return true;
            } elseif ($product_type == 'hotel_booking') {
                $product_qty = 0;
                if (!empty($id_room)) {
                    $room_details = KbBookingProduct::getHotelProductRoomsByID($product_details['id_booking_product'], $id_room);
                    if (!empty($room_details)) {
                        $product_qty = (int) $room_details[0]['room_quantity'];
                    }
                }
                $date_range = $this->kbDateRange($checkin, $checkout);
                $datediff = strtotime($checkout) - strtotime($checkin);
                $total_days = (int) round($datediff / (60 * 60 * 24));
                $flag_days = $total_days;
                if (!empty($date_range)) {
                    foreach ($date_range as $key => $range) {
                        $used_qty = 0;
                        if ($key == 0) {
                            $kb_range_checkin = date('Y-m-d 01:00:00', strtotime($range));
                        } else {
                            $kb_range_checkin = date('Y-m-d  23:59:59', strtotime($range));
                        }
                        $order_placed = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'kb_booking_product_order d on (d.id_cart=c.id_cart AND c.id_product=d.`id_product` AND c.id_customization=d.id_customization) WHERE c.id_product=' . (int) $id_product . ' AND c.id_room=' . (int) $id_room . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out)');
                        if (!empty($order_placed)) {
                            foreach ($order_placed as $order) {
                                $used_qty += $order['qty'];
                            }
                        }
                        if (!empty($id_cart)) {
                            $cart_details = Db::getInstance()->executeS('SELECT c.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_cart c INNER JOIN ' . _DB_PREFIX_ . 'cart_product co on (co.id_cart=c.id_cart AND co.id_product=c.id_product) WHERE c.id_product=' . (int) $id_product . ' AND c.id_cart=' . (int) $id_cart . ' AND c.id_room=' . (int) $id_room . ' AND ("' . pSQL($kb_range_checkin) . '" between c.check_in and c.check_out) GROUP BY c.id_customization');
                            if (!empty($cart_details)) {
                                foreach ($cart_details as $cart_detail) {
                                    $used_qty += $cart_detail['qty'];
                                }
                            }
                        }
                        $available_qty = (int) $product_qty - (int) $used_qty;
                        if ((int) $available_qty < 0) {
                            $available_qty = 0;
                        }
                        if ((int) $available_qty < (int) $qty) {
                            $flag_days--;
                        }
                    }
                }
                if ($flag_days < $total_days) {
                    return false;
                }
                return true;
            }
        }
        if (!empty($id_cart)) {
            if (!empty($product_details)) {
                if ($product_details['id_product'] == $id_product) {
                    foreach ($cart_details as $cart) {
                        if (!empty($id_room)) {
                            if ($id_room != $cart['id_room']) {
                                continue;
                            }
                        }
                        $cart_checkout = strtotime($cart['check_out']);
                        if (strtotime($checkin) <= $cart_checkout) {
                            $used_qty += $cart['quantity'];
                        }
                    }
                }
            }
        }
        if (!empty($product_details)) {
            if ($product_details['product_type'] == 'hotel_booking') {
                if (!empty($id_room)) {
                    $room_details = KbBookingProduct::getHotelProductRoomsByID($product_details['id_booking_product'], $id_room);
                    if (!empty($room_details)) {
                        $available_qty = $room_details[0]['room_quantity'] - $used_qty;
                    }
                }
            } else {
                $available_qty = $product_details['quantity'] - $used_qty;
            }
        }
        if ($available_qty < 0) {
            $available_qty = 0;
        }
        return $available_qty;
    }

    public function checkRuleisApplied($id_product, $check_date)
    {
        $price_rules = KbBookingPriceRule::isPriceRuleApplicable($id_product);
        if (!empty($price_rules)) {
            foreach ($price_rules as $rule) {
                if ($rule['date_selection'] == 'date_range') {
                    if (strtotime($check_date) >= strtotime($rule['start_date']) && strtotime($check_date) <= strtotime($rule['end_date'])) {
                        return $rule;
                    }
                } else {
                    if (strtotime($check_date) == strtotime($rule['particular_date'])) {
                        return $rule;
                    }
                }
            }
        }

        return false;
    }

    public function validateHotelCheckIn($kb_checkin_selected, $kb_checkout_selected)
    {
        $data = array();
        $id_hotel_room = Tools::getValue('id_hotel_room');
        $qty = Tools::getValue('quantity');
        $id_booking_product = Tools::getValue('id_booking_product');
        $id_product = Tools::getValue('id_product');
        $product_type = Tools::getValue('product_type');
        $kb_checkin_date = date('Y-m-d', mktime($kb_checkin_selected['hours'], $kb_checkin_selected['minutes'], $kb_checkin_selected['seconds'], $kb_checkin_selected['months'], $kb_checkin_selected['day'], $kb_checkin_selected['year']));
        $kb_checkout_date = date('Y-m-d', mktime($kb_checkout_selected['hours'], $kb_checkout_selected['minutes'], $kb_checkout_selected['seconds'], $kb_checkout_selected['months'], $kb_checkout_selected['day'], $kb_checkout_selected['year']));
        $room_details = '';

        $booking_product_details = KbBookingProduct::getProductDetailsByID($id_product);
        if ($product_type == 'hotel_booking' && !empty($id_hotel_room)) {
            $room_details = Db::getInstance()->getRow('SELECT r.* FROM ' . _DB_PREFIX_ . 'kb_booking_product_room_facilities_mapping r INNER JOIN ' . _DB_PREFIX_ . 'kb_booking_product p on (p.id_booking_product=r.id_booking_product) WHERE r.id_booking_product=' . (int) $id_booking_product . ' AND id_booking_room_facilities_map=' . (int) $id_hotel_room);
        }
        if (!empty($booking_product_details)) {
            $datediff = strtotime($kb_checkout_date) - strtotime($kb_checkin_date);
            $total_days = round($datediff / (60 * 60 * 24));
            $min_days = $booking_product_details['min_days'];
            $max_days = $booking_product_details['max_days'];
            $days_exceed = true;
            if (!empty($min_days) && !empty($max_days)) {
                if (((int) $total_days >= (int) $min_days) &&
                        ((int) $total_days <= (int) $max_days)) {
                    $days_exceed = false;
                }
                if ($days_exceed) {
                    $data['status'] = 'failure';
                    $data['message'] = parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('Booking is not allowed as Min Days should be'),
                        'AppValidateBookingData'
                    ) . ' ' . $min_days . ' ' . parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('and Max. days should be'),
                        'AppValidateBookingData'
                    ) . ' ' . $max_days;
                    //$this->l('Booking is not allowed as Min Days should be ', 'cart') . ' ' . $min_days . ' ' . $this->module->l('and Max. days should be ', 'cart') . ' ' . $max_days;
                    return $data;
                }
            }

            $date_details = json_decode($booking_product_details['date_details'], true);
            $kb_date_rang = $this->kbDateRange($kb_checkin_date, $kb_checkout_date);
            $hotel_price = 0;
            if (empty($date_details)) {
                $data['status'] = 'failure';
                $data['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('The booking is not available on the selected date'),
                    'AppValidateBookingData'
                );
                return $data;
            }
            if (!empty($kb_date_rang)) {
                foreach ($kb_date_rang as $date_selected) {
                    $kb_date_exist = 0;

                    foreach ($date_details as $rec_date) {
                        $from_date = strtotime($rec_date['from_date']);
                        $to_date = strtotime($rec_date['to_date']);

                        if (strtotime($date_selected) >= $from_date && strtotime($date_selected) <= $to_date) {
                            //additinal price included
                            $kb_date_exist = 1;
                            $kb_price = $this->applyKbRule($id_product, $kb_checkin_date, $kb_checkin_date, $rec_date['price'] + $room_details['price']);
                            $hotel_price += $kb_price;
                        }
                    }
                    if (!$kb_date_exist) {
                        $kb_price = $this->applyKbRule($id_product, $kb_checkin_date, $kb_checkin_date, $room_details['price']);
                        $hotel_price += $kb_price;
                    }
                }
            }
            if (!empty($room_details) && !empty($qty)) {
                $available_qty = $this->getAvailableQuantityByProduct($id_product, $kb_checkin_date, $kb_checkout_date, (int) $qty, $id_hotel_room);
                if (!$available_qty) {
                    $data['status'] = 'failure';
                    $data['message'] = parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('The item is not available in this quantity. Kindly adjusted the quantity.'),
                        'AppValidateBookingData'
                    );
                    return $data;
                    //$this->module->l('The item is not available in this quantity. Kindly adjusted the quantity.', 'cart');
                } else {
                    $price = $hotel_price;
                    $data['status'] = 'success';
                    $data['price'] = Tools::convertPrice($price);
                    $data['display_price'] = $this->formatPrice(Tools::convertPrice($price));
                    return $data;
                }
            }
        }
        if ($data['status'] != "success") {
            $data['status'] = 'failure';
            $data['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('The booking is not available on the selected date.'),
                'AppValidateBookingData'
            );
        }
        return $data;
    }

    public function applyKbRule($id_product, $start = null, $end = null, $price = null)
    {
        $kb_price = '';
        if (!empty($start) && !empty($end)) {
            if (Tools::getValue('product_type') == 'daily_rental') {
                $kb_date_rang = $this->kbDateRange($start, $end, '+1 day', 'Y-m-d', 'hourly');
            } else {
                $kb_date_rang = $this->kbDateRange($start, $end);
            }
            if (!empty($kb_date_rang)) {
                foreach ($kb_date_rang as $kb_rang) {
                    $rule_applied = $this->checkRuleisApplied($id_product, $kb_rang);
                    if (!empty($rule_applied)) {
                        $reduction = $rule_applied['reduction'];
                        $kb_reduce_price = $price;
                        if ($rule_applied['reduction_type'] == 'percentage') {
                            $kb_reduce_price = $price - ($reduction / 100 * $price);
                        } else {
                            $kb_reduce_price = $price - $reduction;
                        }
                        if ($kb_reduce_price < 0) {
                            $kb_reduce_price = 0;
                        }
                        $kb_price += $kb_reduce_price;
                    } else {
                        $kb_price += $price;
                    }
                }
            }
        }
        return $kb_price;
    }

    private function kbDateRange($first, $last, $step = '+1 day', $output_format = 'Y-m-d', $type = null)
    {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        if ((Tools::getValue('period_type') == 'date_time') || (!empty($type) && $type == 'hourly')) {
            while ($current <= $last) {
                $dates[] = date($output_format, $current);
                $current = strtotime($step, $current);
            }
        } else {
            if ($current == $last) {
                $dates[] = date($output_format, $current);
                return $dates;
            }
            while ($current < $last) {
                $dates[] = date($output_format, $current);
                $current = strtotime($step, $current);
            }
        }
        return $dates;
    }
}
