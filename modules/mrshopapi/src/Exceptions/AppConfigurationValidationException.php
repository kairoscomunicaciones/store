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

namespace MrAPPs\MrShopApi\Exceptions;

class AppConfigurationValidationException extends \RuntimeException
{
    private $messages;

    public function __construct($messages, $code = 0, \Throwable $previous = null)
    {
        $this->messages = is_array($messages)
                ? $messages
                : [$messages];

        $message = implode(', ', $this->messages);

        parent::__construct($message, $code, $previous);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
