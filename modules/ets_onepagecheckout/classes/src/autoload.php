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

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new Exception('Hybridauth 3 requires PHP version 5.4 or higher.');
}
/**
 * Register the autoloader for Hybridauth classes.
 *
 * Based off the official PSR-4 autoloader example found at
 * http://www.php-fig.org/psr/psr-4/examples/
 *
 * @param string $class The fully-qualified class name.
 *
 * @return void
 */
spl_autoload_register(
    function ($class) {
        // project-specific namespace prefix. Will only kicks in for Hybridauth's namespace.
        $prefix = 'Hybridauth\\';

        // base directory for the namespace prefix.
        $base_dir = dirname(__FILE__);// By default, it points to this same folder.
                               // You may change this path if having trouble detecting the path to
                               // the source files.

        // does the class use the namespace prefix?
        $len = \Tools::strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader.
            return;
        }

        // get the relative class name.
        $relative_class = \Tools::substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $relative_class).'.php';

        // if the file exists, require it
        if (file_exists($file)) {

            require $file;
        }
    }
);
