<?php
/**
 * Template Name: Services Page
 * Template Post Type: page
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id = get_the_ID();

arkan_banner();

$intro = arkan_field( 'services_intro', $page_id );
$count = (int) arkan_field( 'services_count', $page_id, -1 );
$query = arkan_query( 'service', $count );
?>

<section class="services section-padding">
	<div class="container">

		<?php if ( $intro ) : ?>
			<div class="row mb-5 animate-box" data-animate-effect="fadeInUp">
				<div class="col-md-12"><p><?php echo wp_kses_post( $intro ); ?></p></div>
			</div>
		<?php endif; ?>

		<div class="row">
			<?php
			if ( $query->have_posts() ) :
				$i = 0;
				while ( $query->have_posts() ) :
					$query->the_post();
					++$i;
					?>
					<div class="col-lg-4 col-md-6 mb-5 animate-box" data-animate-effect="fadeInUp">
						<?php arkan_service_card( $i ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div class="col-md-12"><p><?php esc_html_e( 'No services have been added yet.', 'arkan' ); ?></p></div>
			<?php endif; ?>
		</div>

		<?php
		while ( have_posts() ) :
			the_post();
			if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
				?>
				<div class="row"><div class="col-md-12 entry-content"><?php the_content(); ?></div></div>
				<?php
			endif;
		endwhile;
		?>

	</div>
</section>

<?php
get_footer();
