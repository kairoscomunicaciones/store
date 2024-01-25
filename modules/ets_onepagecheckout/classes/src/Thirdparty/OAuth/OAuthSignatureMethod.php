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

abstract class OAuthSignatureMethod
{
    /**
    * Needs to return the name of the Signature Method (ie HMAC-SHA1)
    *
    * @return string
    */
    abstract public function get_name();

    /**
    * Build up the signature
    * NOTE: The output of this function MUST NOT be urlencoded.
    * the encoding is handled in OAuthRequest when the final
    * request is serialized
    *
    * @param OAuthRequest $request
    * @param OAuthConsumer $consumer
    * @param OAuthToken $token
    * @return string
    */
    abstract public function build_signature($request, $consumer, $token);

    /**
    * Verifies that a given signature is correct
    *
    * @param OAuthRequest $request
    * @param OAuthConsumer $consumer
    * @param OAuthToken $token
    * @param string $signature
    * @return bool
    */
    public function check_signature($request, $consumer, $token, $signature)
    {
        $built = $this->build_signature($request, $consumer, $token);

        // Check for zero length, although unlikely here
        if (\Tools::strlen($built) == 0 || \Tools::strlen($signature) == 0) {
            return false;
        }

        if (\Tools::strlen($built) != \Tools::strlen($signature)) {
            return false;
        }

        // Avoid a timing leak with a (hopefully) time insensitive compare
        $result = 0;
        for ($i = 0; $i < \Tools::strlen($signature); $i ++) {
            $result |= ord($built {$i}) ^ ord($signature {$i});
        }

        return $result == 0;
    }
}
