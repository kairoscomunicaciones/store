<?php
/**
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
* @author    FMM Modules
* @copyright 2022 FMM Modules All right reserved
* @license   FMM Modules
*/

$sql = array();

// Table custom_field
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field(
        `id_custom_field` int(11) unsigned NOT NULL auto_increment,
        `field_type`            enum(\'text\',\'textarea\',\'date\',\'boolean\',\'multiselect\',\'select\',\'checkbox\',\'radio\',\'message\',\'attachment\') default \'text\',
        `field_validation`      varchar(255) default NULL,
        `field_placement`       varchar(255) default NULL,
        `position`              tinyint(4) default 0,
        `assoc_shops`           varchar(255) default '.(string)Context::getContext()->shop->id.',
        `value_required`        tinyint(1) default NULL,
        `editable`              tinyint(1) default 1,
        `extensions`            varchar(128) DEFAULT \'jpg\',
        `products`              TEXT,
        `attachment_size`       DECIMAL(10,2) NOT NULL DEFAULT \'2.0\',
        `show_customer`         tinyint(1) default 1,
        `show_email`            tinyint(1) default 1,
        `show_admin`            tinyint(1) default 1,
        `active`                tinyint(1) default 1,
        `dependant`             tinyint(1) default \'0\',
        `dependant_field`       int(11) default \'0\',
        `dependant_value`       int(11) default \'0\',
        `id_heading`            int(11) default \'0\',
        `created_time`          datetime default NULL,
        `update_time`           datetime default NULL,
        PRIMARY KEY             (`id_custom_field`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_lang(
        `id_custom_field`       int(11) NOT NULL auto_increment,
        `id_lang`               int(11) NOT NULL,
        `field_name`            varchar(255) default NULL,
        `default_value`            varchar(255) default NULL,
        PRIMARY KEY             (`id_custom_field`,`id_lang`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_values(
        `field_value_id`        int(11) NOT NULL auto_increment,
        `id_custom_field`       int(11) NOT NULL,
        `field_value`           text,
        PRIMARY KEY             (`field_value_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_userdata(
        `value_id`              int(10) unsigned NOT NULL auto_increment,
        `id_custom_field`       int(10) unsigned default NULL,
        `field_value`           mediumtext,
        `id_cart`               int(10) unsigned default NULL,
        `id_order`              int(10) unsigned default NULL,
        PRIMARY KEY             (`value_id`),
        UNIQUE KEY `uniq`       (`id_custom_field`,`id_cart`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_shop(
        `id_custom_field`       int(11) NOT NULL,
        `id_shop`               int(11) NOT NULL,
        PRIMARY KEY             (`id_custom_field`,`id_shop`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_group (
        `id_custom_field` int(10) NOT NULL,
        `id_group` int(10) NOT NULL,
        PRIMARY KEY (`id_custom_field`, `id_group`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custom_field_categories (
        `id_custom_field` int(10) NOT NULL,
        `id_category` int(10) NOT NULL,
        PRIMARY KEY (`id_custom_field`, `id_category`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'custom_field_headings(
        `id_custom_field_headings` int(11) NOT NULL auto_increment,
        PRIMARY KEY (`id_custom_field_headings`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'custom_field_headings_lang(
        `id_custom_field_headings` int(11) NOT NULL,
        `id_lang` int(11) NOT NULL,
        `title` varchar(255) default NULL,
        PRIMARY KEY (`id_custom_field_headings`,`id_lang`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
