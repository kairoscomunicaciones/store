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
use Customer;
use CustomerThread;
use Db;
use Language;
use MrAPPs\MrShopApi\Service\FcmClient;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrShopApiNotification;
use OrderState;
use Tools;
use Validate;

class NotificationsHandler
{
    private $module;

    private $fcmClient;

    private $idLangDefault;

    public function __construct($module = null)
    {
        $this->module = $module;

        $this->fcmClient = new FcmClient($this->module);
        $this->idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');
    }

    public function sendNotification($notification, $language)
    {
        if (ApiUtils::isFcmConfigured()) {
            $output =  $this->fcmClient
                ->resetParameters()
                ->setLanguage($language)
                ->setNotificationParametersFromModel($notification)
                ->sendNotification();

            return true;
        }

        return false;
    }

    public function sendNotificationGroup($notification, $language, $id_customer)
    {
        $extraTopic = ['customer_'.$id_customer];

        if (ApiUtils::isFcmConfigured()) {
            $output = $this->fcmClient
                ->resetParameters()
                ->setLanguage($language)
                ->setNotificationParametersFromModel($notification)
                ->sendNotification($extraTopic);

            return true;
        }

        return false;
    }

    public function sendNotifications()
    {
        $notificationsToSend = MrShopApiNotification::getNotificationsToSend();

        foreach ($notificationsToSend as $item) {
            $notification = new MrShopApiNotification((int) $item['id_notification']);
            $id_notification = $item['id_notification'];
            $id_group = $item['id_group'];
            $this->updateTableNotificationPool($id_notification, $id_group);
            $notification->sent = true;
            $notification->active = true;
            $notification->save();
        }

        $data = MrShopApiNotification::getNotificationsPool();

        if (count($data) > 0) {
            foreach ($data as $notif) {
                $id = (int) $notif['id'];
                $idNotification = (int) $notif['id_notification'];
                $idLang = (int) $notif['id_lang'];
                $language = new Language($idLang);
                $idCustomer = (int) $notif['id_customer'];
                $notification = new MrShopApiNotification($idNotification);
                $notification -> active = true;

                if ($idCustomer > 0) {
                    $sent = $this->sendNotificationGroup($notification, $language, $idCustomer);
                    if ($sent) {
                        $sql = 'DELETE FROM `'._DB_PREFIX_.'notification_pool` WHERE `id` ='.$id;
                        Db::getInstance()->execute($sql);
                        $notification->sent = true;
                    }
                } else {
                    $sent = $this->sendNotification($notification, $language);
                    if ($sent) {
                        $sql = 'DELETE FROM `'._DB_PREFIX_.'notification_pool` WHERE `id` ='.$id;
                        Db::getInstance()->execute($sql);
                        $notification->sent = true;
                    }
                }
            }
        }

        return true;
    }

