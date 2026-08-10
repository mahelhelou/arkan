<?php
/**
 * Blog index — the WordPress equivalent of blog.html.
 *
 * Also acts as the fallback template for anything without a more specific file.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$banner_image = arkan_image_url( arkan_option( 'blog_archive_image' ), 'arkan-slider' );
$blog_page_id = (int) get_option( 'page_for_posts' );

if ( ! $banner_image && $blog_page_id ) {
	$banner_image = arkan_image_url( arkan_field( 'banner_image', $blog_page_id ), 'arkan-slider' );
}

arkan_banner(
	array(
		'image'    => $banner_image,
		'subtitle' => arkan_option( 'blog_archive_subtitle', __( 'Blog', 'arkan' ) ),
		'title'    => $blog_page_id ? get_the_title( $blog_page_id ) : arkan_option( 'blog_archive_title', __( 'Latest News', 'arkan' ) ),
	)
);

$layout      = get_theme_mod( 'arkan_blog_layout', 'sidebar-right' );
$has_sidebar = ( 'full' !== $layout );
$main_col    = $has_sidebar ? 'col-lg-8 col-md-12' : 'col-lg-10 offset-lg-1 col-md-12';
?>

<section class="blog section-padding">
	<div class="container">

		<?php
		arkan_section_heading(
			array(
				'subtitle' => arkan_option( 'blog_archive_subtitle', __( 'Blog', 'arkan' ) ),
				'title'    => arkan_option( 'blog_archive_title', '<span>' . __( 'Latest', 'arkan' ) . '</span> ' . __( 'News', 'arkan' ) ),
			)
		);
		?>

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
