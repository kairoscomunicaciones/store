DELETE FROM
  `PREFIX_configuration`
WHERE
  name LIKE 'PS_ROUTE_mrshopapi_client%';

CREATE TABLE IF NOT EXISTS `PREFIX_notification` (
  `id_notification` int(10) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `active` BOOLEAN NOT NULL,
  `sent` BOOLEAN NOT NULL DEFAULT FALSE,
  `type` varchar(50) character set utf8 NOT NULL DEFAULT 'GENERIC',
  `id_product` int(10) unsigned NULL,
  `id_category` int(10) unsigned NULL,
  `id_manufacturer` int(10) unsigned NULL,
  `id_cms` int(10) unsigned NULL,
  `id_group` int(10) unsigned NULL,
  PRIMARY KEY (`id_notification`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_notification_lang` (
  `id_notification` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `title` varchar(255) character set utf8 NOT NULL,
  PRIMARY KEY (`id_notification`, `id_lang`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home` (
  `id_app_home` int(10) unsigned NOT NULL auto_increment,
  `position` int(10) unsigned DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 0,
  `hide_title_in_home` BOOLEAN NOT NULL DEFAULT 0,
  `active_from` datetime NULL,
  `active_to` datetime NULL,
  `type` varchar(50) character set utf8 NOT NULL,
  `banner_type` varchar(50) character set utf8 NULL,
  `banner_size` varchar(50) character set utf8 NULL,
  `order_by` varchar(50) character set utf8 NULL,
  `layout` int(10) unsigned NULL,
  `id_product` int(10) unsigned NULL,
  `id_category` int(10) unsigned NULL,
  `id_manufacturer` int(10) unsigned NULL,
  `id_cms` int(10) unsigned NULL,
  `hash` varchar(32) character set utf8 NULL,
  `carousel_type` VARCHAR(50) character set utf8 NULL,
  `display_in_all_groups` BOOLEAN NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_app_home`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_lang` (
  `id_app_home` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `title` varchar(255) character set utf8 NULL,
  `hash` varchar(32) character set utf8 NOT NULL,
  PRIMARY KEY (`id_app_home`, `id_lang`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_order_status_notifications` (
  `id_order_status_notification` int(10) unsigned NOT NULL auto_increment,
  `active` BOOLEAN NOT NULL DEFAULT 0,
  `id_order_state` int(10) unsigned NULL,
  PRIMARY KEY (`id_order_status_notification`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_cms_page` (
  `id_cms_page` int(10) unsigned NOT NULL auto_increment,
  `id_cms` int(10) unsigned NULL,
  `simple_cms` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_cms_page`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_refresh_token` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) NOT NULL,
  `refresh_token` TEXT character set utf8 NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime not null,
  PRIMARY KEY (`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_notification_pool` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_notification` int(10) NOT NULL,
  `id_customer` int(10) NULL,
  `id_lang` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_item` (
  `id_app_home_item` int unsigned NOT NULL auto_increment,
  `id_app_home` int unsigned,
  `banner_type` varchar(50) character set utf8 NULL,
  `hash` varchar(32) character set utf8 NOT NULL,
  `id_product` int(10) unsigned NULL,
  `id_category` int(10) unsigned NULL,
  `id_manufacturer` int(10) unsigned NULL,
  `id_cms` int(10) unsigned NULL,
  PRIMARY KEY (`id_app_home_item`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_item_lang` (
  `id_app_home_item_lang` int unsigned NOT NULL auto_increment,
  `id_app_home_item` int unsigned NOT NULL,
  `id_lang` int unsigned NOT NULL,
  `title` varchar(255) character set utf8 NULL,
  `lang_hash` varchar(32) character set utf8,
  PRIMARY KEY (`id_app_home_item_lang`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_category` (
    `id_app_home_category` int unsigned NOT NULL auto_increment,
    `id_app_home` int unsigned,
    `id_category` int(10) unsigned,
    PRIMARY KEY (`id_app_home_category`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_group` (
  `id_app_home_group` int unsigned NOT NULL auto_increment,
  `id_app_home` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_app_home_group`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_payment_module` (
 `id_payment_module` int unsigned NOT NULL auto_increment,
 `id_module` int unsigned,
 `use_in_app` BOOLEAN NOT NULL,
 `supported` BOOLEAN NOT NULL,
 PRIMARY KEY (`id_payment_module`)  ) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
