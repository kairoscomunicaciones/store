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

namespace MrAPPs\MrShopApi\Service\MrShopCustomer;

use Context;
use MrAPPs\MrShopApi\Utils\ApiUtils;

class MrShopCustomerRequest
{
    protected $path;

    protected $base;

    protected $body = [];

    protected $method;

    protected $headers = [];

    protected $queryString = [];

    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path   = $path;
        $this->base = _MRSHOP_API_URL_;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getJsonBody()
    {
        return json_encode($this->body);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setQueryString($query_string)
    {
        $this->queryString = $query_string;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getUrl()
    {
        return $this->base.'api'.$this->getPath().$this->getQueryString();
    }

    private function getQueryString()
    {
        $fullQueryString = [
            'secretCode' => \Configuration::get('MRSHOP_SECRET'),
            'shopUrl' => ApiUtils::getFullDomain(),
            'mrshopVersion' => ApiUtils::getModule()->version,
            'lang' => \Context::getContext()->language->iso_code,
        ];

        if (!empty($this->queryString)) {
            $fullQueryString = array_merge($fullQueryString, $this->queryString);
        }

        return '?'.http_build_query($fullQueryString);
    }

    public static function create($method, $path)
    {
        return new static($method, $path);
    }
}
