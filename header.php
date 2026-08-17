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

	<?php get_template_part( 'template-parts/global/navbar' ); ?>

	<div id="content" class="site-content">
