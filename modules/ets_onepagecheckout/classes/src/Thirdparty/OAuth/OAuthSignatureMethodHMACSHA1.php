<?php
/**
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

namespace Hybridauth\Thirdparty\OAuth;

class OAuthSignatureMethodHMACSHA1 extends OAuthSignatureMethod
{
    public function get_name()
    {
        return "HMAC-SHA1";
    }

    public function build_signature($request, $consumer, $token)
    {
        $base_string = $request->get_signature_base_string();
        $request->base_string = $base_string;

        $key_parts = array( $consumer->secret, $token ? $token->secret : '' );

        $key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
        $key = implode('&', $key_parts);
        
        return call_user_func('base64_encode', call_user_func_array('hash_hmac', array('sha1', $base_string, $key, true)));
    }
}
