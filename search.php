<?php
/**
 * Search results.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

arkan_banner(
	array(
		'image'    => arkan_image_url( arkan_option( 'blog_archive_image' ), 'arkan-slider' ),
		'subtitle' => __( 'Search', 'arkan' ),
		/* translators: %s: search query */
		'title'    => sprintf( esc_html__( 'Results for “%s”', 'arkan' ), esc_html( get_search_query() ) ),
	)
);
?>

<section class="blog section-padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-12 animate-box" data-animate-effect="fadeInUp">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', 'excerpt' );
					endwhile;

					arkan_pagination();
				else :
					get_template_part( 'template-parts/content/content', 'none' );
				endif;
				?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php
get_footer();
