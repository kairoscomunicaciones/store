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

abstract class AbstractProxy
{
    /** @var \MrAPPs\MrShopApi\Proxy\ProxyResource\AbstractProxyResource */
    protected static $reference;

    protected $instance;

    public function __construct($args)
    {
        static::load();
        $this->instance = static::$reference->createInstance($args);
    }

    private static function load()
    {
        if (isset(static::$reference)) {
            return;
        }
        $resource = static::getResource();
        /** @var \MrAPPs\MrShopApi\Proxy\ProxyResource\AbstractProxyResource $res */
        foreach ($resource as $res) {
            if ($res->isValid()) {
                static::$reference = $res;

                return;
            }
        }

        throw new \BadMethodCallException("Proxy Resource not found");
    }

    public static function __callStatic($name, $arguments)
    {
        static::load();

        if (false == method_exists(self::$reference->getClassName(), $name)) {
            throw new \BadMethodCallException("Method $name does not exists on class ".self::$reference->getClassName());
        }

        return call_user_func_array(self::$reference->getClassName()."::".$name, $arguments);
    }

    public function __call($name, $arguments)
    {
        if (false == method_exists($this->instance, $name)) {
            throw new \BadMethodCallException("Method $name does not exists on class ".self::$reference->getClassName());
        }

        return call_user_func_array([$this->instance, $name], $arguments);
    }

    /**
     * Returns a list of resource in priority order (higher priority first)
     * @return \MrAPPs\MrShopApi\Proxy\ProxyResource\AbstractProxyResource[]
     */
    abstract protected static function getResource();
}
