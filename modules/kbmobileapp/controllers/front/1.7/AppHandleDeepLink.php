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
 * API to get data of home page
 * called from home page in APP
 */

require_once 'AppCore.php';

class AppHandleDeepLink extends AppCore
{
    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        if (!Tools::getIsset('full_url_of_page') || empty(Tools::getValue('full_url_of_page'))) {
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('URL is missing.'),
                    'AppHandleDeepLink'
                )
            );
            $this->writeLog('URL is missing.');
        } else {
            $URL = Tools::getValue('full_url_of_page');
            $this->content = $this->getUrlData($URL);
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    
    public function addRoute($route_id, $rule, $controller, $id_lang = null, array $keywords = array(), array $params = array(), $id_shop = null)
    {
        if (isset(Context::getContext()->language) && $id_lang === null) {
            $id_lang = (int)Context::getContext()->language->id;
        }

        if (isset(Context::getContext()->shop) && $id_shop === null) {
            $id_shop = (int)Context::getContext()->shop->id;
        }

        $regexp = preg_quote($rule, '#');
        if ($keywords) {
            $transform_keywords = array();
            preg_match_all('#\\\{(([^{}]*)\\\:)?('.implode('|', array_keys($keywords)).')(\\\:([^{}]*))?\\\}#', $regexp, $m);
            for ($i = 0, $total = count($m[0]); $i < $total; $i++) {
                $prepend = $m[2][$i];
                $keyword = $m[3][$i];
                $append = $m[5][$i];
                $transform_keywords[$keyword] = array(
                    'required' => isset($keywords[$keyword]['param']),
                    'prepend' => Tools::stripslashes($prepend),
                    'append' => Tools::stripslashes($append),
                );

                $prepend_regexp = $append_regexp = '';
                if ($prepend || $append) {
                    $prepend_regexp = '('.$prepend;
                    $append_regexp = $append.')?';
                }

                if (isset($keywords[$keyword]['param'])) {
                    $regexp = str_replace($m[0][$i], $prepend_regexp.'(?P<'.$keywords[$keyword]['param'].'>'.$keywords[$keyword]['regexp'].')'.$append_regexp, $regexp);
                } else {
                    $regexp = str_replace($m[0][$i], $prepend_regexp.'('.$keywords[$keyword]['regexp'].')'.$append_regexp, $regexp);
                }
            }
            $keywords = $transform_keywords;
        }

        $regexp = '#^/'.$regexp.'$#u';
        if (!isset($this->routes[$id_shop])) {
            $this->routes[$id_shop] = array();
        }
        if (!isset($this->routes[$id_shop][$id_lang])) {
            $this->routes[$id_shop][$id_lang] = array();
        }

        $this->routes[$id_shop][$id_lang][$route_id] = array(
            'rule' =>        $rule,
            'regexp' =>        $regexp,
            'controller' =>    $controller,
            'keywords' =>    $keywords,
            'params' =>        $params,
        );
    }
    public function isProductLink($short_link, $route, &$kb_unique_id)
    {
        $short_link = preg_replace('#[\d]*[-]*[\d]*\.html?$#', '', '/' . $short_link);
        $regexp = preg_replace('!\\\.html(\\\?.*)\\$#!', '$#', $route['regexp']);
        preg_match($regexp, $short_link, $kw);
        if (empty($kw)) {
            return false;
        }
        $sql = 'SELECT `id_product`
            FROM `' . _DB_PREFIX_ . 'product_lang`
            WHERE `link_rewrite` = \'' . pSQL(trim(end($kw), '-')) . '\' AND `id_lang` = ' . (int) Context::getContext()->language->id;
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND `id_shop` = ' . (int) Shop::getContextShopID();
        }
        $id_product = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        $kb_unique_id = $id_product;
        return $id_product;
    }
    
    public function isCmsLink($short_link, $route, &$kb_unique_id)
    {
        $short_link_cms = Tools::substr(strchr($short_link, "/"), 1);
        $short_link = preg_replace('#\.html?$#', '', '/' . $short_link_cms);
        $regexp = preg_replace('!\\\.html?\\$#!', '$#', $route['regexp']);
        
        preg_match($regexp, $short_link, $kw);
        if (empty($kw)) {
            return false;
        }
        $sql = 'SELECT l.`id_cms`
            FROM `' . _DB_PREFIX_ . 'cms_lang` l
            LEFT JOIN `' . _DB_PREFIX_ . 'cms_shop` s ON (l.`id_cms` = s.`id_cms`)
            WHERE l.`link_rewrite` = \'' . pSQL(end($kw)) . '\'';
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND s.`id_shop` = ' . (int) Shop::getContextShopID();
        }
        $id_cms = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        $kb_unique_id = $id_cms;
        return $id_cms;
    }
    public function isCategoryLink($short_link, $route, &$kb_unique_id)
    {
        $short_link = preg_replace('#\.html?$#', '', '/' . $short_link);
        $regexp = preg_replace('!\\\.html?\\$#!', '$#', $route['regexp']);
        preg_match($regexp, $short_link, $kw);
        if (empty($kw)) {
            return false;
        }
        $sql = 'SELECT `id_category`
            FROM `' . _DB_PREFIX_ . 'category_lang`
            WHERE `link_rewrite` = \'' . pSQL(end($kw)) . '\' AND `id_lang` = ' . (int) Context::getContext()->language->id;
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND `id_shop` = ' . (int) Shop::getContextShopID();
        }
        $id_category = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        $kb_unique_id = $id_category;
        return $id_category;
    }
    public function getUrlData($url)
    {
        $kb_unique_id = '';
        $dispatcher = Dispatcher::getInstance();
        $language_ids = Language::getIDs();
        $dis = json_decode(json_encode($dispatcher), true);
        $context = Context::getContext();
        $this->default_routes = $dis['default_routes'];
        if (isset($context->language) && !in_array($context->language->id, $language_ids)) {
            $language_ids[] = (int)$context->language->id;
        }
        
        // Set default routes
        foreach ($language_ids as $id_lang) {
            foreach ($this->default_routes as $id => $route) {
                $this->addRoute(
                    $id,
                    $route['rule'],
                    $route['controller'],
                    $id_lang,
                    $route['keywords'],
                    isset($route['params']) ? $route['params'] : array(),
                    (int)$context->shop->id
                );
            }
        }
        $b = $this->routes;
        $store_url = $this->context->shop->getBaseURL();
        if ($this->checkSecureUrl()) {
            $store_url = str_replace('http:', 'https:', $store_url);
        }
        $pos = strpos($url, '?');
        if ($pos) {
            $url = Tools::substr($url, 0, $pos);
        }
        if (!preg_match('/\.(gif|jpe?g|png|css|js|ico)$/i', parse_url($url, PHP_URL_PATH))) {
            list($uri) = explode('?', $url);
            $short_link = trim(parse_url($uri, PHP_URL_PATH), '/');
        } else {
            $short_link = '';
        }
        $kb_unique_id = '';
        
        
        $route = $b[Context::getContext()->shop->id][Context::getContext()->language->id]['product_rule'];
        $short_link_product = Tools::substr(strrchr($short_link, "/"), 1);
        $product_id = $this->isProductLink($short_link_product, $route, $kb_unique_id);
        if (!empty($product_id)) {
            return array(
                "status" => "success",
                "click_target" => "product",
                "target_id" => (string)$product_id,
                "title" => ""
            );
        } else {
             //category check
            $route = $b[Context::getContext()->shop->id][Context::getContext()->language->id]['category_rule'];
            $short_link_cat = Tools::substr(strrchr($short_link, "/"), 1);
            $cat_id = $this->isCategoryLink($short_link_cat, $route, $kb_unique_id);
            if (!empty($cat_id)) {
                return array(
                    "status" => "success",
                    "click_target" => "category",
                    "target_id" => (string)$cat_id,
                    "title" => ""
                );
            } else {
                //cms check
                $route = $b[Context::getContext()->shop->id][Context::getContext()->language->id]['cms_rule'];
                $short_link_cms = str_replace($store_url, '', $url);
                $cms_id = $this->isCmsLink($short_link_cms, $route, $kb_unique_id);
                
                if (!empty($cms_id)) {
                    $link = $context->link->getCMSLink($cms_id);
                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                    return array(
                        "status" => "success",
                        "click_target" => "CMS",
                        "target_id" => $link,
                        "title" => "",
                    );
                } else {
                    if ($this->checkSecureUrl()) {
                        $http = _PS_BASE_URL_SSL_.'/';
                    } else {
                        $http = _PS_BASE_URL_.'/';
                    }
                    $iso_code_in_url = ltrim(strrchr($short_link, "/"), '/');
                    $iso_array = array();
                    foreach (Language::getLanguages(false) as $lang_data) {
                        $iso_array[] = $lang_data['iso_code'];
                    }
                    if (!empty($iso_code_in_url) && !in_array($iso_code_in_url, $iso_array)) {
                        return array(
                            "status" => "failure",
                            "click_target" => "",
                            "target_id" => '',
                            "title" => "",
                        );
                    } else if ($http.str_replace(strrchr($short_link, "/"), '', $short_link.'/') == $store_url) {
                        return array(
                            "status" => "success",
                            "click_target" => "home",
                            "target_id" => '',
                            "title" => "",
                        );
                    } else {
                        return array(
                            "status" => "failure",
                            "click_target" => " ",
                            "target_id" => '',
                            "title" => "",
                        );
                    }
                }
            }
        }
    }
}
