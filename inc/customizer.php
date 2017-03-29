<?php
/**
 * strateg Theme Customizer
 *
 * @package strateg
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function strateg_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->add_section( 'title_image_section' , array(
    	'title'      => __( 'Title Image', 'strateg' ),
        'priority'   => 90
    ) );
    $wp_customize->add_setting( 'title_image_toggle', array( 
    	'default' => 1
    ) );
    $wp_customize->add_control( 'title_image_toggle', array(
    	'label'     => __( 'Show title image?', 'strateg' ),
    	'section'   => 'title_image_section',
	    'type'      => 'checkbox',
	    'priority'  => 10
    ) );
}
add_action( 'customize_register', 'strateg_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function strateg_customize_preview_js() {
	wp_enqueue_script( 'strateg_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'strateg_customize_preview_js' );
