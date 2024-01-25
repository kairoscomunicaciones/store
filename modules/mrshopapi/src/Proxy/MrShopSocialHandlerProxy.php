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

namespace MrAPPs\MrShopApi\Proxy;

use MrAPPs\MrShopApi\Proxy\ProxyResource\ProxyPresentResource;

/**
 *
 * @method static array getConfig($appConfig = false, $callback = 'https://validcallbackurl.com')
 * @method static mixed getHybridAuthInstance($callback, $fromApp = false)
 * @method static mixed getUser($hybridAuth, $provider, $tokens, $fromApp = false, $callback = 'https://validcallbackurl.com')
 * @method static mixed getUserForApp($hybridAuth, $provider, $tokens)
 * @method static mixed getUserData($adapter, $provider, $response = null)
 * @method static mixed auth($hybridAuth, $provider)
 * @method static mixed handleAuth($hybridAuth, $provider, $redirect_after)
 * @method static mixed login($id_customer)
 */
class MrShopSocialHandlerProxy extends AbstractProxy
{
    protected static function getResource()
    {
        return [
            ProxyPresentResource::create(_PS_MODULE_DIR_.'mrshopsociallogin/src/MrShopSocialHandler.php', 'MrAPPs\MrShopSocialLogin\MrShopSocialHandler'),
            ProxyPresentResource::create(_PS_MODULE_DIR_.'mrshopsociallogin/classes/MrShopSocialHandler.php', 'MrShopSocialHandler')
        ];
    }
}
