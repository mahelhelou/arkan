<?php
/**
 * Blog list item (.blog .wrap).
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'wrap' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'arkan-blog', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</a>
	<?php endif; ?>

	<div class="con">
		<?php arkan_post_meta(); ?>

		<div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>

		<p><?php echo esc_html( arkan_excerpt( 42 ) ); ?></p>

		<div class="more">
			<a href="<?php the_permalink(); ?>" class="link-btn">
				<?php esc_html_e( 'Read more', 'arkan' ); ?> <i class="ti-arrow-right"></i>
			</a>
		</div>
	</div>

</article>
