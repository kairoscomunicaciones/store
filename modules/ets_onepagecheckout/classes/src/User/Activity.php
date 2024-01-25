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
 * Hybridauth\User\Activity
 */
final class Activity
{
    /**
    * activity id on the provider side, usually given as integer
    *
    * @var string
    */
    public $id = null;

    /**
    * activity date of creation
    *
    * @var string
    */
    public $date = null;

    /**
    * activity content as a string
    *
    * @var string
    */
    public $text = null;

    /**
    * user who created the activity
    *
    * @var object
    */
    public $user = null;

    /**
    *
    */
    public function __construct()
    {
        $this->user = new \stdClass();

        // typically, we should have a few information about the user who created the event from social apis
        $this->user->identifier  = null;
        $this->user->displayName = null;
        $this->user->profileURL  = null;
        $this->user->photoURL    = null;
    }

    /**
    * Prevent the providers adapters from adding new fields.
    *
    * @var string $name
    * @var mixed  $value
    *
    * @throws Exception\UnexpectedValueException
    */
    public function __set($name, $value)
    {
	    unset($value);
        throw new UnexpectedValueException(sprintf('Adding new property "%s\' to %s is not allowed.', $name, __CLASS__));
    }
}
