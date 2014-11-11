DROP TABLE IF EXISTS `{prefix}reports`;

CREATE TABLE IF NOT EXISTS `{prefix}reports` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `reporter_id` int(20) NOT NULL,
  `owner_id` int(20) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
