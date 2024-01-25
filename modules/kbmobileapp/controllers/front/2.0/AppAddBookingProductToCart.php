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
 * API to add product into cart call from product page of APP
 */

require_once 'AppCore.php';
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingFacilities.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomCategory.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingProduct.php');
require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomType.php');
class AppAddBookingProductToCart extends AppCore
{
    private $product = null;

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        $product_data = Tools::getValue('cart_products', json_encode(array()));
        
        $product_data = json_decode($product_data);
        
        if (empty($product_data)) {
            $this->content['cart_add_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product data is missing'),
                    'AppAddBookingProductToCart'
                )
            );
            $this->writeLog('Product data is missing.');
        } else {
            //die(print_r($product_data));
            $id_product = $product_data->cart_products[0]->id_product;
            if (empty($id_product)) {
                $id_product = 0;
            }
            $this->product = new Product(
                $id_product,
                true,
                $this->context->language->id,
                $this->context->shop->id,
                $this->context
            );
            if (!Validate::isLoadedObject($this->product)) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product not found'),
                    'AppAddBookingProductToCart'
                );
                $this->writeLog('Product with the provided data is not found.');
            } else {
                $cart_id = Tools::getValue('session_data', '');
                if (empty($cart_id)) {
                    /* Add new cart to save product data */
                    $this->context->cart->id_currency = $this->context->currency->id;
                    $this->context->cart->add();
                    if ($this->context->cart->id) {
                        $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    }
                } else {
                    /**
                     * Passes language id while creating new cart
                     * TGapr2023 LangId_cart
                     * @date 28-04-2023
                     * @modifier Tanisha Gupta
                     */
                    $lang_id = (int)Language::getIdByIso(Tools::getValue('iso_code', ''));
                    $this->context->cart = new Cart($cart_id, $lang_id);
                    if (!Validate::isLoadedObject($this->context->cart)) {
                        $this->context->cart->id_currency = $this->context->currency->id;
                        $this->context->cart->add();
                    }
                    $this->context->cart->id_currency = $this->context->currency->id;
                    if ($this->context->cart->id) {
                        $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    }
                }
                if ($this->product->customizable) {
                    $response = $this->addProductInCart($product_data);
                    $id_customise = $response['id_customization'];
                }
                
                $qty = $product_data->cart_products[0]->quantity;
                $this->addKbProduct($id_product, $id_customise, $qty);
//                /*start:changes made by aayushi on 15th March 2019 to update cart count while adding product to the cart*/
                $this->content['total_cart_items'] = Cart::getNbProducts($this->context->cart->id);
//                /*end:changes made by aayushi on 15th March 2019 to update cart count while adding product to the cart*/
                $this->content['session_data'] = (int)$this->context->cart->id;
            }
        }

        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }

    

    /**
     * Add product into cart with provided quantity
     *
     * @param int $id_product product id
     * @param int $id_product_attribute product attruibute id
     * @param int $qty product quantity
     */
    public function addKbProduct($id_product, $id_customise, $qty)
    {
        
        $id_product_attribute = '';
        if ($qty == 0) {
            $qty = 1;
        }
        if (!empty($id_product_attribute)) {
            /**
             * Added PS version 8 condition as Attribute class change, which has been renamed to ProductAttribute
             * TGfeb2023 attribute_class
             * @date 17-02-2023
             * @author Tanisha Gupta
             */
            if (_PS_VERSION_ >= '8.0.0') {
                $minimal_quantity = (int) ProductAttribute::getAttributeMinimalQty($id_product_attribute);
            }else{
                $minimal_quantity = (int) Attribute::getAttributeMinimalQty($id_product_attribute);
            }
        } else {
            $minimal_quantity = (int) $this->product->minimal_quantity;
        }
        if ($minimal_quantity == 0) {
            $minimal_quantity = 1;
        }
        if ((int) $qty < $minimal_quantity) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Add minimum Quantity of product'),
                'AppAddBookingProductToCart'
            );
            $this->writeLog('Error in adding product into cart');
        } else {
            $update_status = $this->context->cart->updateQty($qty, $id_product, $id_product_attribute, $id_customise);
            //die(print_r($update_status));
            if (!$update_status) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Unable to add product into the cart'),
                    'AppAddBookingProductToCart'
                );
                $this->writeLog('Error in adding product into cart');
            } else {
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product successfully Added'),
                    'AppAddBookingProductToCart'
                );
            }
        }
    }
    public function addProductInCart($product_data)
    {  
        $product_data = $product_data->cart_products[0];
        $id_product = $product_data->id_product;
        $id_booking_product = $product_data->id_booking_product;
        $product_type = $product_data->product_type;
        $period_type = $product_data->period_type;
        $service_type = $product_data->service_type;
        $price = $product_data->price;
        $qty = $product_data->quantity;
        $kb_checkin_selected = (array)$product_data->kb_checkin_selected;
        $kb_checkout_selected = (array)$product_data->kb_checkout_selected;
        if (empty($kb_checkin_selected['minutes'])) {
            $kb_checkin_selected['minutes'] = 0;
        }
        if (empty($kb_checkin_selected['months'])) {
            $kb_checkin_selected['months'] = 0;
        }
        if (empty($kb_checkin_selected['seconds'])) {
            $kb_checkin_selected['seconds'] = 0;
        }
        if (empty($kb_checkin_selected['date'])) {
            $kb_checkin_selected['date'] = 0;
        }
        if (empty($kb_checkin_selected['hours'])) {
            $kb_checkin_selected['hours'] = 0;
        }
        if (empty($kb_checkin_selected['day'])) {
            $kb_checkin_selected['day'] = 0;
        }
        if (empty($kb_checkin_selected['year'])) {
            $kb_checkin_selected['year'] = 0;
        }
        
        if (empty($kb_checkout_selected['minutes'])) {
            $kb_checkout_selected['minutes'] = 0;
        }
        if (empty($kb_checkout_selected['months'])) {
            $kb_checkout_selected['months'] = 0;
        }
        if (empty($kb_checkout_selected['seconds'])) {
            $kb_checkout_selected['seconds'] = 0;
        }
        if (empty($kb_checkout_selected['date'])) {
            $kb_checkout_selected['date'] = 0;
        }
        if (empty($kb_checkout_selected['hours'])) {
            $kb_checkout_selected['hours'] = 0;
        }
        if (empty($kb_checkout_selected['day'])) {
            $kb_checkout_selected['day'] = 0;
        }
        if (empty($kb_checkout_selected['year'])) {
            $kb_checkout_selected['year'] = 0;
        }
        $room = $product_data->id_hotel_room;
        $rec_data = $product_data->time_slot;
        $data = array();
        if (empty($room)) {
            $room = '';
        }
        if (!empty($id_product) && !empty($id_booking_product)) {
            //$validate = $this->validateCheckInDate();
            $validate = array();
            $validate['success'] = true;
            $validate['price'] = $product_data->price;
            if (isset($validate['success'])) {
                $check_in = '';
                $check_out = '';
                $price = $validate['price'];
                $check_in = date('Y-m-d', mktime($kb_checkin_selected['hours'], $kb_checkin_selected['minutes'], $kb_checkin_selected['seconds'], $kb_checkin_selected['months'], $kb_checkin_selected['date'], $kb_checkin_selected['year']));
                if ($product_type != 'appointment') {
                    $check_out = date('Y-m-d', mktime($kb_checkout_selected['hours'], $kb_checkout_selected['minutes'], $kb_checkout_selected['seconds'], $kb_checkout_selected['months'], $kb_checkout_selected['date'], $kb_checkout_selected['year']));
                }

                if ($product_type == 'hourly_rental') {
                    if ($period_type == 'date') {
                        $check_in = date('Y-m-d H:i:s', mktime($kb_checkin_selected['hours'], $kb_checkin_selected['minutes'], $kb_checkin_selected['seconds'], $kb_checkin_selected['months'], $kb_checkin_selected['date'], $kb_checkin_selected['year']));
                        $check_out = date('Y-m-d H:i:s', mktime($kb_checkout_selected['hours'], $kb_checkout_selected['minutes'], $kb_checkout_selected['seconds'], $kb_checkout_selected['months'], $kb_checkout_selected['date'], $kb_checkout_selected['year']));
                    }
                }
                $response = array();
                $pro_obj = new Product((int) $id_product);
                
                if (!$this->context->cart->id) {
                    $this->context->cart->add();
                    $this->context->cookie->id_cart = (int) $this->context->cart->id;
                }
                if (!$field_ids = $pro_obj->getCustomizationFieldIds()) {
                        $response['error'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false),
                            $this->l('Unable to add product to cart, please try again.'),
                            'AppAddBookingProductToCart'
                        );
                    return $response;
                }

                $authorized_text_fields = array();
                foreach ($field_ids as $field_id) {
                    if ($field_id['type'] == Product::CUSTOMIZE_TEXTFIELD) {
                        $authorized_text_fields[(int) $field_id['id_customization_field']] = 'textField' . (int) $field_id['id_customization_field'];
                    }
                }
                
                $indexes = array_flip($authorized_text_fields);
                $counter = 1;
                $is_added = false;
                $id_customization = 0;
                $kbcheck_in = $check_in;
                $kbcheck_out = $check_out;
                if ($product_type == 'daily_rental') {
                    if ($period_type == 'date') {
                        $check_out = date('Y-m-d 23:59:59', strtotime($check_out));
                    } else {
                        $check_out = date('Y-m-d 23:59:59', strtotime($check_in));
                    }
                }
                foreach ($indexes as $field) {
                    if (!empty($rec_data)) {
                        $return_pos = Tools::strpos($rec_data, '( ');
                        $returnValue = Tools::substr($rec_data, $return_pos);
                        $time_slot_price = preg_replace('/[^0-9\.]/', "", $returnValue);
                        $time_slot_time = Tools::substr($rec_data, 0, strpos($rec_data, ' ('));
                    }
                    $value = '';
                    if ($period_type != 'date') {
                        $kbcheck_in = $check_in . ' Slot - ' . $time_slot_time;
                    }
                    if ($product_type == 'appointment') {
                        $check_out = date('Y-m-d 23:59:59', strtotime($check_out));
                        if ($counter == 1) {
                            $value = $kbcheck_in;
                            $check_out = $kbcheck_in;
                        } elseif ($counter == 2) {
                            $service = '';
                            if ($service_type == 'home_service') {
                                $service = parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false),
                                    $this->l('Home'),
                                    'AppAddBookingProductToCart'
                                );
                            } elseif ($service_type == 'branch') {
                                $service = parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false),
                                    $this->l('Branch'),
                                    'AppAddBookingProductToCart'
                                );
                            }
                            $value = $service;
                        }
                    } elseif ($product_type == 'daily_rental') {
                        if ($counter == 1) {
                            $value = $kbcheck_in;
                        } elseif ($counter == 2) {
                            if ($period_type == 'date') {
                                $value = $kbcheck_out;
                            } else {
                                $value = '';
                            }
                        } elseif ($counter == 3) {
                            if ($period_type == 'date') {
                                $datediff = strtotime($kbcheck_out) - strtotime($check_in);
                                $total_days = round($datediff / (60 * 60 * 24));
                                $value = $total_days + 1;
                            } else {
                                $value = 1;
                            }
                        }
                    } elseif ($product_type == 'hourly_rental') {
                        if ($counter == 1) {
                            $value = $kbcheck_in;
                        } elseif ($counter == 2) {
                            if ($period_type == 'date') {
                                $value = $check_out;
                            } else {
                                $value = '';
                            }
                        } elseif ($counter == 3) {
                            if ($period_type == 'date') {
                                $datediff = strtotime($check_out) - strtotime($check_in);
                                $total_hours = ceil($datediff / (60 * 60));
                                $value = $total_hours;
                            } else {
                                $time_slot = Tools::getValue('time_slot');
                                if (!empty($time_slot)) {
                                    $return_pos = Tools::strpos($time_slot, '( ');
                                    $returnValue = Tools::substr($time_slot, $return_pos);
                                    $time_slot_price = preg_replace('/[^0-9\.]/', "", $returnValue);
                                    $time_slot_time = Tools::substr($time_slot, 0, strpos($time_slot, ' ('));
                                }
                                if (!empty($time_slot)) {
                                    $time_array = explode(' - ', $time_slot_time);
                                    if (isset($time_array[0]) && isset($time_array[1])) {
                                        $datediff = strtotime($time_array[1]) - strtotime($time_array[0]);
                                        $total_hours = ceil($datediff / (60 * 60));
                                        $value = $total_hours;
                                    }
                                }
                            }
                        }
                    } elseif ($product_type == 'hotel_booking') {
                        /*To integrate the app with kbbookingproduct module by default */
                        $room_info = KbBookingProduct::getHotelProductRoomsByID($id_booking_product, $room);
                        $room_type = '';
                        $room_category = '';
                        if (!empty($room_info)) {
                            $room_type = KbBookingRoomType::getAvailableRoomTypeByID($room_info[0]['id_room_type']);
                            $room_category = KbBookingRoomCategory::getRoomCategoryNameByID($room_info[0]['id_room_category']);
                        }
                        if ($counter == 1) {
                            $value = $check_in;
                        } elseif ($counter == 2) {
                            $value = $check_out;
                        } elseif ($counter == 3) {
                            $value = (!empty($room_type)) ? $room_type['room_name'] : '';
                        } elseif ($counter == 4) {
                            $value = $room_category;
                        } elseif ($counter == 5) {
                            $datediff = strtotime($check_out) - strtotime($check_in);
                            $total_hours = round($datediff / (60 * 60 * 24));
                            $value = $total_hours;
                        }
                    }
                    $is_added = $this->context->cart->addTextFieldToProduct($pro_obj->id, $field, Product::CUSTOMIZE_TEXTFIELD, $value);
                    $id_customization = Db::getInstance()->getValue('SELECT id_customization FROM ' . _DB_PREFIX_ . 'customized_data ORDER BY id_customization DESC');
                    $counter++;
                }
                if ($is_added) {
                    if (!empty($id_customization)) {
                        Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'kb_booking_product_cart set id_room=' . (int) $room . ',check_out="' . pSQL($check_out) . '",check_in="' . pSQL($check_in) . '", id_customization=' . (int) $id_customization . ',id_cart=' . (int) $this->context->cart->id . ',id_product=' . (int) $id_product . ', price="' . pSQL($price) . '",qty=' . (int) $qty . ', date_add=now(),date_upd=now()');
                        $response['id_customization'] = $id_customization;
                    }
                    $response['success'] = true;
                    return $response;
                }
            } else {
                $response['error'] = (isset($validate['error'])) ? $validate['error'] :
                        parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false),
                            $this->l('The booking is not available on the selected date.'),
                            'AppAddBookingProductToCart'
                        );
                return $response;
            }
        }
        return $response;
    }
}
