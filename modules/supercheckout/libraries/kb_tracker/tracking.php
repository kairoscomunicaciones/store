<?php
function captureAndSendAdminDetails()
{
    try {
        $plugin_monitor_url = 'http://tracker.knowband.com/track_plugin.php';
        $full_site_url = '';
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $full_site_url = _PS_BASE_URL_SSL_ . __PS_BASE_URI__;
        } else {
            $full_site_url = _PS_BASE_URL_ . __PS_BASE_URI__;
        }

        $domain_name = Context::getContext()->shop->domain;
        $plugin_name = Tools::getValue('module_name');
        if (Tools::getValue('controller') == 'AdminModules') {
            $plugin_name = Tools::getValue('configure');
        } else {
            if (isset(Context::getContext()->controller->module->name)) {
                $plugin_name = Context::getContext()->controller->module->name;
            }
        }
        
        $admin_email = Configuration::get('PS_SHOP_EMAIL');
        $shop_name = Configuration::get('PS_SHOP_NAME');
        $data_time = date('Y-m-d H:i:s');
        $plugin_version = '';
        $plugin_installed = 0;
        if (!empty($plugin_name)) {
            $module = Module::getInstanceByName($plugin_name);
            if (Module::isInstalled($plugin_name)) {
                $plugin_installed = 1;
            }
            $plugin_version = $module->version;
        }
        $token = md5($shop_name.$admin_email);
        $data = array(
            'full_site_url' => $full_site_url,
            'domain_name' => $domain_name,
            'admin_email' => $admin_email,
            'shop_name' => $shop_name,
            'data_time' => $data_time,
            'plugin_name' => $plugin_name,
            'plugin_version' => $plugin_version,
            'plugin_installed' => $plugin_installed,
            'platform' => 'PrestaShop',
            'token' => $token
        );
        $ch = curl_init($plugin_monitor_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) . '&action=capturefreeplugindata');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        curl_close($ch);
    } catch (Exception $e) {
        //Do Nothing
    }
}