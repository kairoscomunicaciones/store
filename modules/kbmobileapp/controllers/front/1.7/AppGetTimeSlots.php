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
class AppGetTimeSlots extends AppCore
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
                    'AppGetTimeSlots'
                )
            );
        } else if (!(int) Tools::getValue('id_booking_product', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Booking Product id is missing'),
                    'AppGetTimeSlots'
                )
            );
        } else if (! Tools::getValue('date') || ! Tools::getValue('month') || ! Tools::getValue('year')) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Selected Date/Month/Year is missing'),
                    'AppGetTimeSlots'
                )
            );
        } else {
            $id_product = (int) Tools::getValue('product_id', 0);
            $id_booking_product = (int) Tools::getValue('id_booking_product', 0);
            $id_category = (int) Tools::getValue('id_category', 0);
            
            $this->content['time_slots_data'] = $this->getTimeSlotsData((int) $id_product, (int) $id_category);
            if ($this->content['time_slots_data']['is_time_slots_available'] == 0) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('No time slots available'),
                    'AppGetTimeSlots'
                );
            } else {
                $this->content['status'] = 'success';
            }
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
    public function getTimeSlotsData($id_product, $id_category)
    {
        $data = array();
        $sql = 'SELECT a.* FROM '._DB_PREFIX_.'kb_booking_product'.' as a INNER JOIN '
                ._DB_PREFIX_.'product p on (a.id_product=p.id_product) INNER JOIN '
                ._DB_PREFIX_.'product_shop ps on (a.id_product=ps.id_product AND '
                . 'ps.id_shop='.(int)$this->context->shop->id.') WHERE a.active=1 AND '
                . 'a.id_product='.(int)$id_product;
        $product_Booking_Data = Db::getInstance()->getRow($sql);
        $date_data = json_decode($product_Booking_Data['date_details'], true);
        $selected_date = Tools::getValue('date');
        $selected_month = Tools::getValue('month');
        $selected_year = Tools::getValue('year');
        //$date = $selected_year.'-'.$selected_month.'-'.$selected_date;
        $date_obj = new DateTime();
        $date_obj->setDate((int)$selected_year, (int)$selected_month, (int)$selected_date);
        $date = $date_obj->format('Y-m-d');
        $time_Slots_Array = array();
        foreach ($date_data as $data_time_slot => $date_time_data) {
            if (($date >= $date_time_data['from_date']) && ($date <= $date_time_data['to_date'])) {
                $time_Slots_Array = $date_time_data['time'];
            }
        }
        $time_slots = array();
        if (!empty($time_Slots_Array)) {
            $index = 0;
            $is_time_slots_available = 1;
            foreach ($time_Slots_Array as $time_Slots_Array_Key => $time_Slots_Array_Data) {
                $time_slots[$index] = $time_Slots_Array_Data['from_time'] .' - '. $time_Slots_Array_Data['to_time'] .' ( ' .$this->formatPrice($time_Slots_Array_Data['price']).' )' ;
                $index++;
            }
        } else {
            $is_time_slots_available = 0;
        }
        $data['is_time_slots_available'] = $is_time_slots_available;
        $data['time_slots'] = $time_slots;
        return $data;
    }
}
