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

class ProxyPresentResource extends AbstractProxyResource
{
    protected $path;
    
    public function __construct($path, $fqcn)
    {
        $this->path = $path;
        $this->fqcn = $fqcn;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return file_exists($this->path);
    }
    
    public static function create($path, $fqcn)
    {
        return new static($path, $fqcn);
    }
}
