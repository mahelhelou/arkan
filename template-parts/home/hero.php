<?php
/**
 * Home hero slider (header.slider-fade).
 *
 * Slides come from the "Hero Slider" repeater on the front page. If it is empty
 * the theme falls back to projects flagged "Feature in Home Hero Slider", then
 * to the newest projects, so the slider is never blank.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();
$slides  = arkan_field( 'hero_slides', $page_id, array() );

/* ---------------------------------------------------- Project fallback */
if ( empty( $slides ) ) {
	// First try projects explicitly flagged for the slider…
	$fallback = arkan_query(
		'project',
		3,
		array(
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'project_featured_home',
					'value'   => '1',
					'compare' => '=',
				),
			),
		)
	);

	// …and if none are flagged, just use the newest projects.
	if ( ! $fallback->have_posts() ) {
		wp_reset_postdata();
		$fallback = arkan_query( 'project', 3 );
	}

	if ( $fallback->have_posts() ) {
		$index  = 0;
		$slides = array();
		while ( $fallback->have_posts() ) {
			$fallback->the_post();
			++$index;
			$slides[] = array(
				'slide_image'       => get_post_thumbnail_id(),
				'slide_overlay'     => 4,
				'slide_subtitle'    => arkan_project_number( get_the_ID(), $index ),
				'slide_title'       => get_the_title(),
				'slide_text'        => arkan_excerpt( 28 ),
				'slide_button_text' => __( 'View Project', 'arkan' ),
				'slide_button_link' => array( 'url' => get_permalink() ),
			);
		}
		wp_reset_postdata();
	}
}

if ( empty( $slides ) ) {
	return;
}
?>
<!-- Hero slider -->
<header id="slider-area" class="header slider-fade">
	<div class="owl-carousel owl-theme">
		<?php
		foreach ( $slides as $slide ) :
			$image = arkan_image_url( isset( $slide['slide_image'] ) ? $slide['slide_image'] : '', 'arkan-slider' );
			$image = $image ? $image : ARKAN_URI . 'assets/images/slider/1.jpg';

			$link      = isset( $slide['slide_button_link'] ) ? $slide['slide_button_link'] : '';
			$link_url  = is_array( $link ) && ! empty( $link['url'] ) ? $link['url'] : ( is_string( $link ) ? $link : '' );
			$link_tgt  = is_array( $link ) && ! empty( $link['target'] ) ? $link['target'] : '';
			$link_text = ! empty( $slide['slide_button_text'] ) ? $slide['slide_button_text'] : '';
			if ( is_array( $link ) && ! empty( $link['title'] ) && ! $link_text ) {
				$link_text = $link['title'];
			}
			?>
			<div class="text-left item bg-img" data-overlay-dark="<?php echo esc_attr( isset( $slide['slide_overlay'] ) ? (int) $slide['slide_overlay'] : 4 ); ?>" data-background="<?php echo esc_url( $image ); ?>">
				<div class="v-middle caption">
					<div class="container">
						<div class="row">
							<div class="col-lg-7 col-md-12">
								<?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
									<h4><?php echo esc_html( $slide['slide_subtitle'] ); ?></h4>
								<?php endif; ?>

								<?php if ( ! empty( $slide['slide_title'] ) ) : ?>
									<h1><?php echo wp_kses_post( $slide['slide_title'] ); ?></h1>
								<?php endif; ?>

								<?php if ( ! empty( $slide['slide_text'] ) ) : ?>
									<p><?php echo wp_kses_post( $slide['slide_text'] ); ?></p>
								<?php endif; ?>

								<?php if ( $link_url && $link_text ) : ?>
									<a href="<?php echo esc_url( $link_url ); ?>" class="button-light"<?php echo $link_tgt ? ' target="' . esc_attr( $link_tgt ) . '"' : ''; ?>><?php echo esc_html( $link_text ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="slide-num" id="snh-1"></div>
	<div class="slider__progress"><span></span></div>
</header>
