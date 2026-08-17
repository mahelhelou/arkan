<?php
/**
 * Small helpers shared by templates.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read an ACF field with a graceful fallback when ACF is not active.
 *
 * @param string     $selector Field name.
 * @param int|string $post_id  Post ID, 'option', etc.
 * @param mixed      $default  Returned when ACF is missing or the value is empty.
 * @return mixed
 */
function arkan_field( $selector, $post_id = false, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $selector, $post_id );
	if ( null === $value || '' === $value || false === $value || array() === $value ) {
		return $default;
	}
	return $value;
}

/**
 * Read a theme-option ACF field (options page) with fallback.
 *
 * @param string $selector Field name.
 * @param mixed  $default  Fallback.
 * @return mixed
 */
function arkan_option( $selector, $default = '' ) {
	return arkan_field( $selector, 'option', $default );
}

/**
 * Resolve an image field (ID, URL or array) to a URL at a given size.
 *
 * @param mixed  $image Image field value.
 * @param string $size  Registered image size.
 * @return string
 */
function arkan_image_url( $image, $size = 'full' ) {
	if ( empty( $image ) ) {
		return '';
	}
	if ( is_numeric( $image ) ) {
		$url = wp_get_attachment_image_url( (int) $image, $size );
		return $url ? $url : '';
	}
	if ( is_array( $image ) ) {
		if ( ! empty( $image['sizes'][ $size ] ) ) {
			return $image['sizes'][ $size ];
		}
		if ( ! empty( $image['ID'] ) ) {
			$url = wp_get_attachment_image_url( (int) $image['ID'], $size );
			return $url ? $url : '';
		}
		return isset( $image['url'] ) ? $image['url'] : '';
	}
	return esc_url_raw( $image );
}

/**
 * Featured image URL for a post, with a bundled placeholder fallback.
 *
 * @param int    $post_id  Post ID.
 * @param string $size     Image size.
 * @param string $fallback Relative path inside assets/, e.g. 'images/slider/1.jpg'.
 * @return string
 */
function arkan_post_image_url( $post_id = 0, $size = 'large', $fallback = 'images/slider/1.jpg' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, $size );
		if ( $url ) {
			return $url;
		}
	}
	return $fallback ? ARKAN_URI . 'assets/' . ltrim( $fallback, '/' ) : '';
}

/**
 * Zero-padded sequence number, e.g. 01, 02 ... used by services and projects.
 *
 * @param int $number Number.
 * @return string
 */
function arkan_pad( $number ) {
	return str_pad( (int) $number, 2, '0', STR_PAD_LEFT );
}

/**
 * Render a Contact Form 7 shortcode by ID, silently doing nothing if unavailable.
 *
 * @param string $form_id CF7 post ID or full shortcode.
 * @return string
 */
function arkan_contact_form( $form_id ) {
	$form_id = trim( (string) $form_id );
	if ( '' === $form_id || ! shortcode_exists( 'contact-form-7' ) ) {
		return '';
	}
	if ( 0 === strpos( $form_id, '[' ) ) {
		return do_shortcode( $form_id );
	}
	return do_shortcode( sprintf( '[contact-form-7 id="%s"]', esc_attr( $form_id ) ) );
}

/**
 * Empty-state message for a section that has no content yet.
 *
 * Site editors get an actionable hint naming the dashboard screen to use.
 * Visitors get a plain sentence — back-office instructions are not shown to
 * the public. Return false from the 'arkan_show_empty_notice' filter to hide
 * the block entirely.
 *
 * @param string $plural    Lowercase plural of the item, e.g. 'projects'.
 * @param string $dashboard Dashboard menu label, e.g. 'Projects'.
 * @param string $wrapper   Optional Bootstrap column wrapper, e.g. 'col-md-12'.
 */
function arkan_empty_notice( $plural, $dashboard, $wrapper = '' ) {
	if ( ! apply_filters( 'arkan_show_empty_notice', true, $plural, $dashboard ) ) {
		return;
	}

	$can_edit = current_user_can( 'edit_posts' );

	if ( $can_edit ) {
		$message = sprintf(
			/* translators: 1: plural item name e.g. "projects", 2: dashboard menu label e.g. "Projects" */
			__( 'No %1$s found. You can add an item from %2$s in the dashboard.', 'arkan' ),
			$plural,
			$dashboard
		);
	} else {
		$message = sprintf(
			/* translators: %s: plural item name e.g. "projects" */
			__( 'No %s found.', 'arkan' ),
			$plural
		);
	}

	$html = sprintf(
		'<p class="arkan-empty%s">%s</p>',
		$can_edit ? ' arkan-empty--editor' : '',
		esc_html( $message )
	);

	if ( $wrapper ) {
		$html = sprintf( '<div class="%s">%s</div>', esc_attr( $wrapper ), $html );
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/**
 * Excerpt limited to a word count, safe for any post object.
 *
 * @param int $words   Word count.
 * @param int $post_id Post ID.
 * @return string
 */
function arkan_excerpt( $words = 24, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$text    = get_the_excerpt( $post_id );
	return wp_trim_words( $text, $words, '…' );
}

/**
 * Excerpt length + more string tuned for the card layouts.
 */
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);
