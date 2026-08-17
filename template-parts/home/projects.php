<?php
/**
 * Home "Creative Projects" section — isotope filtered masonry grid.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();

if ( ! arkan_field( 'home_projects_enabled', $page_id, true ) ) {
	return;
}

$count = (int) arkan_field( 'home_projects_count', $page_id, 6 );
$query = arkan_query( 'project', $count );

$subtitle    = arkan_field( 'home_projects_subtitle', $page_id );
$title       = arkan_field( 'home_projects_title', $page_id );
$text        = arkan_field( 'home_projects_text', $page_id );
$show_filter = arkan_field( 'home_projects_filter', $page_id, true );

// Collect the categories actually used by the projects being shown.
$used_terms = array();
if ( $show_filter && $query->have_posts() ) {
	foreach ( $query->posts as $arkan_project ) {
		$terms = get_the_terms( $arkan_project->ID, 'project_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$used_terms[ $term->slug ] = $term->name;
			}
		}
	}
}
?>
<!-- Projects -->
<div class="projects2 section-padding">
	<div class="container">

		<?php
		arkan_section_heading(
			array(
				'subtitle'  => $subtitle,
				'title'     => $title,
				'text'      => $text,
				'row_class' => 'mb-4',
			)
		);
		?>

		<?php if ( ! empty( $used_terms ) ) : ?>
			<div class="row text-center animate-box" data-animate-effect="fadeInUp">
				<ul class="projects2-filter">
					<li class="active" data-filter="*"><?php esc_html_e( 'All', 'arkan' ); ?></li>
					<?php foreach ( $used_terms as $slug => $name ) : ?>
						<li data-filter=".<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( ! $query->have_posts() ) : ?>
			<div class="row">
				<?php arkan_empty_notice( __( 'projects', 'arkan' ), __( 'Projects', 'arkan' ), 'col-md-12' ); ?>
			</div>
		<?php endif; ?>

		<div class="row projects2-items animate-box" data-animate-effect="fadeInUp">
			<?php
			$i = 0;
			while ( $query->have_posts() ) :
				$query->the_post();
				++$i;

				$slugs = array();
				$terms = get_the_terms( get_the_ID(), 'project_category' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$slugs = wp_list_pluck( $terms, 'slug' );
				}
				?>
				<div class="col-md-6 single-item <?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
					<div class="projects2-wrap">
						<a href="<?php the_permalink(); ?>">
							<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'arkan-project-wide', 'images/projects/0' . ( ( $i % 6 ) + 1 ) . '.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>">
						</a>
						<div class="projects2-con">
							<p><?php echo esc_html( arkan_project_number( get_the_ID(), $i ) ); ?></p>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<a href="<?php the_permalink(); ?>" class="project2-link" aria-label="<?php the_title_attribute(); ?>"></a>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

	</div>
</div>
