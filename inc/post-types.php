<?php
/**
 * Custom post types and taxonomies.
 *
 * project    -> projects.html / project-page.html
 * service    -> services.html / services-page.html
 * team       -> about.html team carousel
 * testimonial-> index.html testimonials carousel
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register post types.
 */
function arkan_register_post_types() {

	/* ---------------------------------------------------------- Projects */
	register_post_type(
		'project',
		array(
			'labels'             => array(
				'name'               => __( 'Projects', 'arkan' ),
				'singular_name'      => __( 'Project', 'arkan' ),
				'add_new'            => __( 'Add New', 'arkan' ),
				'add_new_item'       => __( 'Add New Project', 'arkan' ),
				'edit_item'          => __( 'Edit Project', 'arkan' ),
				'new_item'           => __( 'New Project', 'arkan' ),
				'view_item'          => __( 'View Project', 'arkan' ),
				'search_items'       => __( 'Search Projects', 'arkan' ),
				'not_found'          => __( 'No projects found', 'arkan' ),
				'not_found_in_trash' => __( 'No projects found in Trash', 'arkan' ),
				'all_items'          => __( 'All Projects', 'arkan' ),
				'menu_name'          => __( 'Projects', 'arkan' ),
			),
			'public'             => true,
			'has_archive'        => true,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-building',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ),
			'rewrite'            => array(
				'slug'       => arkan_cpt_slug( 'project', 'projects' ),
				'with_front' => false,
			),
			'show_in_rest'       => true,
			'publicly_queryable' => true,
		)
	);

	/* ---------------------------------------------------------- Services */
	register_post_type(
		'service',
		array(
			'labels'        => array(
				'name'          => __( 'Services', 'arkan' ),
				'singular_name' => __( 'Service', 'arkan' ),
				'add_new_item'  => __( 'Add New Service', 'arkan' ),
				'edit_item'     => __( 'Edit Service', 'arkan' ),
				'all_items'     => __( 'All Services', 'arkan' ),
				'menu_name'     => __( 'Services', 'arkan' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 21,
			'menu_icon'     => 'dashicons-hammer',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ),
			'rewrite'       => array(
				'slug'       => arkan_cpt_slug( 'service', 'services' ),
				'with_front' => false,
			),
			'show_in_rest'  => true,
		)
	);

	/* ------------------------------------------------------- Team member */
	register_post_type(
		'team',
		array(
			'labels'        => array(
				'name'          => __( 'Team Members', 'arkan' ),
				'singular_name' => __( 'Team Member', 'arkan' ),
				'add_new_item'  => __( 'Add New Team Member', 'arkan' ),
				'edit_item'     => __( 'Edit Team Member', 'arkan' ),
				'all_items'     => __( 'All Team Members', 'arkan' ),
				'menu_name'     => __( 'Team', 'arkan' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'has_archive'   => false,
			'menu_position' => 22,
			'menu_icon'     => 'dashicons-groups',
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);

	/* ------------------------------------------------------ Testimonials */
	register_post_type(
		'testimonial',
		array(
			'labels'        => array(
				'name'          => __( 'Testimonials', 'arkan' ),
				'singular_name' => __( 'Testimonial', 'arkan' ),
				'add_new_item'  => __( 'Add New Testimonial', 'arkan' ),
				'edit_item'     => __( 'Edit Testimonial', 'arkan' ),
				'all_items'     => __( 'All Testimonials', 'arkan' ),
				'menu_name'     => __( 'Testimonials', 'arkan' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'has_archive'   => false,
			'menu_position' => 23,
			'menu_icon'     => 'dashicons-format-quote',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'arkan_register_post_types', 5 );

/**
 * Register taxonomies.
 */
function arkan_register_taxonomies() {

	register_taxonomy(
		'project_category',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Project Categories', 'arkan' ),
				'singular_name' => __( 'Project Category', 'arkan' ),
				'search_items'  => __( 'Search Project Categories', 'arkan' ),
				'all_items'     => __( 'All Project Categories', 'arkan' ),
				'edit_item'     => __( 'Edit Project Category', 'arkan' ),
				'add_new_item'  => __( 'Add New Project Category', 'arkan' ),
				'menu_name'     => __( 'Categories', 'arkan' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'project-category',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'project_tag',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => __( 'Project Tags', 'arkan' ),
				'singular_name' => __( 'Project Tag', 'arkan' ),
				'menu_name'     => __( 'Tags', 'arkan' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'project-tag',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'arkan_register_taxonomies', 5 );

/**
 * Allow the archive slug to be changed from theme options without editing code.
 *
 * @param string $option_key Option field name.
 * @param string $default    Default slug.
 * @return string
 */
function arkan_cpt_slug( $option_key, $default ) {
	$stored = get_option( 'arkan_slug_' . $option_key );
	return $stored ? sanitize_title( $stored ) : $default;
}

/**
 * Projects and services archives should show every item, ordered by menu order.
 *
 * @param WP_Query $query Query.
 */
function arkan_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'project' ) || $query->is_tax( 'project_category' ) || $query->is_tax( 'project_tag' ) ) {
		$query->set( 'posts_per_page', (int) arkan_option( 'projects_per_page', 9 ) );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}

	if ( $query->is_post_type_archive( 'service' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
}
add_action( 'pre_get_posts', 'arkan_pre_get_posts' );

/**
 * Flush rewrite rules once after the theme is activated.
 */
function arkan_flush_rewrites() {
	arkan_register_post_types();
	arkan_register_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'arkan_flush_rewrites' );
