ALTER TABLE `PREFIX_notification` ADD `sent` BOOLEAN NOT NULL DEFAULT FALSE;
UPDATE `PREFIX_notification` SET `sent` = TRUE WHERE `sent` <= NOW();