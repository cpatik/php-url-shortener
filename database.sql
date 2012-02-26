CREATE TABLE `redirect` (
	`slug` varchar(14) collate utf8_unicode_ci NOT NULL,
	`url` varchar(620) collate utf8_unicode_ci NOT NULL,
	`date` datetime NOT NULL,
	`hits` bigint(20) NOT NULL default '0',
	PRIMARY KEY (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Used for the URL shortener';

INSERT INTO `redirect` VALUES ('a', 'http://patik.com/about/', NOW(), 1);
INSERT INTO `redirect` VALUES ('t', 'http://patik.com/travel/', NOW(), 1);
INSERT INTO `redirect` VALUES ('c', 'http://patik.com/code/', NOW(), 1);
INSERT INTO `redirect` VALUES ('h', 'http://patik.com/html5/', NOW(), 1);
INSERT INTO `redirect` VALUES ('travel', 'http://patik.com/travel/', NOW(), 1);
INSERT INTO `redirect` VALUES ('bnb', 'http://patik.com/travel/britain-benelux/', NOW(), 1);
INSERT INTO `redirect` VALUES ('bb', 'http://patik.com/travel/britain-benelux/', NOW(), 1);
INSERT INTO `redirect` VALUES ('b', 'http://patik.com/blog/', NOW(), 1);
INSERT INTO `redirect` VALUES ('blog', 'http://patik.com/blog/', NOW(), 1);
INSERT INTO `redirect` VALUES ('tr', 'http://patik.com/travel/turkey/', NOW(), 1);
INSERT INTO `redirect` VALUES ('de', 'http://patik.com/travel/germany/', NOW(), 1);
INSERT INTO `redirect` VALUES ('es', 'http://patik.com/travel/spain/', NOW(), 1);
INSERT INTO `redirect` VALUES ('sa', 'http://patik.com/travel/peru-argentina/', NOW(), 1);
INSERT INTO `redirect` VALUES ('e', 'http://ellsass.com/', NOW(), 1);
INSERT INTO `redirect` VALUES ('ph', 'https://picasaweb.google.com/116513687533678150554', NOW(), 1);
INSERT INTO `redirect` VALUES ('url', 'https://github.com/cpatik/php-url-shortener', NOW(), 1);
INSERT INTO `redirect` VALUES ('git', 'https://github.com/cpatik', NOW(), 1);
