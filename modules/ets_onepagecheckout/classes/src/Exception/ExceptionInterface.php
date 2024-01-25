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

namespace Hybridauth\Exception;

/**
 * Hybridauth Exceptions Interface
 */
interface ExceptionInterface
{
    /*
    ExceptionInterface
    Exception                                             extends \Exception implements ExceptionInterface
    |   RuntimeException                                  extends Exception
    |   |    UnexpectedValueException                     extends RuntimeException
    |   |    |    AuthorizationDeniedException            extends UnexpectedValueException
    |   |    |    HttpClientFailureException              extends UnexpectedValueException
    |   |    |    HttpRequestFailedException              extends UnexpectedValueException
    |   |    |    InvalidAuthorizationCodeException       extends UnexpectedValueException
    |   |    |    InvalidAuthorizationStateException      extends UnexpectedValueException
    |   |    |    InvalidOauthTokenException              extends UnexpectedValueException
    |   |    |    InvalidAccessTokenException             extends UnexpectedValueException
    |   |    |    UnexpectedApiResponseException          extends UnexpectedValueException
    |   |
    |   |    BadMethodCallException                       extends RuntimeException
    |   |    |   NotImplementedException                  extends BadMethodCallException
    |   |
    |   |    InvalidArgumentException                     extends RuntimeException
    |   |    |   InvalidApplicationCredentialsException   extends InvalidArgumentException
    |   |    |   InvalidOpenidIdentifierException         extends InvalidArgumentException
*/
}
