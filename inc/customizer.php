<?php
/**
 * Customizer settings that are not content (logo, colours, misc).
 *
 * Content-level settings live in ACF Theme Options; this file only holds the
 * pieces WordPress users expect to find in the Customizer.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function arkan_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_section(
		'arkan_layout',
		array(
			'title'    => __( 'Arkan Layout', 'arkan' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'arkan_blog_layout',
		array(
			'default'           => 'sidebar-right',
			'sanitize_callback' => 'arkan_sanitize_blog_layout',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'arkan_blog_layout',
		array(
			'label'   => __( 'Blog Layout', 'arkan' ),
			'section' => 'arkan_layout',
			'type'    => 'select',
			'choices' => array(
				'sidebar-right' => __( 'List with right sidebar', 'arkan' ),
				'full'          => __( 'Full width list', 'arkan' ),
			),
		)
	);

	$wp_customize->add_setting(
		'arkan_single_sidebar',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'arkan_single_sidebar',
		array(
			'label'   => __( 'Show sidebar on single posts', 'arkan' ),
			'section' => 'arkan_layout',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'arkan_accent_color',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'arkan_accent_color',
			array(
				'label'       => __( 'Accent Colour', 'arkan' ),
				'description' => __( 'Overrides the gold accent used for links, buttons and highlighted words.', 'arkan' ),
				'section'     => 'colors',
			)
		)
	);
}
add_action( 'customize_register', 'arkan_customize_register' );

/**
 * Sanitize the blog layout choice.
 *
 * @param string $value Value.
 * @return string
 */
function arkan_sanitize_blog_layout( $value ) {
	return in_array( $value, array( 'sidebar-right', 'full' ), true ) ? $value : 'sidebar-right';
}

/**
 * Print the accent colour override.
 */
function arkan_customizer_css() {
	$accent = get_theme_mod( 'arkan_accent_color' );
	if ( ! $accent ) {
		return;
	}
	?>
	<style id="arkan-customizer-css">
		:root { --arkan-accent: <?php echo esc_attr( $accent ); ?>; }
		a:hover,
		.section-title span,
		.navbar .navbar-nav .nav-link:hover,
		.navbar .navbar-nav .nav-link.active,
		.sub-title,
		.services .item .con .numb,
		.contact .phone,
		.footer .item .phone,
		.blog .wrap .con .title a:hover,
		.sidebar .widget.category li span a:hover,
		.sidebar .widget.last-post-thum .item .cont h6 a:hover { color: <?php echo esc_attr( $accent ); ?>; }
		.button-light:hover,
		input[type="submit"]:hover,
		.pagination-wrap li a:hover,
		.pagination-wrap li a.active { background-color: <?php echo esc_attr( $accent ); ?>; border-color: <?php echo esc_attr( $accent ); ?>; }
	</style>
	<?php
}
add_action( 'wp_head', 'arkan_customizer_css', 20 );

/**
 * Live preview JS for the site title.
 */
function arkan_customize_preview_js() {
	wp_enqueue_script(
		'arkan-customizer-preview',
		ARKAN_URI . 'assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		ARKAN_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'arkan_customize_preview_js' );
