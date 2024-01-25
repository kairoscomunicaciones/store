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

use MrAPPs\MrShopApi\Utils\ApiUtils;

class ResponseHandler
{
    const BAD_REQUEST = 400;

    const UNAUTHORIZED = 401;

    const NOT_FOUND = 404;

    const METHOD_NOT_ALLOWED = 405;

    const SUCCESS = 200;

    const UNPROCESSABLE_ENTITY = 422;

    /**
     * Handle all HTTP responses
     *
     * @param int $status
     * @param string $error
     * @param array $data
     * @param int $maxAge
     */
    public static function response($status = 200, $error = null, $data = null, $maxAge = null)
    {
        header('Content-type: application/json');

        // Cache control
//        if ($maxAge) {
//            header('Cache-Control: must-revalidate, max-age=' . $maxAge);
//        }else {
//            header('Cache-Control: no-cache, must-revalidate');
//        }
        // 03/12/18 client cache disabled
        header('Cache-Control: no-cache, must-revalidate');

        http_response_code($status);

        if ($error != null) {
            echo json_encode(['message' => $error]);
        } else {
            echo ApiUtils::isArray($data) || is_object($data) ? json_encode($data) : $data;
        }
        exit();
    }

    /**
     * Handle all HTTP responses
     *
     * @param int $status
     * @param string $error
     * @param array $data
     * @param int $maxAge
     */
    public static function responseHtml($html, $status = 200)
    {
        header('Content-type: text/html');
        header('Cache-Control: no-cache, must-revalidate');

        http_response_code($status);

        echo $html;
        exit();
    }

    public static function notFound($error)
    {
        static::response(self::NOT_FOUND, $error);
    }

    public static function badRequest($error)
    {
        static::response(self::BAD_REQUEST, $error);
    }

    public static function methodNotAllowed($error)
    {
        static::response(self::METHOD_NOT_ALLOWED, $error);
    }

    public static function unauthorized($error)
    {
        static::response(self::UNAUTHORIZED, $error);
    }

    public static function success($data = [], $maxAge = null)
    {
        static::response(self::SUCCESS, null, $data, $maxAge);
    }

    public static function error($error)
    {
        static::response(self::BAD_REQUEST, $error, null);
    }

    public static function unprocessableEntity($error)
    {
        static::response(self::UNPROCESSABLE_ENTITY, $error, null);
    }

    public static function successfulEmptyResponse()
    {
        static::response(200, null, new \stdClass());
    }
}
