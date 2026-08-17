<?php
/**
 * Home "Latest News" section.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();

if ( ! arkan_field( 'home_blog_enabled', $page_id, true ) ) {
	return;
}

$count = (int) arkan_field( 'home_blog_count', $page_id, 3 );
$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

$subtitle = arkan_field( 'home_blog_subtitle', $page_id );
$title    = arkan_field( 'home_blog_title', $page_id );

// Three or fewer posts sit in a static grid; more than three become a carousel.
$is_carousel = $query->post_count > 3;
?>
<!-- Latest news -->
<section class="blog-home section-padding">
	<div class="container">

		<?php
		arkan_section_heading(
			array(
				'subtitle' => $subtitle,
				'title'    => $title,
			)
		);
		?>

		<div class="row">
			<?php if ( ! $query->have_posts() ) : ?>
				<?php arkan_empty_notice( __( 'posts', 'arkan' ), __( 'Posts', 'arkan' ), 'col-md-12' ); ?>
			<?php elseif ( $is_carousel ) : ?>
				<div class="col-md-12">
					<div class="owl-carousel owl-theme">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							arkan_post_card();
						endwhile;
						?>
					</div>
				</div>
			<?php else : ?>
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<div class="col-md-4">
						<?php arkan_post_card(); ?>
					</div>
					<?php
				endwhile;
				?>
			<?php endif; ?>
		</div>

	</div>
</section>
<?php
wp_reset_postdata();
