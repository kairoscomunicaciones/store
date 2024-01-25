ALTER TABLE `PREFIX_app_home` ADD COLUMN `carousel_type` VARCHAR(50) character set utf8 NULL;
ALTER TABLE `PREFIX_app_home_lang` ADD COLUMN `hash` VARCHAR(32) character set utf8 NULL;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_item` (
    `id_app_home_item` int unsigned NOT NULL auto_increment,
    `id_app_home` int unsigned,
    `banner_type` varchar(50) character set utf8 NULL,
    `hash` varchar(32) character set utf8,
    `id_product` int(10) unsigned NULL,
    `id_category` int(10) unsigned NULL,
    `id_manufacturer` int(10) unsigned NULL,
    `id_cms` int(10) unsigned NULL,
    PRIMARY KEY (`id_app_home_item`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_item_lang` (
    `id_app_home_item_lang` int unsigned NOT NULL auto_increment,
    `id_app_home_item` int unsigned NOT NULL,
    `id_lang` int unsigned NOT NULL,
    `title` varchar(255) character set utf8 NULL,
    `lang_hash` varchar(32) character set utf8,
    PRIMARY KEY (`id_app_home_item_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_category` (
    `id_app_home_category` int unsigned NOT NULL auto_increment,
    `id_app_home` int unsigned,
    `id_category` int(10) unsigned,
    PRIMARY KEY (`id_app_home_category`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

ALTER TABLE `PREFIX_mrshop_cms_page` DROP COLUMN `active`;