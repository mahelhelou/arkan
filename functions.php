<?php
/**
 * Arkan theme bootstrap.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

define( 'ARKAN_VERSION', '1.0.0' );
define( 'ARKAN_DIR', trailingslashit( get_template_directory() ) );
define( 'ARKAN_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * Theme supports, menus, image sizes.
 */
function arkan_setup() {
	load_theme_textdomain( 'arkan', ARKAN_DIR . 'languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'arkan' ),
			'footer'  => __( 'Footer Bottom Menu', 'arkan' ),
		)
	);

	// Image sizes used across the design.
	add_image_size( 'arkan-slider', 1920, 1080, true );   // Hero slides / page banners.
	add_image_size( 'arkan-project', 800, 800, true );    // Project grid + carousel cards.
	add_image_size( 'arkan-project-wide', 1200, 700, true ); // Homepage "projects2" masonry cards.
	add_image_size( 'arkan-blog', 800, 520, true );       // Blog cards + blog list.
	add_image_size( 'arkan-thumb', 120, 100, true );      // Sidebar latest posts.
	add_image_size( 'arkan-team', 600, 750, true );       // Team / about portraits.
}
add_action( 'after_setup_theme', 'arkan_setup' );

/**
 * Content width.
 */
function arkan_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'arkan_content_width', 1140 );
}
add_action( 'after_setup_theme', 'arkan_content_width', 0 );

/**
 * Sidebars.
 */
function arkan_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'arkan' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Shown on the blog list, archives, search and single posts.', 'arkan' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6 class="title-widget">',
			'after_title'   => '</h6>',
		)
	);
}
add_action( 'widgets_init', 'arkan_widgets_init' );

require ARKAN_DIR . 'inc/helpers.php';
require ARKAN_DIR . 'inc/enqueue.php';
require ARKAN_DIR . 'inc/nav-walker.php';
require ARKAN_DIR . 'inc/post-types.php';
require ARKAN_DIR . 'inc/acf-fields.php';
require ARKAN_DIR . 'inc/template-tags.php';
require ARKAN_DIR . 'inc/comment-walker.php';
require ARKAN_DIR . 'inc/customizer.php';
require ARKAN_DIR . 'inc/admin-notices.php';
