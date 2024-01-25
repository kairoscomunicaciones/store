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
class AppGetRoomTypes extends AppCore
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
                    'AppGetRoomTypes'
                )
            );
        } else if (!(int) Tools::getValue('id_category', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Category id is missing'),
                    'AppGetRoomTypes'
                )
            );
        } else if (!(int) Tools::getValue('id_booking_product', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Booking Product id is missing'),
                    'AppGetRoomTypes'
                )
            );
        } else {
            $id_product = (int) Tools::getValue('product_id', 0);
            $id_booking_product = (int) Tools::getValue('id_booking_product', 0);
            $id_category = (int) Tools::getValue('id_category', 0);
            $this->content['room_types'] = $this->getRoomTypes((int) $id_booking_product, (int) $id_category);
        }
        if (empty($this->content['room_types'])) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('No Rooms available'),
                'AppGetRoomTypes'
            );
        } else {
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
    public function getRoomTypes($id_booking_product, $id_category)
    {
        $room_types = array();
        $kbbooking = Module::getInstanceByName('Kbbookingcalendar');
        $hotel_rooms = KbBookingProduct::getHotelProductRoomsByID($id_booking_product);
        //die(print_r($hotel_rooms));
        $index = 0;
        foreach ($hotel_rooms as $hotel_rooms_key => $hotel_rooms_data) {
            $room_types_data = KbBookingRoomType::getAvailableRoomTypeByID($hotel_rooms_data['id_room_type']);
            if ($hotel_rooms_data['id_room_category'] == $id_category) {
                $room_types[$index]['id_room_type'] = (int)$hotel_rooms_data['id_booking_room_facilities_map'];
                $room_types[$index]['category'] = KbBookingRoomCategory::getRoomCategoryNameByID($hotel_rooms_data['id_room_category']);
                $room_types[$index]['type'] = $room_types_data['room_name'];
                $room_types[$index]['price'] = $this->formatPrice($hotel_rooms_data['price']);
                $room_images = json_decode($hotel_rooms_data['upload_images']);
                if (!empty($room_images)) {
                    $room_types[$index]['image_url'] = $room_images[0]->link;
                } else {
                    $room_types[$index]['image_url']  = '';
                }
                $index++;
            }
        }
        return $room_types;
    }
}
