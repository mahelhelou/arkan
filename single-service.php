<?php
/**
 * Single service — the WordPress equivalent of services-page.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$post_id = get_the_ID();
	$gallery = arkan_field( 'service_gallery', $post_id, array() );

	arkan_banner(
		array(
			'subtitle'      => arkan_field( 'banner_subtitle', $post_id, __( 'Services', 'arkan' ) ),
			'subtitle_link' => get_post_type_archive_link( 'service' ),
		)
	);
	?>

	<section class="about section-padding">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php if ( ! empty( $gallery ) ) : ?>
						<br>
						<div class="row archsan-popup-gallery">
							<?php
							// Mirrors the template rhythm: two halves, one full, two halves.
							$pattern = array( 'col-md-6', 'col-md-6', 'col-md-12', 'col-md-6', 'col-md-6' );
							$i       = 0;
							foreach ( $gallery as $image ) {
								arkan_gallery_item( $image, $pattern[ $i % count( $pattern ) ], get_the_title() );
								++$i;
							}
							?>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</section>

	<?php
	/* --------------------------------------------------- Other services */
	$others = arkan_query( 'service', -1, array( 'post__not_in' => array( $post_id ) ) );

	if ( $others->have_posts() ) :
		?>
		<section class="services section-padding">
			<div class="container">
				<?php
				arkan_section_heading(
					array(
						'subtitle'  => __( 'Moreover', 'arkan' ),
						'title'     => '<span>' . esc_html__( 'Other', 'arkan' ) . '</span> ' . esc_html__( 'Services', 'arkan' ),
						'row_class' => 'mb-4',
					)
				);
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="owl-carousel owl-theme">
							<?php
							$i = 0;
							while ( $others->have_posts() ) :
								$others->the_post();
								++$i;
								arkan_service_card( $i );
							endwhile;
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	endif;
	wp_reset_postdata();

endwhile;

get_footer();
