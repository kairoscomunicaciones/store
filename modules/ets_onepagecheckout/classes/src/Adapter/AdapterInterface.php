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

namespace Hybridauth\Adapter;

use Hybridauth\HttpClient\HttpClientInterface;
use Hybridauth\Storage\StorageInterface;
use Hybridauth\Logger\LoggerInterface;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * Initiate the appropriate protocol and process/automate the authentication or authorization flow.
     *
     * @return boolean|null
     */
    public function authenticate();

    /**
     * Returns TRUE if the user is connected
     *
     * @return boolean
     */
    public function isConnected();

    /**
     * Clear all access token in storage
     */
    public function disconnect();

    /**
     * Retrieve the connected user profile
     *
     * @return \Hybridauth\User\Profile
     */
    public function getUserProfile();

    /**
     * Retrieve the connected user contacts list
     *
     * @return \Hybridauth\User\Contact[]
     */
    public function getUserContacts();

    /**
     * Retrieve the connected user pages|companies|groups list
     *
     * @return array
     */
    public function getUserPages();

    /**
     * Retrieve the user activity stream
     *
     * @param string $stream
     *
     * @return \Hybridauth\User\Activity[]
     */
    public function getUserActivity($stream);

    /**
     * Post a status on user wall|timeline|blog|website|etc.
     *
     * @param string|array $status
     *
     * @return mixed API response
     */
    public function setUserStatus($status);

    /**
     * Post a status on page|company|group wall.
     *
     * @param string|array $status
     * @param string $pageId
     *
     * @return mixed API response
     */
    public function setPageStatus($status, $pageId);

    /**
     * Send a signed request to provider API
     *
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @param array $headers
     *
     * @return mixed
     */
    public function apiRequest($url, $method = 'GET', $parameters = array(), $headers = array());

    /**
     * Return oauth access tokens.
     *
     * @return array
     */
    public function getAccessToken();

    /**
     * Set oauth access tokens.
     *
     * @param array $tokens
     */
    public function setAccessToken($tokens = array());

    /**
     * Set http client instance.
     *
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient = null);

    /**
     * Return http client instance.
     */
    public function getHttpClient();

    /**
     * Set storage instance.
     *
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage = null);

    /**
     * Return storage instance.
     */
    public function getStorage();

    /**
     * Set Logger instance.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null);

    /**
     * Return logger instance.
     */
    public function getLogger();
}
