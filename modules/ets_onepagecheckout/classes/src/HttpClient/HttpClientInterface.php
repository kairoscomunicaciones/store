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

namespace Hybridauth\HttpClient;

/**
 * HybridAuth Http clients interface
 */
interface HttpClientInterface
{
    /**
    * Send request to the remote server
    *
    * Returns the result (Raw response from the server) on success, FALSE on failure
    *
    * @param string $uri
    * @param string $method
    * @param array  $parameters
    * @param array  $headers
    *
    * @return mixed
    */
    public function request($uri, $method = 'GET', $parameters = array(), $headers = array());

    /**
    * Returns raw response from the server on success, FALSE on failure
    *
    * @return mixed
    */
    public function getResponseBody();

    /**
    * Retriever the headers returned in the response
    *
    * @return array
    */
    public function getResponseHeader();

    /**
    * Returns latest request HTTP status code
    *
    * @return integer
    */
    public function getResponseHttpCode();

    /**
    * Returns latest error encountered by the client
    * This can be either a code or error message
    *
    * @return mixed
    */
    public function getResponseClientError();
}
