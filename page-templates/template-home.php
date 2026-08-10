<?php
/**
 * Template Name: Home Page
 * Template Post Type: page
 *
 * Same sections as front-page.php, available on any page so a second landing
 * page can be built without changing the Reading settings.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/projects' );
get_template_part( 'template-parts/home/testimonials' );
get_template_part( 'template-parts/home/blog' );

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) {
			echo '<section class="section-padding"><div class="container"><div class="row"><div class="col-md-12 entry-content">';
			the_content();
			echo '</div></div></div></section>';
		}
	}
}

get_footer();
