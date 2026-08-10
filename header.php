<?php
/**
 * Document head, opening wrappers and the navbar.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<noscript>
		<style>
			/* Without JS the preloader overlay would never be removed. */
			#preloader,
			.preloader-bg { display: none !important; }
			.animate-box { opacity: 1 !important; }
		</style>
	</noscript>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="content-wrapper">

	<?php if ( arkan_option( 'preloader_enabled', true ) ) : ?>
		<!-- Preloader -->
		<div class="preloader-bg"></div>
		<div id="preloader">
			<div id="preloader-status">
				<div class="preloader-position loader"><span></span></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( arkan_option( 'scroll_top_enabled', true ) ) : ?>
		<!-- Progress scroll to top -->
		<div class="progress-wrap cursor-pointer">
			<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
				<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
			</svg>
		</div>
	<?php endif; ?>

	<?php if ( arkan_option( 'content_lines_enabled', true ) ) : ?>
		<!-- Decorative lines -->
		<div class="content-lines-wrapper">
			<div class="content-lines-inner">
				<div class="content-lines"></div>
			</div>
		</div>
	<?php endif; ?>

	<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'arkan' ); ?></a>

	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">

			<div class="logo-wrapper">
				<?php if ( has_custom_logo() ) : ?>
					<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$arkan_logo_id  = (int) get_theme_mod( 'custom_logo' );
						$arkan_logo_url = $arkan_logo_id ? wp_get_attachment_image_url( $arkan_logo_id, 'full' ) : '';
						?>
						<img src="<?php echo esc_url( $arkan_logo_url ); ?>" class="logo-img" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				<?php else : ?>
					<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<h2><?php bloginfo( 'name' ); ?> <span><?php bloginfo( 'description' ); ?></span></h2>
					</a>
				<?php endif; ?>
			</div>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'arkan' ); ?>">
				<span class="navbar-toggler-icon"><i class="ti-menu"></i></span>
			</button>

			<div class="collapse navbar-collapse" id="navbar">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav ms-auto',
						'depth'          => 3,
						'walker'         => new Arkan_Nav_Walker(),
						'fallback_cb'    => array( 'Arkan_Nav_Walker', 'fallback' ),
					)
				);
				?>
			</div>

		</div>
	</nav>

	<div id="content" class="site-content">
