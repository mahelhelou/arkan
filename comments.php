<?php
/**
 * Comments + reply form, styled like post.html.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}

if ( ! comments_open() && ! have_comments() ) {
	return;
}

$arkan_commenter = wp_get_current_commenter();
$arkan_req       = get_option( 'require_name_email' );
$arkan_aria      = $arkan_req ? " aria-required='true' required" : '';
?>
<div class="comments" id="comments">
	<div class="container">
		<div class="row">

			<!-- Comment list -->
			<div class="col-lg-7 col-md-12">
				<?php if ( have_comments() ) : ?>
					<h6 class="mb-4">
						<?php
						printf(
							/* translators: %s: comment count */
							esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'arkan' ) ),
							esc_html( number_format_i18n( get_comments_number() ) )
						);
						?>
					</h6>

					<ol class="comment-list list-unstyled">
						<?php
						wp_list_comments(
							array(
								'style'       => 'ol',
								'short_ping'  => true,
								'avatar_size' => 80,
								'callback'    => 'arkan_comment_callback',
							)
						);
						?>
					</ol>

					<?php
					the_comments_pagination(
						array(
							'prev_text' => '<i class="ti-angle-left"></i>',
							'next_text' => '<i class="ti-angle-right"></i>',
							'class'     => 'pagination-wrap',
						)
					);
					?>

					<?php if ( ! comments_open() ) : ?>
						<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'arkan' ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<!-- Reply form -->
			<div class="col-lg-4 offset-lg-1 col-md-12 archsan-contact-col">
				<?php
				comment_form(
					array(
						'title_reply'          => '<h6 class="mb-4">' . esc_html__( 'Leave a Reply', 'arkan' ) . '</h6>',
						'title_reply_before'   => '',
						'title_reply_after'    => '',
						'title_reply_to'       => '<h6 class="mb-4">' . esc_html__( 'Reply to %s', 'arkan' ) . '</h6>',
						'class_form'           => 'archsan-contact-form row',
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'label_submit'         => esc_attr__( 'Send Comment', 'arkan' ),
						'class_submit'         => 'archsan-submit',
						'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">',
						'submit_field'         => '<div class="col-md-12 mt-3 form-submit">%1$s %2$s</div>',
						'comment_field'        => '<div class="col-md-12"><div class="form-component"><textarea name="comment" id="comment" cols="40" rows="4" class="archsan-input" placeholder="' . esc_attr__( 'Comment *', 'arkan' ) . '" aria-required="true" required></textarea></div></div>',
						'fields'               => array(
							'author' => '<div class="col-md-12"><div class="form-component"><input type="text" name="author" id="author" class="archsan-input" placeholder="' . esc_attr__( 'Name *', 'arkan' ) . '" value="' . esc_attr( $arkan_commenter['comment_author'] ) . '"' . $arkan_aria . '></div></div>',
							'email'  => '<div class="col-md-12"><div class="form-component"><input type="email" name="email" id="email" class="archsan-input" placeholder="' . esc_attr__( 'Email *', 'arkan' ) . '" value="' . esc_attr( $arkan_commenter['comment_author_email'] ) . '"' . $arkan_aria . '></div></div>',
							'url'    => '<div class="col-md-12"><div class="form-component"><input type="url" name="url" id="url" class="archsan-input" placeholder="' . esc_attr__( 'Website', 'arkan' ) . '" value="' . esc_attr( $arkan_commenter['comment_author_url'] ) . '"></div></div>',
							'cookies' => '<div class="col-md-12 comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"> <label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name and email in this browser for the next time I comment.', 'arkan' ) . '</label></div>',
						),
					)
				);
				?>
			</div>

		</div>
	</div>
</div>
