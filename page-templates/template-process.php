<?php
/**
 * Template Name: Process Page
 * Template Post Type: page
 *
 * Alternating image / text rows. Odd steps put the image on the left,
 * even steps flip it using the design's .order1 / .order2 helpers.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id = get_the_ID();

arkan_banner();

$steps = arkan_field( 'process_steps', $page_id, array() );
?>

<section class="process section-padding">
	<div class="container">

		<?php
		arkan_section_heading(
			array(
				'subtitle'  => arkan_field( 'process_subtitle', $page_id ),
				'title'     => arkan_field( 'process_title', $page_id ),
				'row_class' => 'mb-4',
			)
		);
		?>

		<?php
		$index = 0;
		foreach ( (array) $steps as $step ) :
			++$index;
			$flipped = ( 0 === $index % 2 );
			$image   = arkan_image_url( isset( $step['step_image'] ) ? $step['step_image'] : '', 'arkan-slider' );

			$image_col = $flipped
				? 'col-lg-6 col-md-12 order1 animate-box'
				: 'col-lg-6 col-md-12 animate-box';
			$text_col  = $flipped
				? 'col-lg-6 col-md-12 order2 valign animate-box'
				: 'col-lg-6 col-md-12 valign animate-box';

			$image_block = '';
			if ( $image ) {
				$image_block = sprintf(
					'<div class="%1$s" data-animate-effect="%2$s"><div class="img%3$s"><img src="%4$s" alt="%5$s"></div></div>',
					esc_attr( $image_col ),
					$flipped ? 'fadeInRight' : 'fadeInLeft',
					( 0 === $index % 3 ) ? ' left' : '',
					esc_url( $image ),
					esc_attr( isset( $step['step_title'] ) ? $step['step_title'] : '' )
				);
			}

			$text_block = sprintf(
				'<div class="%1$s" data-animate-effect="%2$s"><div class="wrap"><div class="number"><h1>%3$s</h1></div><div class="cont"><h3>%4$s</h3><p>%5$s</p></div></div></div>',
				esc_attr( $text_col ),
				$flipped ? 'fadeInLeft' : 'fadeInRight',
				esc_html( arkan_pad( $index ) ),
				esc_html( isset( $step['step_title'] ) ? $step['step_title'] : '' ),
				wp_kses_post( isset( $step['step_text'] ) ? $step['step_text'] : '' )
			);
			?>
			<div class="row">
				<?php
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- built with escaping above.
				if ( $flipped ) {
					echo $text_block . $image_block;
				} else {
					echo $image_block . $text_block;
				}
				// phpcs:enable
				?>
			</div>
			<?php
		endforeach;
		?>

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
