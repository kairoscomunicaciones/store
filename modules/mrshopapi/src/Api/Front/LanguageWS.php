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

namespace MrAPPs\MrShopApi\Api\Front;

use Configuration;
use Language;
use MrAPPs\MrShopApi\Api\BaseWS;
use phpDocumentor\Reflection\Types\This;

class LanguageWS extends BaseWS
{
    public static function getDefaultLanguage()
    {
        return (int) Configuration::get('PS_LANG_DEFAULT');
    }

    public function getList($params)
    {
        $result = array_map(function ($n) {
            return [
                'id_lang' => (int) $n['id_lang'],
                'iso_code' => $n['iso_code'],
                'name' => $n['name'],
                'locale' => isset($n['locale']) ? $n['locale'] : '',
                'is_default' => LanguageWS::getDefaultLanguage() == $n['id_lang']
            ];
        }, Language::getLanguages(true, $this->context->shop->id));

        $this->response(true, null, $result);
    }
}
