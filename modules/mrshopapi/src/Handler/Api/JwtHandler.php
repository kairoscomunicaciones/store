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

namespace MrAPPs\MrShopApi\Handler\Api;

use Configuration;
use Customer;
use Db;
use Firebase\JWT\JWT;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Tools;

class JwtHandler
{
    private $secret;

    private $algorithm = 'HS512';

    private $module;

    private $token;

    private $decodedToken;

    const AUTH_EXPIRATION = 3600;

    // one month in seconds
    const REFRESH_EXPIRATION = 2592000;

    const REFRESH_TOKEN_TABLE = 'mrshop_refresh_token';

    public function __construct($module, $token = null)
    {
        $this->secret = Configuration::get('MRSHOP_SECRET_JWT');
        $this->module = $module;
        $this->token = $token;
    }

    public function encode(Customer $user)
    {
        return $this->generateJWT($user, self::AUTH_EXPIRATION);
    }

    public function encodeRefreshToken(Customer $user)
    {
        $db = Db::getInstance(_PS_USE_SQL_SLAVE_);
        $token = $this->generateJWT($user, self::REFRESH_EXPIRATION);
        $sql = 'INSERT INTO '._DB_PREFIX_.self::REFRESH_TOKEN_TABLE.'(id_customer, refresh_token, expires_at)
            VALUES ('.(int) $user->id.", '".pSQL($token)."', DATE_ADD(NOW(), INTERVAL ".self::REFRESH_EXPIRATION.' SECOND))';

        $result = $db->execute($sql);

        /*
         * se non è stato possibile salvare il token sul db, lancio un eccezione
         * perchè per essere valido il token deve essere scritto sul db
         */
        if (empty($result)) {
            throw new \Exception('There was an error registering the refresh token');
        }

        if (rand(1, 10) < 5) {
            $sql = 'DELETE FROM '._DB_PREFIX_.self::REFRESH_TOKEN_TABLE.' WHERE expires_at < NOW()';
            // eventuali errori di scrittura sul db qui non vengono gestiti,
            // è solo una procedura di manutenzione della tabella che quindi non si merita
            // di creare problemi nella generazione del refresh token stesso
            $db->execute($sql);
        }

        return $token;
    }

    public function invalidateRefreshToken($token)
    {
        $sql = 'DELETE FROM '._DB_PREFIX_.self::REFRESH_TOKEN_TABLE." WHERE refresh_token = '".pSQL($token)."'";

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
    }

    public function verifyRefreshToken($token)
    {
        $decoded = $this->decode($token);
        if (empty($decoded['success'])) {
            return $decoded;
        }

        $sql = 'SELECT * FROM '._DB_PREFIX_.self::REFRESH_TOKEN_TABLE."
                WHERE refresh_token = '".pSQL($token)."' AND expires_at >= NOW()";

        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

        if (empty($row)) {
            return $this->createDecodePayload(false);
        }

        return $decoded;
    }

    private function generateJWT(Customer $user, $expiration)
    {
        $tokenId = Tools::passwdGen(32, 'RANDOM');

        $issuedAt = time();
        $expire = $issuedAt + $expiration;
        $serverName = 'mrshopapi';

        $data = [
            'iat' => $issuedAt,
            'jti' => $tokenId,
            'iss' => $serverName,
            'nbf' => $issuedAt,
            'exp' => $expire,
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ];

        return JWT::encode($data, $this->secret, $this->algorithm);
    }

    public function decodeToken()
    {
        if (!ApiUtils::isArray($this->decodedToken)) {
            $this->decodedToken = $this->decode($this->token);
        }

        return $this->decodedToken;
    }

    protected function decode($token)
    {
        try {
            $decoded = JWT::decode($token, $this->secret, [$this->algorithm]);
            $customerId = (int) $decoded->data->id;
            if ($customerId != 0) {
                return $this->createDecodePayload(true, $decoded);
            }
        } catch (\Exception $ex) {
            // do nothing
        }

        return $this->createDecodePayload(false);
    }

    protected function createDecodePayload($success, $decoded = null)
    {
        if ($success) {
            return [
                'success' => (bool) $success,
                'message' => null,
                'data' => (array) $decoded->data,
            ];
        } else {
            return [
                'success' => (bool) $success,
                'message' => $this->module->l('There was an error while retrieving data', 'jwthandler'),
                'data' => null,
            ];
        }
    }

    public function isAuth($throwErrorIfInvalidJwt)
    {
        if ($this->hasToken()) {
            $response = $this->decodeToken();
            if ($response['success'] === true) {
                return true;
            } else {
                if ($throwErrorIfInvalidJwt) {
                    ResponseHandler::unauthorized($this->module->l('Authentication failed', 'jwthandler'));
                }
            }
        }

        return false;
    }

    public function hasToken()
    {
        return !empty($this->token);
    }

    public function getCustomerId()
    {
        if (false == $this->hasToken()) {
            ResponseHandler::unauthorized($this->module->l('Authorization Required', 'jwthandler'));
        }

        $data = $this->decodeToken();
        $customerId = $data['success'] ? $data['data']['id'] : 0;

        if ($customerId == 0) {
            ResponseHandler::unauthorized($this->module->l('Authorization failed', 'jwthandler'));
        }

        return $customerId;
    }
}
