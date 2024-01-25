<?php

/**

 * This is main class of module.

 *

 * @author    Globo Software Solution JSC <contact@globosoftware.net>

 * @copyright 2017 Globo ., Jsc

 * @license   please read license in file license.txt

 * @link	     http://www.globosoftware.net

 */



if (!defined('_PS_VERSION_'))

    exit;
function upgrade_module_1_3_0($module)
{
    $getcont = Module::getInstanceByName('gwadvancedinvoice');
    $getcont->unregisterHook('displayBackOfficeHeader');
    $getcont->registerHook('actionAdminControllerSetMedia');
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gwaicustomnumber` ADD COLUMN `current_counter_nbr` int(10) NULL DEFAULT  "0"');
    $sql = 'SELECT * FROM `'._DB_PREFIX_.'gwaicustomnumber`';
    $all_customnumber = Db::getInstance()->ExecuteS($sql);
    if($all_customnumber) {
        $moduleObj = Module::getInstanceByName('gwadvancedinvoice');
        foreach ($all_customnumber as $numberconfig) {
            $old_reset_date = '1970-01-01 00:00:00';
            switch ((int)$numberconfig['resettype']) {
                case '0':
                    $old_reset_date = '1970-01-01 00:00:00'; // Unix timestamp
                    break;
                case '2':
                    // reset every day
                    $day_now = date('Y-m-d');
                    $old_reset_date = $day_now.' 00:00:00';
                    break;
                case '3':
                    // reset every month
                    $month_now = date('Y-m');
                    $old_reset_date = $month_now.'-01'.' 00:00:00';
                    break;
                case '4':
                    // reset every year
                    $year_now = date('Y');
                    $old_reset_date = $year_now.'-01'.'-01'.' 00:00:00';
                    break;
                case '5':
                    // reset by date
                    $time_reset_config = $numberconfig['resetdate'];
                    if($time_reset_config !=null && $time_reset_config !=''){
                        $time_reset = date('Y-m-d H:i:s',strtotime($time_reset_config));
                        $old_reset_date = $time_reset;
                    }else{
                        $old_reset_date = '1970-01-01 00:00:00'; // Unix timestamp
                    }
                    break;
            }
            $total_nbr = $moduleObj->getTotal($numberconfig['type'],$numberconfig['groups'],$old_reset_date,(int)$numberconfig['id_shop']);
            $total = $total_nbr*$numberconfig['step'];
            if((int)$numberconfig['resettype'] == 1 && $total > $numberconfig['resetnumber'] && $numberconfig['resetnumber'] > 0){
                $total = $numberconfig['start'];
            }
            $sql = 'UPDATE `'._DB_PREFIX_.'gwaicustomnumber` SET current_counter_nbr='.(int)$total.' WHERE id_gwaicustomnumber = '.(int)$numberconfig['id_gwaicustomnumber'];
            Db::getInstance()->Execute($sql);
        }
    }
    return true;
}