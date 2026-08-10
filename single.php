<?php
/**
 * Single post — the WordPress equivalent of post.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$cats     = get_the_category();
	$subtitle = arkan_field( 'banner_subtitle', get_the_ID(), ! empty( $cats ) ? $cats[0]->name : __( 'Blog', 'arkan' ) );
	$sub_link = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : get_permalink( get_option( 'page_for_posts' ) );

	ob_start();
	?>
	<div class="wrap">
		<div class="author">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 60, '', '', array( 'class' => 'avatar' ) ); ?>
			<?php the_author(); ?>
		</div>
		<div class="date-comment">
			<i class="ti-calendar"></i> <?php echo esc_html( get_the_date() ); ?>
		</div>
	</div>
	<?php
	$banner_meta = ob_get_clean();

	arkan_banner(
		array(
			'subtitle'      => $subtitle,
			'subtitle_link' => $sub_link,
			'meta'          => $banner_meta,
		)
	);

	$show_sidebar = (bool) get_theme_mod( 'arkan_single_sidebar', false );
	$main_col     = $show_sidebar ? 'col-lg-8 col-md-12' : 'col-md-12';
	?>

	<section class="post section-padding">
		<div class="container">
			<div class="row">
				<div class="<?php echo esc_attr( $main_col ); ?> animate-box" data-animate-effect="fadeInUp">

					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'arkan-slider', array( 'class' => 'img-responsive mb-5' ) ); ?>
					<?php endif; ?>

					<?php arkan_post_meta(); ?>

					<h2><?php the_title(); ?></h2>

					<div class="entry-content">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'arkan' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>

					<?php
					$tags = get_the_tags();
					if ( $tags ) :
						?>
						<div class="widget tags mt-4">
							<h6 class="title-widget"><?php esc_html_e( 'Tags', 'arkan' ); ?></h6>
							<div class="bt">
								<?php foreach ( $tags as $tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<?php
				if ( $show_sidebar ) {
					get_sidebar();
				}
				?>
			</div>
		</div>
	</section>

	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	/* ------------------------------------------------------- Prev / Next */
	$prev = get_previous_post();
	$next = get_next_post();
	if ( $prev || $next ) :
		?>
		<div class="prev-next">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="d-sm-flex align-items-center justify-content-between">
							<div class="prev-next-left">
								<?php if ( $prev ) : ?>
									<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><i class="ti-arrow-left"></i> <?php echo esc_html( get_the_title( $prev ) ); ?></a>
								<?php endif; ?>
							</div>

							<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'All posts', 'arkan' ); ?>"><i class="ti-layout-grid3-alt"></i></a>

							<div class="prev-next-right">
								<?php if ( $next ) : ?>
									<a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> <i class="ti-arrow-right"></i></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	endif;

endwhile;

get_footer();
