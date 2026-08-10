<?php
/**
 * Home "About" section.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();

if ( ! arkan_field( 'home_about_enabled', $page_id, true ) ) {
	return;
}

$subtitle = arkan_field( 'home_about_subtitle', $page_id );
$title    = arkan_field( 'home_about_title', $page_id );
$text     = arkan_field( 'home_about_text', $page_id );
$boxes    = arkan_field( 'home_about_boxes', $page_id, array() );

if ( ! $subtitle && ! $title && ! $text && ! $boxes ) {
	return;
}
?>
<!-- About -->
<section class="about section-padding">
	<div class="container">
		<div class="row">
			<div class="col-md-4 mb-30 animate-box" data-animate-effect="fadeInUp">
				<?php if ( $subtitle ) : ?>
					<div class="sub-title border-bot-light"><?php echo esc_html( $subtitle ); ?></div>
				<?php endif; ?>
			</div>
			<div class="col-md-8 animate-box" data-animate-effect="fadeInUp">
				<?php if ( $title ) : ?>
					<div class="section-title"><?php echo wp_kses_post( $title ); ?></div>
				<?php endif; ?>

				<?php echo wp_kses_post( $text ); ?>

				<?php if ( ! empty( $boxes ) ) : ?>
					<br>
					<div class="row">
						<?php foreach ( $boxes as $box ) : ?>
							<div class="col col-md-4">
								<div class="about-box">
									<?php
									$icon = arkan_image_url( isset( $box['box_icon'] ) ? $box['box_icon'] : '', 'thumbnail' );
									if ( $icon ) :
										?>
										<img src="<?php echo esc_url( $icon ); ?>" class="icon" alt="<?php echo esc_attr( isset( $box['box_title'] ) ? $box['box_title'] : '' ); ?>">
									<?php endif; ?>
									<?php if ( ! empty( $box['box_title'] ) ) : ?>
										<h5><?php echo esc_html( $box['box_title'] ); ?></h5>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
