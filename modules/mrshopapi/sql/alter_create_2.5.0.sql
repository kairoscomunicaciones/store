ALTER TABLE `PREFIX_mrshop_cms_page` ADD column `simple_cms` BOOLEAN NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_payment_module` (
    `id_payment_module` int unsigned NOT NULL auto_increment,
    `id_module` int unsigned,
    `use_in_app` BOOLEAN NOT NULL,
    `supported` BOOLEAN NOT NULL,
    PRIMARY KEY (`id_payment_module`)  ) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;