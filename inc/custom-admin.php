<?php
/**
 * Customization of Toolbar
 *
 */

// Add Toolbar Menus
function custom_toolbar() {
	global $wp_admin_bar;

	$args = array(
		'id'     => 'tbcreate',
		'title'  => 'Vytvořit tabulky',
		'href'   => get_home_url(NULL, 'tbcreate'),
	);
	$wp_admin_bar->add_menu( $args );

}
add_action( 'wp_before_admin_bar_render', 'custom_toolbar', 999 );
