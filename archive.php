<?php
/**
 * Category / tag / date / author archives for standard posts.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

arkan_banner(
	array(
		'image'    => arkan_image_url( arkan_option( 'blog_archive_image' ), 'arkan-slider' ),
		'subtitle' => get_the_archive_title(),
		'title'    => arkan_option( 'blog_archive_title', __( 'Latest News', 'arkan' ) ),
	)
);

$layout      = get_theme_mod( 'arkan_blog_layout', 'sidebar-right' );
$has_sidebar = ( 'full' !== $layout );
$main_col    = $has_sidebar ? 'col-lg-8 col-md-12' : 'col-lg-10 offset-lg-1 col-md-12';
?>

<section class="blog section-padding">
	<div class="container">

		<?php if ( get_the_archive_description() ) : ?>
			<div class="row mb-5">
				<div class="col-md-12 entry-content"><?php the_archive_description(); ?></div>
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="<?php echo esc_attr( $main_col ); ?> animate-box" data-animate-effect="fadeInUp">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', 'excerpt' );
					endwhile;

					arkan_pagination();
				else :
					get_template_part( 'template-parts/content/content', 'none' );
				endif;
				?>
			</div>

			<?php
			if ( $has_sidebar ) {
				get_sidebar();
			}
			?>
		</div>

	</div>
</section>

<?php
get_footer();
