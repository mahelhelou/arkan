<?php
/**
 * Services archive — the WordPress equivalent of services.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

arkan_banner(
	array(
		'image'    => arkan_image_url( arkan_option( 'services_archive_image' ), 'arkan-slider' ),
		'subtitle' => arkan_option( 'services_archive_subtitle', __( 'What We Do', 'arkan' ) ),
		'title'    => arkan_option( 'services_archive_title', post_type_archive_title( '', false ) ),
	)
);
?>

<section class="services section-padding">
	<div class="container">
		<div class="row">
			<?php
			if ( have_posts() ) :
				$i = 0;
				while ( have_posts() ) :
					the_post();
					++$i;
					?>
					<div class="col-lg-4 col-md-6 mb-5 animate-box" data-animate-effect="fadeInUp">
						<?php arkan_service_card( $i ); ?>
					</div>
					<?php
				endwhile;
			else :
				arkan_empty_notice( __( 'services', 'arkan' ), __( 'Services', 'arkan' ), 'col-md-12' );
			endif;
			?>
		</div>
	</div>
</section>

<?php
get_footer();
