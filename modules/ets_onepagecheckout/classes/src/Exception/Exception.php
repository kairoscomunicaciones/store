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
 * Hybridauth Base Exception
 */
class Exception extends \Exception implements ExceptionInterface
{
    /**
    * Shamelessly Borrowed from Slimframework
    *
    * @param $object
    */
    public function debug($object)
    {
        $title   = "Hybridauth Exception\r\n";
        $code    = $this->getCode();
        $message = $this->getMessage();
        $file    = $this->getFile();
        $line    = $this->getLine();
        $trace   = $this->getTraceAsString();

        $html  = $title."\r\n";
        $html .= "HybridAuth has encountered the following error:\n";
        $html .= "Details\n";
        $html .= "Exception:".get_class($this);
        $html .= "Message:".print_r($message);
        $html .= "File:".print_r($file);
        $html .= "File:".print_r($line);
        $html .= "File:".print_r($code);
        $html .= "Trace\r\n";
        $html .= print_r($trace);
        if ($object) {
            $html .= "Debug\r\n";
            $obj_dump = print_r($object, true);
            $html .= get_class($object)." extends ".get_parent_class($object).print_r($obj_dump);
        }
        $html .= "Session\r\n";
	    $cookie_dump = print_r($_COOKIE, true);
	    $html .= $cookie_dump;

        echo $title.$html;
    }
}
