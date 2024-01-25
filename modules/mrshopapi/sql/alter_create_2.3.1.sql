ALTER TABLE `PREFIX_app_home`
ADD COLUMN `display_in_all_groups` BOOLEAN NOT NULL DEFAULT 1;

CREATE TABLE IF NOT EXISTS `PREFIX_app_home_group` (
    `id_app_home_group` int(10) unsigned NOT NULL auto_increment,
    `id_app_home` int(10) unsigned NOT NULL,
    `id_group` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id_app_home_group`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;