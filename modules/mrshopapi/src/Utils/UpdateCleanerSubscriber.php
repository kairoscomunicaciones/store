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

namespace MrAPPs\MrShopApi\Utils;

use MrAPPs\ModuleCleaner\CleaningSubscriberInterface;
use Tools;

class UpdateCleanerSubscriber implements CleaningSubscriberInterface
{
    private $tag;

    private $filename;
    
    public function __construct($tag)
    {
        $this->tag = $tag;
        $this->filename = $this->getLogDir().self::slugify($tag).'.log';
    }
    
    public function cleaned($path, $type)
    {
        $this->log('Removed '.$type.' '.$path);
    }

    public function cleaning($path, $type)
    {
        $this->log('Cleaning '.$type.' '.$path);
    }

    public function cleaningEmptyFolder($path)
    {
        $this->log('Cleaning empty folder '.$path);
    }

    public function nodeNotFound($path, $type)
    {
        $this->log('Node of type '.$type.' not found');
    }

    public function notcleaned($path, $type)
    {
        $this->log('Unable to clean node '.$type.' '.$path);
    }
    
    public function log($action)
    {
        $msg = '['.$this->tag.'] '.$action."\r\n";
        file_put_contents($this->filename, $msg, FILE_APPEND);
    }
    
    protected function getLogDir()
    {
        if (is_dir(_PS_ROOT_DIR_.'/log')) {
            return _PS_ROOT_DIR_.'/log/';
        } elseif (is_dir(_PS_ROOT_DIR_.'/app/logs')) {
            return _PS_ROOT_DIR_.'/app/logs/';
        } else {
            return _PS_ROOT_DIR_.'/var/logs/';
        }
    }
    
    protected static function slugify($text, $divider = '_')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = Tools::strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
