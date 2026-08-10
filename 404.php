<?php
/**
 * 404 — the WordPress equivalent of 404.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$image = arkan_image_url( arkan_option( 'error_image' ), 'arkan-slider' );
$image = $image ? $image : ARKAN_URI . 'assets/images/slider/4.jpg';
$title = arkan_option( 'error_title', __( "Sorry We Can't Find That Page!", 'arkan' ) );
$text  = arkan_option( 'error_text', __( 'The page you are looking for was moved, removed, renamed or never existed.', 'arkan' ) );
?>

<div class="banner-header notfound valign bg-img bg-fixed" data-overlay-dark="5" data-background="<?php echo esc_url( $image ); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8 col-md-12 text-center">
				<div class="number">404</div>
				<div class="title"><?php echo esc_html( $title ); ?></div>
				<p><?php echo esc_html( $text ); ?></p>
				<div class="error-form">
					<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="form-group clearfix">
							<input type="search" name="s" value="" placeholder="<?php esc_attr_e( 'Search here', 'arkan' ); ?>" required class="mb-3">
							<input name="submit" type="submit" value="<?php esc_attr_e( 'Search', 'arkan' ); ?>">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
