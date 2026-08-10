<?php
/**
 * Comment markup matching .comments .wrap in the design.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a single comment.
 *
 * @param WP_Comment $comment Comment.
 * @param array      $args    Args.
 * @param int        $depth   Depth.
 */
function arkan_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item' ); ?>>
		<div class="wrap" id="div-comment-<?php comment_ID(); ?>">
			<div class="user">
				<?php echo get_avatar( $comment, 80 ); ?>
			</div>
			<div class="con">
				<h6><?php echo esc_html( get_comment_author( $comment ) ); ?></h6>
				<p>
					<span>
						<?php
						printf(
							/* translators: %s: human readable time difference */
							esc_html__( '%s ago', 'arkan' ),
							esc_html( human_time_diff( (int) get_comment_time( 'U', true, false, $comment ), time() ) )
						);
						?>
					</span>
				</p>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><em><?php esc_html_e( 'Your comment is awaiting moderation.', 'arkan' ); ?></em></p>
				<?php endif; ?>

				<?php comment_text(); ?>

				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '',
							'after'     => '<i class="ti-back-left"></i>',
							'reply_text' => esc_html__( 'Reply', 'arkan' ),
							'class'     => 'reply',
						)
					)
				);
				?>
			</div>
		</div>
	<?php
	// Closing tag is added by WordPress via end-el.
}

/**
 * Force the reply link to carry the design's class.
 *
 * @param string $link Anchor markup.
 * @return string
 */
function arkan_comment_reply_link_class( $link ) {
	return str_replace( "class='comment-reply-link", "class='reply comment-reply-link", $link );
}
add_filter( 'comment_reply_link', 'arkan_comment_reply_link_class' );
