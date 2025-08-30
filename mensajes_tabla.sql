CREATE TABLE `mensajes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario_envio` bigint(20) DEFAULT NULL,
  `id_usuario_receptor` bigint(20) DEFAULT NULL,
  `mensaje` longtext,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `readed` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
