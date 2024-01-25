ALTER TABLE `PREFIX_notification` ADD `id_cms` int(10) unsigned NULL;
ALTER TABLE `PREFIX_app_home` ADD `id_cms` int(10) unsigned NULL;
ALTER TABLE `PREFIX_notification` MODIFY COLUMN `date` DATETIME NOT NULL;
