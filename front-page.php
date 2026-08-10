<?php
/**
 * Front page — the WordPress equivalent of index.html.
 *
 * Used automatically when Settings → Reading is set to "A static page".
 * The same sections are available on any page through the
 * "Home Page" template (page-templates/template-home.php).
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

// If the front page is set to "Your latest posts", fall back to the blog index.
if ( is_home() ) {
	get_template_part( 'index' );
	return;
}

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/projects' );
get_template_part( 'template-parts/home/testimonials' );
get_template_part( 'template-parts/home/blog' );

// Anything typed into the page editor is rendered after the fixed sections.
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$arkan_content = get_the_content();
		if ( '' !== trim( wp_strip_all_tags( $arkan_content ) ) ) {
			echo '<section class="section-padding"><div class="container"><div class="row"><div class="col-md-12 entry-content">';
			the_content();
			echo '</div></div></div></section>';
		}
	}
}

get_footer();
