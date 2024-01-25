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
 */

class Firebase 
{

    // sending push message to single user by firebase reg id
    public function send($to, $message, $server_key, $device = 'both') 
    {
        $notification = $message['data'];
        $notification['body'] = $message['data']['message'];
        if ($device == 'android') {
            $fields = array(
                'to' => $to,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
            return $this->sendPushNotification($fields, $server_key);
        } else if ($device == 'ios') {
            $fields = array(
                'to' => $to,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification,
            );
            return $this->sendPushNotification($fields, $server_key);
        } else {
            $fields = array(
                'to' => $to,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
            return $this->sendPushNotification($fields, $server_key);
        }
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $message, $server_key, $device = 'both') 
    {
        $notification = $message['data'];
        $notification['body'] = $message['data']['message'];
        if ($device == 'android') {
            $fields = array(
                'to' => '/topics/' . $to,
                'data' => $message['data'],
                'notification' => $notification
            );
        } elseif ($device == 'ios') {
            $fields = array(
                'to' => '/topics/' . $to,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
        } else {
            $fields = array(
                'to' => '/topics/' . $to,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
        }

        return $this->sendPushNotification($fields, $server_key);
    }

    public function sendMultiple($registration_ids, $message, $server_key, $device = 'both') 
    {
        $notification = $message['data'];
        $notification['body'] = $message['data']['message'];
        if ($device == 'android') {
            $fields = array(
                'to' => $registration_ids,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
        } else if ($device == 'ios') {
            $fields = array(
                'to' => $registration_ids,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
        } else {
            $fields = array(
                'to' => $registration_ids,
                'data' => $message['data'],
                'priority' => "high",
                'mutable_content' => true,
                'content_available' => true,
                'notification' => $notification
            );
        }

        return $this->sendPushNotification($fields, $server_key);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields, $server_key = '') 
    {

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . $server_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));


        // Execute post
        $result = curl_exec($ch);


        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }

}
