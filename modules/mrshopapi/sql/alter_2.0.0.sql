ALTER TABLE `PREFIX_notification` ADD column `id_group` int(10) unsigned NULL;

CREATE TABLE IF NOT EXISTS `PREFIX_notification_pool` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `id_notification` int(10) NOT NULL,
    `id_customer` int(10) NULL,
    `id_lang` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

