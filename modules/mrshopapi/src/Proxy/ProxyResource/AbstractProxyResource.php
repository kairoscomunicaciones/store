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

namespace MrAPPs\MrShopApi\Proxy\ProxyResource;

use ReflectionClass;

abstract class AbstractProxyResource
{
    protected $fqcn;

    /**
     * Tells id the resource is usable
     * @return bool
     */
    abstract public function isValid();

    /**
     * Returns the resource fully qualified class name
     * @return string
     */
    public function getClassName()
    {
        return $this->fqcn;
    }

    /**
     * Returns a resource instance
     * @return mixed
     */
    public function createInstance($args)
    {
        $reflector = new ReflectionClass($this->fqcn);

        return $reflector->newInstanceArgs($args);
    }
}
