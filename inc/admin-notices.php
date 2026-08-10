<?php
/**
 * Dependency notices.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tell the admin which plugins the theme expects.
 */
function arkan_dependency_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$missing = array();

	if ( ! class_exists( 'ACF' ) ) {
		$missing[] = __( 'Advanced Custom Fields PRO — powers every editable section, the home page, project details and theme options.', 'arkan' );
	} elseif ( ! function_exists( 'acf_add_options_page' ) ) {
		$missing[] = __( 'Advanced Custom Fields PRO — the free edition is active, but the theme needs PRO for the Repeater, Gallery and Options Page features.', 'arkan' );
	}

	if ( ! shortcode_exists( 'contact-form-7' ) ) {
		$missing[] = __( 'Contact Form 7 — renders the contact page form and the "Let\'s Talk" bar.', 'arkan' );
	}

	if ( empty( $missing ) ) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<p><strong><?php esc_html_e( 'Arkan theme: recommended plugins are missing', 'arkan' ); ?></strong></p>
		<ul style="list-style:disc;margin-left:20px;">
			<?php foreach ( $missing as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
		<p><?php esc_html_e( 'The theme still renders without them, falling back to bundled demo content, but nothing will be editable.', 'arkan' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'arkan_dependency_notice' );
