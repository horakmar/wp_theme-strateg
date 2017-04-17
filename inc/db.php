<?php
function create_tables($prefix){
	global $wpdb;
    $res = $wpdb->query("
CREATE TABLE IF NOT EXISTS `{$prefix}_person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fname` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `nick` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `sname` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `ybirth` year(4) NOT NULL DEFAULT '1985',
  `club` varchar(64) NOT NULL,
  `sex` enum('m','w') DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET ascii NOT NULL,
  `email` varchar(50) CHARACTER SET ascii NOT NULL,
  `pcomment` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(32) NOT NULL,
  `date` datetime NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `shocart_id` int(10) unsigned NOT NULL,
  `meal` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    if($res > 0){
        $res = $wpdb->query("
CREATE TABLE IF NOT EXISTS `{$prefix}_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` varchar(80) COLLATE utf8_czech_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `admin_comment` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `d_modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_create` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(32) COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;");
    }
    return $res;
}
