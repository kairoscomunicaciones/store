CREATE TABLE IF NOT EXISTS `PREFIX_mrshop_refresh_token` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) NOT NULL,
  `refresh_token` TEXT character set utf8 NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
  `expires_at` datetime not null,
  PRIMARY KEY (`id`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;