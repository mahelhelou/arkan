<?php
/**
 * Single project — the WordPress equivalent of project-page.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$post_id  = get_the_ID();
	$intro    = arkan_field( 'project_intro', $post_id );
	$gallery  = arkan_field( 'project_gallery', $post_id, array() );
	$features = arkan_field( 'project_features', $post_id, array() );

	$bar = array(
		__( 'Year', 'arkan' )     => arkan_field( 'project_year', $post_id ),
		__( 'Company', 'arkan' )  => arkan_field( 'project_company', $post_id ),
		__( 'Name', 'arkan' )     => arkan_field( 'project_name', $post_id ),
		__( 'Location', 'arkan' ) => arkan_field( 'project_location', $post_id ),
	);
	$bar = array_filter( $bar );

	arkan_banner(
		array(
			'subtitle'      => arkan_field( 'banner_subtitle', $post_id, __( 'Projects', 'arkan' ) ),
			'subtitle_link' => get_post_type_archive_link( 'project' ),
		)
	);
	?>

	<section class="project-page section-padding">
		<div class="container">

			<?php if ( $intro ) : ?>
				<div class="row">
					<div class="col-md-12"><p><?php echo wp_kses_post( $intro ); ?></p><br></div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $gallery ) || ! empty( $bar ) ) : ?>
				<div class="row justify-content-center">
					<div class="col-md-12">

						<?php if ( ! empty( $gallery ) ) : ?>
							<div class="owl-carousel owl-theme">
								<?php foreach ( $gallery as $image ) : ?>
									<?php $url = arkan_image_url( $image, 'arkan-slider' ); ?>
									<?php if ( $url ) : ?>
										<div class="portfolio-item">
											<img class="img-fluid" src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( is_array( $image ) && ! empty( $image['alt'] ) ? $image['alt'] : get_the_title() ); ?>">
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $bar ) ) : ?>
							<div class="row">
								<div class="col-md-8">
									<div class="project-bar">
										<div class="row justify-content-between align-items-center text-left text-lg-start">
											<?php foreach ( $bar as $label => $value ) : ?>
												<div class="col-md-3 mb-15">
													<h5><?php echo esc_html( $label ); ?></h5>
													<h6><?php echo esc_html( $value ); ?></h6>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>

					</div>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12 entry-content">
					<?php the_content(); ?>

					<?php if ( ! empty( $features ) ) : ?>
						<br>
						<ul class="list-unstyled page-list mb-30">
							<?php foreach ( $features as $feature ) : ?>
								<li>
									<div class="page-list-icon"><span class="ti-check"></span></div>
									<div class="page-list-text">
										<p><?php echo esc_html( isset( $feature['feature_text'] ) ? $feature['feature_text'] : '' ); ?></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</section>

	<?php
	/* --------------------------------------------------- Other projects */
	$others = arkan_query(
		'project',
		6,
		array( 'post__not_in' => array( $post_id ) )
	);

	if ( $others->have_posts() ) :
		?>
		<section class="projects section-padding">
			<div class="container">
				<?php
				arkan_section_heading(
					array(
						'subtitle' => __( 'Discover', 'arkan' ),
						'title'    => '<span>' . esc_html__( 'Other', 'arkan' ) . '</span> ' . esc_html__( 'Projects', 'arkan' ),
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
								arkan_project_card( $i );
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
