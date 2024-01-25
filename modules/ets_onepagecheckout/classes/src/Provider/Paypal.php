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

namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;
use Hybridauth\User;

/**
 * GitLab OAuth2 provider adapter.
 */
class Paypal extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $scope = 'openid profile email';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.sandbox.paypal.com';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://www.sandbox.paypal.com/connect';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://api.sandbox.paypal.com/v1/oauth2/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developer.paypal.com/docs/api/overview/#';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();
        $this->AuthorizeUrlParameters += [
            'flowEntry' => 'static'
        ];

        $this->tokenExchangeHeaders = [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ];

        $this->tokenRefreshHeaders = [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ];
        if (!(int)\Configuration::get('ETS_OPC_PAYPAL_SANBOX_ENABLED'))
        {
            $this->apiBaseUrl = 'https://api.paypal.com/';
            $this->authorizeUrl = 'https://www.paypal.com/signin/authorize';//'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
            $this->accessTokenUrl = 'https://api.paypal.com/v1/oauth2/token';//'https://api.paypal.com/v1/identity/openidconnect/tokenservice';
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $parameters = [
            'schema' => 'paypalv1.1'
        ];

        $response = $this->apiRequest('v1/identity/oauth2/userinfo', 'GET', $parameters, $headers);
        $data = new Data\Collection($response);

        if (!$data->exists('user_id')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }
        $email = $data->get('email');
        if(!$email){
            $emails = $data->get('emails');
            if($emails && is_array($emails) && isset($emails[0]) && isset($emails[0]->value)){
                $email=$emails[0]->value;
            }
        }
        $userProfile = new User\Profile();
        $userProfile->identifier  = $this->getIdentifier($data->get('user_id'));
        $userProfile->displayName = $data->get('name');
        $userProfile->email = $email;
        $userProfile->emailVerified = $email;
        $userProfile->language = $data->get('language');
        $userProfile->birthDay = $data->get('birthday');
        $userProfile->profileURL  = $data->get('user_id');
        return $userProfile;
    }
    public function getIdentifier($identifier)
    {
        if ($identifier && \Validate::isUrl($identifier) && preg_match('(([^/]*)/*$)', $identifier, $result))
        {
            return trim($result[1]);
        }
        return $identifier;
    }
}
