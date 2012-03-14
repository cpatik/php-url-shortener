CREATE TABLE `redirect` (
	`slug` varchar(14) collate utf8_unicode_ci NOT NULL,
	`url` varchar(620) collate utf8_unicode_ci NOT NULL,
	`date` datetime NOT NULL,
	`hits` bigint(20) NOT NULL default '0',
	PRIMARY KEY (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Used for the URL shortener';

INSERT INTO `redirect` VALUES ('c', 'http://patik.com/code/', NOW(), 1);
INSERT INTO `redirect` VALUES ('git', 'https://github.com/cpatik', NOW(), 1);
