<?php
/**
 * Custom ACF location rule: "Page Slug".
 *
 * The theme resolves About/Contact/Gallery/FAQ/Process by slug through the
 * WordPress template hierarchy (page-about.php, page-contact.php, …), which
 * means no page template is assigned — and ACF's built-in "Page Template" rule
 * would never match, leaving the editor with no fields.
 *
 * This rule matches on the page slug instead, so the field groups appear
 * whichever route is used.
 *
 * Uses ACF's filter-based location API, which is still supported in ACF 6.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the rule type under a "Arkan" group in the location dropdown.
 *
 * @param array $choices Existing rule types.
 * @return array
 */
function arkan_acf_location_rule_type( $choices ) {
	$choices['Arkan']['arkan_page_slug'] = __( 'Page Slug', 'arkan' );
	return $choices;
}
add_filter( 'acf/location/rule_types', 'arkan_acf_location_rule_type' );

/**
 * Values offered for the rule — the slugs the theme knows about.
 *
 * @param array $choices Existing values.
 * @return array
 */
function arkan_acf_location_rule_values( $choices ) {
	foreach ( arkan_page_slugs() as $slug ) {
		$choices[ $slug ] = $slug;
	}
	return $choices;
}
add_filter( 'acf/location/rule_values/arkan_page_slug', 'arkan_acf_location_rule_values' );

/**
 * Decide whether the current edit screen matches the rule.
 *
 * @param bool  $match   Current match state.
 * @param array $rule    Rule being evaluated.
 * @param array $options Screen options supplied by ACF.
 * @return bool
 */
function arkan_acf_location_rule_match( $match, $rule, $options ) {
	$post_id = 0;

	if ( ! empty( $options['post_id'] ) ) {
		$post_id = (int) $options['post_id'];
	}

	if ( ! $post_id ) {
		return false;
	}

	$post = get_post( $post_id );

	if ( ! $post || 'page' !== $post->post_type ) {
		return false;
	}

	$slug = $post->post_name;

	// A brand-new page has no slug yet; fall back to the sanitized title so the
	// fields still show while the page is being created.
	if ( '' === $slug && ! empty( $post->post_title ) ) {
		$slug = sanitize_title( $post->post_title );
	}

	if ( '==' === $rule['operator'] ) {
		return $slug === $rule['value'];
	}

	if ( '!=' === $rule['operator'] ) {
		return $slug !== $rule['value'];
	}

	return $match;
}
add_filter( 'acf/location/rule_match/arkan_page_slug', 'arkan_acf_location_rule_match', 10, 3 );
