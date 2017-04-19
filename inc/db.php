<?php
function create_tables() {
    $prefix = get_theme_mod('entry_race_id');
	global $wpdb;
    $res = $wpdb->query("
CREATE TABLE IF NOT EXISTS `{$prefix}_person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fname` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `sname` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `ybirth` year(4) NOT NULL DEFAULT '1985',
  `sex` enum('m','w') DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET ascii NOT NULL,
  `email` varchar(50) CHARACTER SET ascii NOT NULL,
  `password` varchar(32) NOT NULL,
  `d_modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `password` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `p0_id` int(11) DEFAULT NULL,
  `p1_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;");
    }
    return $res;
}

function pwd_check($id, $pwd) {
    if(current_user_can('edit_pages')) return TRUE;
    $prefix = get_theme_mod('entry_race_id');
    global $wpdb;
    $sql = $wpdb->prepare("SELECT password FROM `{$prefix}_team` WHERE id = %d", $id);
    $db_pwd = $wpdb->get_var($sql);
    return ($db_pwd && $pwd == $db_pwd);
}

function tables_init() {
	$prefix = get_theme_mod('entry_race_id');
	if(empty($prefix)) {
		echo '<div class="errmsg">Není vyplněn identifikátor závodu.</div>';
	}
	if(create_tables() == 0){
		echo '<div class="errmsg">Tabulky se nepodařilo vytvořit.</div>';
	} else {
		echo '<div class="okmsg">Tabulky vytvořeny.</div>';
	}
}
add_shortcode('tbinit', 'tables_init');



?>
