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
