<?php
/**
 * Template Name: Gallery Page
 * Template Post Type: page
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id = get_the_ID();

arkan_banner();

$images = arkan_field( 'gallery_images', $page_id, array() );
?>

<section class="section-padding">
	<div class="container">

		<?php
		arkan_section_heading(
			array(
				'subtitle' => arkan_field( 'gallery_subtitle', $page_id ),
				'title'    => arkan_field( 'gallery_title', $page_id ),
				'text'     => arkan_field( 'gallery_text', $page_id ),
			)
		);
		?>

		<?php if ( empty( $images ) ) : ?>
			<div class="row">
				<?php arkan_empty_notice( __( 'images', 'arkan' ), __( 'the “Gallery Page” panel on this page', 'arkan' ), 'col-md-12' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $images ) ) : ?>
			<div class="row archsan-popup-gallery">
				<?php
				// Repeat the template's 3-3-2-2 rhythm: three thirds, then two halves.
				$pattern = array( 'col-md-4', 'col-md-4', 'col-md-4', 'col-md-6', 'col-md-6' );
				$i       = 0;
				foreach ( $images as $image ) {
					arkan_gallery_item( $image, $pattern[ $i % count( $pattern ) ], get_the_title( $page_id ) );
					++$i;
				}
				?>
			</div>
		<?php endif; ?>

		<?php
		while ( have_posts() ) :
			the_post();
			if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
				?>
				<div class="row mt-5"><div class="col-md-12 entry-content"><?php the_content(); ?></div></div>
				<?php
			endif;
		endwhile;
		?>

	</div>
</section>

<?php
get_footer();
