CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_refresh_token_new` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) NOT NULL,
  `refresh_token` TEXT character set utf8 NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
  `expires_at` datetime not null,
  PRIMARY KEY (`id`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;



INSERT INTO `PREFIX_mrshop_refresh_token_new`(`id_customer`, `refresh_token`, `created_at`, `expires_at`) SELECT `id_customer`, `refresh_token`, `created_at`, `expires_at` FROM `PREFIX_mrshop_refresh_token`;



RENAME TABLE `PREFIX_mrshop_refresh_token` TO `PREFIX_mrshop_refresh_token_old`, `PREFIX_mrshop_refresh_token_new` TO `PREFIX_mrshop_refresh_token`;