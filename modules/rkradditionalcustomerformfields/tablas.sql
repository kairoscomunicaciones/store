CREATE TABLE IF NOT EXISTS `PREFIX_rkr_additional_form_fields` (
    `id_form_field` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(64) NOT NULL,
    `type` varchar(64),
    `default_value` text,
    `required` tinyint(1) NOT NULL,
    `required_address` tinyint(1) NOT NULL DEFAULT '0',
    `maxLength` INTEGER UNSIGNED,
    `constraint` varchar(64),
    `active` tinyint(1) NOT NULL DEFAULT '1',
    `position` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_form_field`),
    KEY (`name`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET='utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_rkr_additional_form_fields_lang` (
    `id_form_field` INTEGER UNSIGNED NOT NULL,
    `id_lang`  INTEGER UNSIGNED NOT NULL,
    `label` text,
    PRIMARY KEY (`id_form_field`,`id_lang`),
    KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET='utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_rkr_available_values_form_fields` (
    `id_available_value` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_form_field` INTEGER UNSIGNED NOT NULL,
    `value` text,
    `label` varchar(64),
    PRIMARY KEY (`id_available_value`),
    KEY `label` (`label`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET='utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_rkr_customer_form_fields_values` (
    `id_customer` INTEGER UNSIGNED NOT NULL,
    `id_form_field` INTEGER UNSIGNED NOT NULL,
    `value` text,
    PRIMARY KEY (`id_customer`,`id_form_field`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET='utf8';


