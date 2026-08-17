<?php
/**
 * Hardcoded navigation.
 *
 * The navbar reproduces frontend/index.html exactly — same dropdowns, same
 * classes, same icons. Only the href values are dynamic: each one resolves to a
 * real WordPress destination instead of a .html file.
 *
 * Demo-only entries (Home Layout 02-11, Projects 02/03, Blog 02, …) have no
 * converted counterpart, so they resolve to the closest real equivalent.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slugs the hardcoded menu looks for. Filterable so the pages can be renamed
 * without touching the template.
 *
 * @return array
 */
function arkan_page_slugs() {
	return apply_filters(
		'arkan_page_slugs',
		array(
			'about'    => 'about',
			'services' => 'services',
			'contact'  => 'contact',
			'gallery'  => 'gallery',
			'faq'      => 'faq',
			'process'  => 'process',
		)
	);
}

/**
 * Resolve a page ID from one of the slugs above.
 *
 * @param string $key Key from arkan_page_slugs().
 * @return int Page ID, or 0 when the page does not exist.
 */
function arkan_page_id( $key ) {
	static $cache = array();

	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$slugs = arkan_page_slugs();
	$slug  = isset( $slugs[ $key ] ) ? $slugs[ $key ] : $key;
	$page  = get_page_by_path( $slug );

	$cache[ $key ] = $page ? (int) $page->ID : 0;

	return $cache[ $key ];
}

/**
 * Newest published post of a type — used by the "single" demo menu entries.
 *
 * @param string $post_type Post type.
 * @return int Post ID, or 0.
 */
function arkan_latest_id( $post_type ) {
	static $cache = array();

	if ( isset( $cache[ $post_type ] ) ) {
		return $cache[ $post_type ];
	}

	$ids = get_posts(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);

	$cache[ $post_type ] = $ids ? (int) $ids[0] : 0;

	return $cache[ $post_type ];
}

/**
 * URL for a named menu target.
 *
 * @param string $key Target key.
 * @return string
 */
function arkan_nav_url( $key ) {
	switch ( $key ) {

		case 'home':
			return home_url( '/' );

		case 'projects':
			$url = get_post_type_archive_link( 'project' );
			return $url ? $url : home_url( '/projects/' );

		case 'services':
			// Prefer a real "Services" page if one exists, else the CPT archive.
			$page = arkan_page_id( 'services' );
			if ( $page ) {
				return get_permalink( $page );
			}
			$url = get_post_type_archive_link( 'service' );
			return $url ? $url : home_url( '/services/' );

		case 'blog':
			$posts_page = (int) get_option( 'page_for_posts' );
			if ( $posts_page ) {
				return get_permalink( $posts_page );
			}
			// "Latest posts" front page: the blog is the site root.
			return home_url( '/' );

		case 'project_single':
			$id = arkan_latest_id( 'project' );
			return $id ? get_permalink( $id ) : arkan_nav_url( 'projects' );

		case 'service_single':
			$id = arkan_latest_id( 'service' );
			return $id ? get_permalink( $id ) : arkan_nav_url( 'services' );

		case 'post_single':
			$id = arkan_latest_id( 'post' );
			return $id ? get_permalink( $id ) : arkan_nav_url( 'blog' );

		case '404':
			// A URL guaranteed not to resolve, so the 404 template is shown.
			return home_url( '/404-not-found/' );

		default:
			$id = arkan_page_id( $key );
			if ( $id ) {
				return get_permalink( $id );
			}
			$slugs = arkan_page_slugs();
			$slug  = isset( $slugs[ $key ] ) ? $slugs[ $key ] : $key;
			return home_url( '/' . $slug . '/' );
	}
}

/**
 * Is the given menu target the page currently being viewed?
 *
 * @param string $key Target key.
 * @return bool
 */
function arkan_nav_is_active( $key ) {
	switch ( $key ) {

		case 'home':
			return is_front_page();

		case 'projects':
			return is_post_type_archive( 'project' )
				|| is_singular( 'project' )
				|| is_tax( 'project_category' )
				|| is_tax( 'project_tag' );

		case 'services':
			$page = arkan_page_id( 'services' );
			return is_post_type_archive( 'service' )
				|| is_singular( 'service' )
				|| ( $page && is_page( $page ) );

		case 'blog':
			return is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date();

		case 'pages':
			// The "Pages" dropdown is active for any of its children.
			foreach ( array( 'gallery', 'faq', 'process' ) as $child ) {
				$id = arkan_page_id( $child );
				if ( $id && is_page( $id ) ) {
					return true;
				}
			}
			return is_404();

		default:
			$id = arkan_page_id( $key );
			return $id ? is_page( $id ) : false;
	}
}

/**
 * Print class="…" for a top-level nav link.
 *
 * @param string $key         Target key.
 * @param bool   $is_dropdown Whether the item opens a dropdown.
 */
function arkan_nav_link_class( $key, $is_dropdown = false ) {
	$classes = array( 'nav-link' );

	if ( arkan_nav_is_active( $key ) ) {
		$classes[] = 'active';
	}
	if ( $is_dropdown ) {
		$classes[] = 'dropdown-toggle';
	}

	echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}

/**
 * Print class="…" for a dropdown item.
 *
 * @param bool $active Whether it is the current page.
 */
function arkan_nav_item_class( $active = false ) {
	echo 'class="' . esc_attr( $active ? 'dropdown-item active' : 'dropdown-item' ) . '"';
}
