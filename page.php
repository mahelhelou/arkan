<?php
/**
 * Default page template.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

arkan_banner();
?>

<section class="post section-padding">
	<div class="container">
		<div class="row">
			<div class="col-md-12 animate-box entry-content" data-animate-effect="fadeInUp">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'arkan' ),
							'after'  => '</div>',
						)
					);
				endwhile;
				?>
			</div>
		</div>
	</div>
</section>

<?php
if ( comments_open() || get_comments_number() ) {
	comments_template();
}

get_footer();
