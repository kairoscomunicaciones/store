DELETE FROM `PREFIX_configuration` WHERE name LIKE 'PS_ROUTE_mrshopapi_client%';

ALTER TABLE `PREFIX_app_home` ADD column `banner_size` varchar(50) character set utf8 NULL;
ALTER TABLE `PREFIX_app_home` ADD column `hide_title_in_home` BOOLEAN NOT NULL DEFAULT 0;
