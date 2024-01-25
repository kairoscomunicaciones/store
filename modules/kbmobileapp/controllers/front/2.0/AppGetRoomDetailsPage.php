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
class AppGetRoomDetailsPage extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if (!(int) Tools::getValue('product_id', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product id is missing'),
                    'AppGetRoomDetailsPage'
                )
            );
        } else if (!(int) Tools::getValue('id_booking_product', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Booking Product id is missing'),
                    'AppGetRoomDetailsPage'
                )
            );
        } else if (!(int) Tools::getValue('id_room_type', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Room Type id is missing'),
                    'AppGetRoomDetailsPage'
                )
            );
        } else {
            $id_product = (int) Tools::getValue('product_id', 0);
            $id_booking_product = (int) Tools::getValue('id_booking_product', 0);
            $id_room_type = (int) Tools::getValue('id_room_type', 0);
            $sql = 'SELECT a.* FROM '._DB_PREFIX_.'kb_booking_product'.' as a INNER JOIN '
                ._DB_PREFIX_.'product p on (a.id_product=p.id_product) INNER JOIN '
                ._DB_PREFIX_.'product_shop ps on (a.id_product=ps.id_product AND '
                . 'ps.id_shop='.(int)$this->context->shop->id.') WHERE a.active=1 AND '
                . 'a.id_product='.(int)$id_product;
            $product_Booking_Data = Db::getInstance()->getRow($sql);
            $this->content['service_type'] = $product_Booking_Data['service_type'];
            $date_data = json_decode($product_Booking_Data['date_details'], true);
            $today_date = date("Y-m-d");
            if ((isset($date_data)) && !empty($date_data)) {
                $from_Dates_Array = array();
                $to_Dates_Array = array();
                foreach ($date_data as $data_time_slot => $date_time_data) {
                    $from_Dates_Array[] = $date_time_data['from_date'];
                    $to_Dates_Array[] = $date_time_data['to_date'];
                }
                
                if (min($from_Dates_Array) < $today_date) {
                    $from_date = $today_date;
                } else {
                    $from_date = min($from_Dates_Array);
                }
                $this->content['enabled_dates'] = array(
                    'from_date' => $from_date,
                    'to_date' => max($to_Dates_Array)
                );
            } else {
                $this->content['enabled_dates'] = array(
                    'from_date' => $today_date,
                    'to_date' => ''
                );
            }
            $this->content['room_details'] = $this->getSelectedRoomDetails((int) $id_product, (int) $id_booking_product, (int) $id_room_type);
            $this->content['status'] = 'success';
        }
        
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }


    /**
     * Get list of states for selected country
     *
     * @param int $id_country counmtry id
     * @return array state data
     */
    public function getSelectedRoomDetails($id_product, $id_booking_product, $id_room_type)
    {
        require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingFacilities.php');
        $room_details = array();
        $kbbooking = Module::getInstanceByName('Kbbookingcalendar');
        $hotel_rooms = KbBookingProduct::getHotelProductRoomsByID($id_booking_product, $id_room_type);
        if (empty($hotel_rooms)) {
            return $room_details;
        }
        foreach ($hotel_rooms as &$rooms) {
            if ($rooms['id_booking_room_facilities_map'] == $id_room_type) {
                $rooms['room_facilities'] = (!empty($rooms['id_facilities'])) ? KbBookingFacilities::getFacilitiesMappedwithHotelRooms($rooms['id_booking_room_facilities_map'], $rooms['id_facilities']) : '';
                $rooms['room_type'] = KbBookingRoomType::getAvailableRoomTypeByID($rooms['id_room_type']);
                $rooms['room_category_name'] = KbBookingRoomCategory::getRoomCategoryNameByID($rooms['id_room_category']);
                $rooms['price'] = Tools::displayPrice(Tools::convertPriceFull($rooms['price'], Currency::getDefaultCurrency()));
            } else {
                unset($rooms);
            }
        }
        $room_details['id_room_type'] = (int)$id_room_type;
        $room_details['type'] = $rooms['room_type']['room_name'];
        $room_details['is_quantity_enabled'] = 1;
        $room_details['is_add_cart_enabled'] = 1;
        $room_details['guest']['adult'] = (int)$rooms['room_type']['max_allowed_adult'];
        $room_details['guest']['child'] = (int)$rooms['room_type']['max_allowed_child'];
        $room_details['category'] = $rooms['room_category_name'];
        $room_details['price']  = $this->formatPrice($rooms['price']);
        $room_images = json_decode($rooms['upload_images']);
        $images = array();
        if (!empty($room_images)) {
            foreach ($room_images as $room_image_id => $room_image_link) {
                $images[] = $room_image_link->link;
            }
            $room_details['images'] = $images;
        } else {
            $room_details['images'] = array();
        }
        $room_details['other'] = array(
            'room_type' => $rooms['room_type']['room_name'],
            'room_category' => $rooms['room_category_name'],
            'check_in' => $rooms['start_time'],
            'check_out' => $rooms['end_time'],
        );
        //die(print_r($hotel_rooms));
        $this->context->smarty->assign(array(
                        'hotel_rooms' => $hotel_rooms,
                    ));
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }

        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $module_dir = '"'._PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = '"'._PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        $this->context->smarty->assign('kbmobileapp_dir', $module_dir.'kbmobileapp/');
        $module_dir = $module_dir.'kbbookingcalendar/';
        $this->context->smarty->assign('mod_dir', $module_dir);
        $book_faci_tpl = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'kbmobileapp' . '/views/templates/hook/hotel_rooms.tpl');
        $book_faci_tpl = preg_replace("/[\n\r]/", "", $book_faci_tpl);
        $room_details['facility_html'] = $book_faci_tpl;
        if (empty($room_details['facility_html'])) {
            $room_details['is_facility_html'] = 0;
        } else {
            $room_details['is_facility_html'] = 1;
        }
        return $room_details;
    }
}
