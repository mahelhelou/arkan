<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id = get_the_ID();

arkan_banner();

$locations  = arkan_field( 'contact_locations', $page_id, array() );
$form_title = arkan_field( 'contact_form_title', $page_id );
$form       = arkan_contact_form( arkan_field( 'contact_form_id', $page_id ) );
$map        = arkan_field( 'contact_map', $page_id );
$loc_count  = is_array( $locations ) ? count( $locations ) : 0;
$loc_col    = $loc_count > 1 ? 'col-lg-4 col-md-6' : 'col-lg-6 col-md-6';
?>

<div class="contact section-padding">
	<div class="container">
		<div class="row">

			<?php foreach ( (array) $locations as $location ) : ?>
				<div class="<?php echo esc_attr( $loc_col ); ?>">
					<?php if ( ! empty( $location['location_city'] ) ) : ?>
						<h4 class="mb-4">
							<?php echo esc_html( $location['location_city'] ); ?>
							<?php if ( ! empty( $location['location_label'] ) ) : ?>
								<span><?php echo esc_html( $location['location_label'] ); ?></span>
							<?php endif; ?>
						</h4>
					<?php endif; ?>

					<?php if ( ! empty( $location['location_address'] ) ) : ?>
						<p><?php echo wp_kses_post( $location['location_address'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $location['location_phone'] ) ) : ?>
						<div class="phone"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $location['location_phone'] ) ); ?>"><?php echo esc_html( $location['location_phone'] ); ?></a></div>
					<?php endif; ?>

					<?php if ( ! empty( $location['location_email'] ) ) : ?>
						<div class="mail mb-3"><a href="mailto:<?php echo esc_attr( $location['location_email'] ); ?>"><?php echo esc_html( $location['location_email'] ); ?></a></div>
					<?php endif; ?>

					<?php
					if ( ! empty( $location['location_socials'] ) ) {
						arkan_social_links( $location['location_socials'], 'location_social_icon', 'location_social_url', 'div', 'social mt-2' );
					}
					?>
				</div>
			<?php endforeach; ?>

			<div class="col-lg-4 col-md-12">
				<?php if ( $form_title ) : ?>
					<h4 class="mb-4"><?php echo wp_kses_post( $form_title ); ?></h4>
				<?php endif; ?>

				<?php
				if ( $form ) {
					echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CF7 output.
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					printf(
						'<p class="arkan-editor-note"><em>%s</em></p>',
						esc_html__( 'Set the Contact Form 7 ID in the "Contact Page" panel below the editor. (Only administrators see this message.)', 'arkan' )
					);
				}
				?>
			</div>

		</div>

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
</div>

<?php if ( $map ) : ?>
	<div class="google-maps">
		<?php
		if ( false !== strpos( $map, '<iframe' ) ) {
			echo wp_kses(
				$map,
				array(
					'iframe' => array(
						'src'             => true,
						'width'           => true,
						'height'          => true,
						'style'           => true,
						'allowfullscreen' => true,
						'loading'         => true,
						'referrerpolicy'  => true,
						'title'           => true,
						'id'              => true,
						'frameborder'     => true,
					),
				)
			);
		} else {
			printf(
				'<iframe id="gmap_canvas" src="%s" width="600" height="450" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="%s"></iframe>',
				esc_url( $map ),
				esc_attr__( 'Map', 'arkan' )
			);
		}
		?>
	</div>
<?php endif; ?>

<?php
get_footer();
