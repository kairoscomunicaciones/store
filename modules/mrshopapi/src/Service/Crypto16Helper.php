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

namespace MrAPPs\MrShopApi\Service;

use Tools;

class Crypto16Helper
{
    /**
     * Iterate on hash_methods array and return true if it matches
     *
     * @param string $passwd The password you want to check
     * @param string $hash The hash you want to check
     *
     * @return bool `true` is returned if the function find a match else false
     */
    public function checkHash($passwd, $hash)
    {
        $hashedPasswd = Crypto16Helper::hash($passwd);

        return $hashedPasswd == $hash;
    }

    /**
     * Hash the `$plaintextPassword` string and return the result of the 1st hashing method
     * contained in PrestaShop\PrestaShop\Core\Crypto\Hashing::hash_methods
     *
     * @param string $plaintextPassword The password you want to hash
     *
     * @return string
     */
    public function hash($plaintextPassword)
    {
        return Tools::encrypt($plaintextPassword);
    }
}
