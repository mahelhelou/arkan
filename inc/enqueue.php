<?php
/**
 * Styles and scripts.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end assets, loaded in the same order as the original HTML template.
 */
function arkan_enqueue_assets() {
	$css = ARKAN_URI . 'assets/css/';
	$js  = ARKAN_URI . 'assets/js/';
	$mod = ARKAN_URI . 'assets/modules/';

	// Fonts.
	wp_enqueue_style(
		'arkan-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,300;0,400;1,300;1,400&family=Oswald:wght@300;400&display=swap',
		array(),
		null
	);

	// Vendor styles.
	wp_enqueue_style( 'bootstrap', $css . 'bootstrap.min.css', array(), '5.2.0' );
	wp_enqueue_style( 'animate', $css . 'animate.css', array(), '3.7.2' );
	wp_enqueue_style( 'themify-icons', $css . 'themify-icons.css', array(), '1.0.0' );
	wp_enqueue_style( 'owl-carousel', $css . 'owl.carousel.min.css', array(), '2.3.4' );
	wp_enqueue_style( 'owl-theme-default', $css . 'owl.theme.default.min.css', array( 'owl-carousel' ), '2.3.4' );
	wp_enqueue_style( 'magnific-popup', $mod . 'magnific-popup/magnific-popup.css', array(), '1.1.0' );
	wp_enqueue_style( 'youtube-popup', $mod . 'YouTubePopUp/YouTubePopUp.css', array(), '1.0.0' );

	// Theme design.
	wp_enqueue_style(
		'arkan-style',
		$css . 'style.css',
		array( 'bootstrap', 'animate', 'themify-icons', 'owl-theme-default', 'magnific-popup' ),
		ARKAN_VERSION
	);

	// WordPress-specific corrections (CF7, comments, core block/alignment classes).
	wp_enqueue_style( 'arkan-wp', $css . 'wp-overrides.css', array( 'arkan-style' ), ARKAN_VERSION );

	// The stylesheet WordPress expects to identify the theme.
	wp_enqueue_style( 'arkan-theme', get_stylesheet_uri(), array( 'arkan-wp' ), ARKAN_VERSION );

	/*
	 * Scripts.
	 *
	 * WordPress' own jQuery (3.7.x) is used rather than the bundled 3.6.1 file,
	 * so plugins that depend on the core handle keep working. The bundled copy
	 * stays in assets/js/plugins/ for reference only.
	 */
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'bootstrap', $js . 'plugins/bootstrap.min.js', array( 'jquery' ), '5.2.0', true );
	wp_enqueue_script( 'modernizr', $js . 'plugins/modernizr-2.6.2.min.js', array(), '2.6.2', true );
	wp_enqueue_script( 'waypoints', $js . 'plugins/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
	wp_enqueue_script( 'imagesloaded-pkgd', $js . 'plugins/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.4', true );
	wp_enqueue_script( 'isotope', $js . 'plugins/jquery.isotope.v3.0.2.js', array( 'jquery' ), '3.0.2', true );
	wp_enqueue_script( 'owl-carousel', $js . 'plugins/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
	wp_enqueue_script( 'scrollit', $js . 'plugins/scrollIt.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'magnific-popup', $mod . 'magnific-popup/jquery.magnific-popup.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'masonry-pkgd', $mod . 'masonry/masonry.pkgd.min.js', array( 'jquery' ), '4.2.2', true );
	wp_enqueue_script( 'youtube-popup', $mod . 'YouTubePopUp/YouTubePopUp.js', array( 'jquery' ), '1.0.0', true );

	wp_enqueue_script(
		'arkan-script',
		$js . 'script.js',
		array( 'jquery', 'bootstrap', 'waypoints', 'imagesloaded-pkgd', 'isotope', 'owl-carousel', 'scrollit', 'magnific-popup', 'masonry-pkgd' ),
		ARKAN_VERSION,
		true
	);

	// The original script.js swapped the logo on scroll using a hardcoded path.
	$logo_id  = (int) get_theme_mod( 'custom_logo' );
	$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : ARKAN_URI . 'assets/images/logo.png';

	wp_localize_script(
		'arkan-script',
		'arkanVars',
		array(
			'logo'       => $logo_url,
			'logoScroll' => $logo_url,
		)
	);

	/*
	 * Safety net. Loaded after script.js and deliberately dependency-free, so a
	 * fatal error inside script.js cannot stop it running. It removes the
	 * full-screen preloader and reveals .animate-box content if script.js never
	 * finished — without it, one JS error renders the whole site blank.
	 */
	wp_enqueue_script(
		'arkan-safety',
		$js . 'wp-safety.js',
		array(),
		ARKAN_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'arkan_enqueue_assets' );

/**
 * Themify icon font + fonts folder are referenced relatively from the CSS,
 * so nothing else is needed — but the editor should share the base look.
 */
function arkan_editor_styles() {
	add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'arkan_editor_styles' );

/**
 * Preload the icon font to avoid a flash of unstyled icons.
 */
function arkan_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'arkan_resource_hints', 10, 2 );