    public function sendOrderStatusChangeNotification($id_order = 0)
    {
        if (!ApiUtils::isFcmConfigured()) {
            return;
        }

        $sql = '
         SELECT o.`reference`, o.`current_state`, o.`id_customer`, c.`id_lang`
         FROM `'._DB_PREFIX_.'orders` o
         LEFT JOIN `'._DB_PREFIX_.'customer` c ON o.`id_customer` = c.`id_customer`
         WHERE o.`id_order` = '.(int) $id_order;
        $order = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

        if (!$order || !is_array($order)) {
            return;
        }

        $reference = trim($order['reference']);
        $id_customer = (int) $order['id_customer'];
        $id_current_state = (int) $order['current_state'];
        $id_lang_customer = (int) $order['id_lang'];

        $sql = '
         SELECT COUNT(*) AS n
         FROM `'._DB_PREFIX_.'order_status_notifications`
         WHERE `id_order_state` = '.(int) $id_current_state.'
         AND `active` = 1';
        $orderStateCheck = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

        $canProceed = $orderStateCheck && is_array($orderStateCheck) && isset($orderStateCheck['n']) ? (int) $orderStateCheck['n'] > 0 : false;
        if (!$canProceed) {
            return;
        }

        $orderState = new OrderState($id_current_state);
        if (!$orderState || !Validate::isLoadedObject($orderState)) {
            return;
        }

        if (is_array($orderState->name) && count($orderState->name) > 0) {
            if (isset($orderState->name[$id_lang_customer])) {
                // Testo nella lingua del cliente
                $orderStateName = trim($orderState->name[$id_lang_customer]);
            } elseif (isset($orderState->name[$this->idLangDefault])) {
                // Testo nella lingua di default di Prestashop
                $orderStateName = trim($orderState->name[$this->idLangDefault]);
            } else {
                // Testo nella prima lingua che trova
                $orderStateName = trim($orderState->name[array_keys($orderState->name)[0]]);
            }
        } else {
            $orderStateName = trim($orderState->name);
        }

        if (Tools::strlen($orderStateName) == 0) {
            return;
        }

        $notificationText = sprintf('%s %s: %s', $this->module->l('Order', 'notificationshandler'), $reference, $orderStateName);

        $language = new Language($id_lang_customer);
        if (!$language || !Validate::isLoadedObject($language)) {
            $language = new Language($this->idLangDefault);
        }

        $fcmClient = new FcmClient($this->module);

        $extraTopic = ['customer_'.$id_customer];
        $fcmClient
            ->resetParameters()
            ->setLanguage($language)
            ->setType(MrShopApiNotification::$TYPE_ORDER_STATE_CHANGED)
            ->setOrder($id_order)
            ->setBody($notificationText)
            ->sendNotification($extraTopic);
    }

    /**
     * @param \CustomerMessage $customerMessage
     *
     */
    public function sendMessageToCustomer($customerMessage)
    {
        if ($customerMessage->id_employee > 0) {
            $customerThread = new CustomerThread((int) $customerMessage->id_customer_thread);

            if (Validate::isLoadedObject($customerThread)) {
                $customer = new Customer((int) $customerThread->id_customer);

                if (Validate::isLoadedObject($customer)) {
                    $language = new Language($customer->id_lang);
                    if (!$language || !Validate::isLoadedObject($language)) {
                        $language = new Language($this->idLangDefault);
                    }

                    $notificationText = $customerMessage->message;

                    $fcmClient = new FcmClient($this->module);

                    $extraTopic = ['customer_'.$customer->id];
                    $fcmClient
                        ->resetParameters()
                        ->setLanguage($language)
                        ->setType(MrShopApiNotification::$TYPE_ORDER_ASSISTANCE)
                        ->setOrder((int) $customerThread->id_order)
                        ->setBody($notificationText)
                        ->sendNotification($extraTopic);
                }
            }
        }
    }

    public function updateTableNotificationPool($id_notification, $id_group = null)
    {
        if (empty($id_group)) {
            $notification = new MrShopApiNotification($id_notification);

            foreach ($notification->title as $idLang => $title) {
                $idCustomer = 0;
                $sql = 'INSERT INTO '._DB_PREFIX_.'notification_pool (`id_notification`, `id_lang`, `id_customer`) values('.(int) $id_notification.','.(int) $idLang.','.(int) $idCustomer.')';
                Db::getInstance()->execute($sql);
            }
        } else {
            $sql = 'SELECT cg.`id_customer`, c.`id_lang` FROM `'._DB_PREFIX_.'customer_group` cg INNER JOIN `'._DB_PREFIX_.'customer` c ON cg.`id_customer`= c.`id_customer` WHERE cg. `id_group`='.(int) $id_group;

            $utentiFiltrati = Db::getInstance()->executeS($sql);

            foreach ($utentiFiltrati as $user) {
                $idCustomer = $user['id_customer'];
                $idLang= $user['id_lang'];
                $sql = 'INSERT INTO '._DB_PREFIX_.'notification_pool (`id_notification`, `id_lang`, `id_customer`) values  ('.(int) $id_notification.','.(int) $idLang.','.(int) $idCustomer.')';
                Db::getInstance()->execute($sql);
            }
        }
    }
}
