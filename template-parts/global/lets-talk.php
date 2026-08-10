<?php
/**
 * The "Let's discuss your project" bar shown above the footer.
 *
 * Form markup comes from Contact Form 7; see README for the exact form body.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

if ( ! arkan_option( 'lets_talk_enabled', true ) ) {
	return;
}

$image    = arkan_image_url( arkan_option( 'lets_talk_image' ), 'arkan-slider' );
$image    = $image ? $image : ARKAN_URI . 'assets/images/slider/1.jpg';
$overlay  = (int) arkan_option( 'lets_talk_overlay', 6 );
$subtitle = arkan_option( 'lets_talk_subtitle', __( 'Contact Us', 'arkan' ) );
$title    = arkan_option( 'lets_talk_title', __( "Let's discuss your project", 'arkan' ) );
$text     = arkan_option( 'lets_talk_text', __( 'Fill out the form and our manager will contact you for consultation.', 'arkan' ) );
$form     = arkan_contact_form( arkan_option( 'lets_talk_form_id' ) );
?>
<section class="lets-talk">
	<div class="background bg-img bg-fixed section-padding" data-background="<?php echo esc_url( $image ); ?>" data-overlay-dark="<?php echo esc_attr( $overlay ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-4 mb-30">
					<div class="sub-title border-bot-dark"><?php echo esc_html( $subtitle ); ?></div>
				</div>
				<div class="col-md-8">
					<div class="section-title"><?php echo wp_kses_post( $title ); ?></div>
					<?php if ( $text ) : ?>
						<p><?php echo wp_kses_post( $text ); ?></p>
					<?php endif; ?>
					<?php
					if ( $form ) {
						echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CF7 output.
					} elseif ( current_user_can( 'edit_theme_options' ) ) {
						printf(
							'<p class="arkan-editor-note"><em>%s</em></p>',
							esc_html__( 'Add a Contact Form 7 ID under Theme Options → Let\'s Talk Bar to show the form here. (Only administrators see this message.)', 'arkan' )
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
