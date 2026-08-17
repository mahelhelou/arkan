<?php
/**
 * Projects archive — the WordPress equivalent of projects.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

// On a taxonomy term page the term name is the more useful heading.
$banner_title = is_tax()
	? single_term_title( '', false )
	: arkan_option( 'projects_archive_title', post_type_archive_title( '', false ) );

arkan_banner(
	array(
		'image'         => arkan_image_url( arkan_option( 'projects_archive_image' ), 'arkan-slider' ),
		'subtitle'      => arkan_option( 'projects_archive_subtitle', __( 'Projects', 'arkan' ) ),
		'subtitle_link' => get_post_type_archive_link( 'project' ),
		'title'         => $banner_title,
	)
);

$paged  = max( 1, get_query_var( 'paged' ) );
$offset = ( $paged - 1 ) * (int) arkan_option( 'projects_per_page', 9 );
?>

<section class="projects section-padding">
	<div class="container">

		<?php
		$terms = get_terms(
			array(
				'taxonomy'   => 'project_category',
				'hide_empty' => true,
			)
		);
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :
			?>
			<div class="row text-center mb-5 animate-box" data-animate-effect="fadeInUp">
				<ul class="projects2-filter">
					<li class="<?php echo is_post_type_archive( 'project' ) ? 'active' : ''; ?>">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'All', 'arkan' ); ?></a>
					</li>
					<?php foreach ( $terms as $term ) : ?>
						<li class="<?php echo is_tax( 'project_category', $term->term_id ) ? 'active' : ''; ?>">
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="row">
			<?php
			if ( have_posts() ) :
				$i = $offset;
				while ( have_posts() ) :
					the_post();
					++$i;
					?>
					<div class="col-lg-4 col-md-6 animate-box" data-animate-effect="fadeInUp">
						<?php arkan_project_card( $i ); ?>
					</div>
					<?php
				endwhile;
			else :
				arkan_empty_notice( __( 'projects', 'arkan' ), __( 'Projects', 'arkan' ), 'col-md-12' );
			endif;
			?>
		</div>

		<?php arkan_pagination(); ?>

	</div>
</section>

<?php
get_footer();
