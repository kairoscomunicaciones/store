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

namespace Hybridauth\User;

use Hybridauth\Exception\UnexpectedValueException;

/**
 * Hybridauth\User\Contact
 */
final class Contact
{
    /**
    * The Unique contact user ID
    *
    * @var string
    */
    public $identifier = null;

    /**
    * User website, blog, web page
    *
    * @var string
    */
    public $webSiteURL = null;

    /**
    * URL link to profile page on the IDp web site
    *
    * @var string
    */
    public $profileURL = null;

    /**
    * URL link to user photo or avatar
    *
    * @var string
    */
    public $photoURL = null;

    /**
    * User displayName provided by the IDp or a concatenation of first and last name
    *
    * @var string
    */
    public $displayName = null;

    /**
    * A short about_me
    *
    * @var string
    */
    public $description = null;

    /**
    * User email. Not all of IDp grant access to the user email
    *
    * @var string
    */
    public $email = null;

    /**
    * Prevent the providers adapters from adding new fields.
    *
    * @param string $name
    * @param mixed $value
    *
    * @throws UnexpectedValueException
    */
    public function __set($name, $value)
    {
	    unset($value);
        throw new UnexpectedValueException(sprintf('Adding new property "%s" to %s is not allowed.', $name, __CLASS__));
    }
}
