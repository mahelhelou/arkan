<?php
/**
 * Home testimonials carousel.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();

if ( ! arkan_field( 'home_testimonials_enabled', $page_id, true ) ) {
	return;
}

$query = arkan_query( 'testimonial', -1 );

$image    = arkan_image_url( arkan_field( 'home_testimonials_image', $page_id ), 'arkan-slider' );
$image    = $image ? $image : ARKAN_URI . 'assets/images/slider/4.jpg';
$overlay  = (int) arkan_field( 'home_testimonials_overlay', $page_id, 6 );
$subtitle = arkan_field( 'home_testimonials_subtitle', $page_id );
$title    = arkan_field( 'home_testimonials_title', $page_id );
?>
<!-- Testimonials -->
<section class="testimonials">
	<div class="background bg-img bg-fixed section-padding" data-background="<?php echo esc_url( $image ); ?>" data-overlay-dark="<?php echo esc_attr( $overlay ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-4 mb-30">
					<?php if ( $subtitle ) : ?>
						<h3 class="sub-title border-bot-dark"><?php echo esc_html( $subtitle ); ?></h3>
					<?php endif; ?>
				</div>
				<div class="col-md-8">
					<?php if ( $title ) : ?>
						<div class="section-title"><?php echo wp_kses_post( $title ); ?></div>
					<?php endif; ?>
					<?php if ( ! $query->have_posts() ) : ?>
						<?php arkan_empty_notice( __( 'testimonials', 'arkan' ), __( 'Testimonials', 'arkan' ) ); ?>
					<?php endif; ?>

					<div class="wrap">
						<div class="owl-carousel owl-theme">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								$role = arkan_field( 'testimonial_role', get_the_ID() );
								?>
								<div class="item">
									<span class="quote"><img src="<?php echo esc_url( ARKAN_URI . 'assets/images/quot.png' ); ?>" alt=""></span>
									<?php the_content(); ?>
									<div class="info">
										<div class="author-img">
											<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'thumbnail', 'images/team/1.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>">
										</div>
										<div class="cont">
											<h6><?php the_title(); ?></h6>
											<?php if ( $role ) : ?>
												<span><?php echo esc_html( $role ); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
