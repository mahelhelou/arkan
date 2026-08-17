<?php
/**
 * ACF options page and local field groups.
 *
 * All groups are registered in code (acf_add_local_field_group) so nothing has to
 * be imported through the admin UI and the fields travel with the theme.
 *
 * Requires Advanced Custom Fields PRO (repeater + gallery fields).
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Field builders
 * ---------------------------------------------------------------------- */

/**
 * Build a field array.
 *
 * @param string $name  Field name.
 * @param string $label Label.
 * @param string $type  Field type.
 * @param array  $extra Extra keys.
 * @return array
 */
function arkan_acf_f( $name, $label, $type = 'text', $extra = array() ) {
	return array_merge(
		array(
			'key'   => 'field_arkan_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Build a tab field.
 *
 * @param string $name  Unique name.
 * @param string $label Label.
 * @return array
 */
function arkan_acf_tab( $name, $label ) {
	return array(
		'key'       => 'field_arkan_tab_' . $name,
		'name'      => '',
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'top',
	);
}

/**
 * Sub-fields for a social links repeater.
 *
 * @param string $prefix Unique prefix.
 * @return array
 */
function arkan_acf_social_subfields( $prefix ) {
	return array(
		arkan_acf_f(
			$prefix . '_icon',
			__( 'Icon', 'arkan' ),
			'select',
			array(
				'choices'       => array(
					'ti-twitter'   => 'Twitter / X',
					'ti-facebook'  => 'Facebook',
					'ti-instagram' => 'Instagram',
					'ti-linkedin'  => 'LinkedIn',
					'ti-pinterest' => 'Pinterest',
					'ti-youtube'   => 'YouTube',
					'ti-dribbble'  => 'Dribbble',
					'ti-behance'   => 'Behance',
					'ti-vimeo'     => 'Vimeo',
					'ti-world'     => 'Website',
				),
				'default_value' => 'ti-instagram',
				'wrapper'       => array( 'width' => '40' ),
			)
		),
		arkan_acf_f( $prefix . '_url', __( 'URL', 'arkan' ), 'url', array( 'wrapper' => array( 'width' => '60' ) ) ),
	);
}

/**
 * Location rules for a page-level field group.
 *
 * Matches EITHER the assignable page template OR the page slug, because the
 * theme resolves About/Contact/etc. through page-{slug}.php in the template
 * hierarchy, where no template is assigned. See inc/acf-location.php.
 *
 * @param string $template Template file name, e.g. 'template-about.php'.
 * @param string $slug     Page slug key, e.g. 'about'.
 * @return array
 */
function arkan_acf_page_location( $template, $slug ) {
	$slugs  = function_exists( 'arkan_page_slugs' ) ? arkan_page_slugs() : array();
	$actual = isset( $slugs[ $slug ] ) ? $slugs[ $slug ] : $slug;

	return array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'page-templates/' . $template,
			),
		),
		array(
			array(
				'param'    => 'arkan_page_slug',
				'operator' => '==',
				'value'    => $actual,
			),
		),
	);
}

/**
 * Banner (page hero) sub-fields, reused by several groups.
 *
 * @return array
 */
function arkan_acf_banner_fields() {
	return array(
		arkan_acf_f(
			'banner_image',
			__( 'Banner Image', 'arkan' ),
			'image',
			array(
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => __( 'Leave empty to fall back to the featured image, then to the default banner in Theme Options.', 'arkan' ),
			)
		),
		arkan_acf_f(
			'banner_overlay',
			__( 'Overlay Darkness', 'arkan' ),
			'range',
			array(
				'min'           => 0,
				'max'           => 9,
				'step'          => 1,
				'default_value' => 4,
				'wrapper'       => array( 'width' => '50' ),
			)
		),
		arkan_acf_f(
			'banner_subtitle',
			__( 'Banner Subtitle', 'arkan' ),
			'text',
			array( 'wrapper' => array( 'width' => '50' ) )
		),
		arkan_acf_f(
			'banner_title',
			__( 'Banner Title', 'arkan' ),
			'text',
			array( 'instructions' => __( 'Leave empty to use the page title. Wrap a word in &lt;span&gt; to accent it.', 'arkan' ) )
		),
	);
}

/* -------------------------------------------------------------------------
 * Options page
 * ---------------------------------------------------------------------- */

/**
 * Register the Theme Options page.
 */
function arkan_acf_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Arkan Theme Options', 'arkan' ),
			'menu_title' => __( 'Theme Options', 'arkan' ),
			'menu_slug'  => 'arkan-options',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 59,
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'arkan_acf_options_page' );

/* -------------------------------------------------------------------------
 * Field groups
 * ---------------------------------------------------------------------- */

/**
 * Register every local field group.
 */
function arkan_acf_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	arkan_acf_group_options();
	arkan_acf_group_front_page();
	arkan_acf_group_banner();
	arkan_acf_group_project();
	arkan_acf_group_service();
	arkan_acf_group_team();
	arkan_acf_group_testimonial();
	arkan_acf_group_about_template();
	arkan_acf_group_contact_template();
	arkan_acf_group_gallery_template();
	arkan_acf_group_faq_template();
	arkan_acf_group_process_template();
	arkan_acf_group_services_template();
}
add_action( 'acf/init', 'arkan_acf_field_groups' );

/**
 * Theme options.
 */
function arkan_acf_group_options() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_options',
			'title'    => __( 'Theme Options', 'arkan' ),
			'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'arkan-options' ) ) ),
			'fields'   => array(

				/* ------------------------------------------------ General */
				arkan_acf_tab( 'general', __( 'General', 'arkan' ) ),
				arkan_acf_f(
					'default_banner',
					__( 'Default Page Banner Image', 'arkan' ),
					'image',
					array( 'return_format' => 'array', 'preview_size' => 'medium' )
				),
				arkan_acf_f( 'preloader_enabled', __( 'Show Preloader', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'scroll_top_enabled', __( 'Show Scroll-To-Top Ring', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'content_lines_enabled', __( 'Show Decorative Vertical Lines', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),

				/* --------------------------------------------- Lets Talk */
				arkan_acf_tab( 'letstalk', __( "Let's Talk Bar", 'arkan' ) ),
				arkan_acf_f( 'lets_talk_enabled', __( "Show Let's Talk section", 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'lets_talk_image', __( 'Background Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				arkan_acf_f( 'lets_talk_overlay', __( 'Overlay Darkness', 'arkan' ), 'range', array( 'min' => 0, 'max' => 9, 'default_value' => 6 ) ),
				arkan_acf_f( 'lets_talk_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Contact Us', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'lets_talk_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => "Let's discuss your project", 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'lets_talk_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 2, 'default_value' => 'Fill out the form and our manager will contact you for consultation.' ) ),
				arkan_acf_f(
					'lets_talk_form_id',
					__( 'Contact Form 7 ID', 'arkan' ),
					'text',
					array( 'instructions' => __( 'Paste only the numeric ID (or the full shortcode). See the theme README for the exact form markup to use.', 'arkan' ) )
				),

				/* ------------------------------------------------ Footer */
				arkan_acf_tab( 'footer', __( 'Footer', 'arkan' ) ),
				arkan_acf_f( 'footer_subtitle', __( 'Footer Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Contact Us' ) ),
				arkan_acf_f(
					'footer_offices',
					__( 'Offices', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add Office', 'arkan' ),
						'max'          => 2,
						'sub_fields'   => array(
							arkan_acf_f( 'office_city', __( 'City', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'office_label', __( 'Accent Word', 'arkan' ), 'text', array( 'default_value' => 'Office', 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'office_address', __( 'Address', 'arkan' ), 'textarea', array( 'rows' => 2, 'new_lines' => 'br' ) ),
							arkan_acf_f( 'office_phone', __( 'Phone', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'office_email', __( 'Email', 'arkan' ), 'email', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f(
								'office_socials',
								__( 'Social Links', 'arkan' ),
								'repeater',
								array(
									'layout'       => 'table',
									'button_label' => __( 'Add Link', 'arkan' ),
									'sub_fields'   => arkan_acf_social_subfields( 'office_social' ),
								)
							),
						),
					)
				),
				arkan_acf_f( 'footer_copyright', __( 'Copyright Line', 'arkan' ), 'text', array( 'default_value' => '© 2026 Arkan. All rights reserved.' ) ),

				/* --------------------------------------------- Archives */
				arkan_acf_tab( 'archives', __( 'Archives', 'arkan' ) ),
				arkan_acf_f( 'projects_archive_subtitle', __( 'Projects Archive Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Projects', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'projects_archive_title', __( 'Projects Archive Title', 'arkan' ), 'text', array( 'default_value' => 'Creative Projects', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'projects_archive_image', __( 'Projects Archive Banner', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				arkan_acf_f( 'projects_per_page', __( 'Projects Per Page', 'arkan' ), 'number', array( 'default_value' => 9, 'min' => 1 ) ),
				arkan_acf_f( 'services_archive_subtitle', __( 'Services Archive Subtitle', 'arkan' ), 'text', array( 'default_value' => 'What We Do', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'services_archive_title', __( 'Services Archive Title', 'arkan' ), 'text', array( 'default_value' => 'Our Services', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'services_archive_image', __( 'Services Archive Banner', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				arkan_acf_f( 'blog_archive_subtitle', __( 'Blog Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Blog', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'blog_archive_title', __( 'Blog Title', 'arkan' ), 'text', array( 'default_value' => 'Latest News', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'blog_archive_image', __( 'Blog Banner', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),

				/* -------------------------------------------------- 404 */
				arkan_acf_tab( 'notfound', __( '404 Page', 'arkan' ) ),
				arkan_acf_f( 'error_image', __( '404 Background', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				arkan_acf_f( 'error_title', __( '404 Title', 'arkan' ), 'text', array( 'default_value' => "Sorry We Can't Find That Page!" ) ),
				arkan_acf_f( 'error_text', __( '404 Text', 'arkan' ), 'textarea', array( 'rows' => 2, 'default_value' => 'The page you are looking for was moved, removed, renamed or never existed.' ) ),
			),
		)
	);
}

/**
 * Front page sections.
 */
function arkan_acf_group_front_page() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_front',
			'title'    => __( 'Home Page Sections', 'arkan' ),
			'location' => array(
				array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ),
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-home.php' ) ),
			),
			'menu_order' => 0,
			'position'   => 'normal',
			'fields'   => array(

				/* ------------------------------------------------- Hero */
				arkan_acf_tab( 'hero', __( 'Hero Slider', 'arkan' ) ),
				arkan_acf_f(
					'hero_slides',
					__( 'Slides', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add Slide', 'arkan' ),
						'sub_fields'   => array(
							arkan_acf_f( 'slide_image', __( 'Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
							arkan_acf_f( 'slide_overlay', __( 'Overlay Darkness', 'arkan' ), 'range', array( 'min' => 0, 'max' => 9, 'default_value' => 4, 'wrapper' => array( 'width' => '33' ) ) ),
							arkan_acf_f( 'slide_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '33' ) ) ),
							arkan_acf_f( 'slide_title', __( 'Title', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '34' ) ) ),
							arkan_acf_f( 'slide_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
							arkan_acf_f( 'slide_button_text', __( 'Button Text', 'arkan' ), 'text', array( 'default_value' => 'View Project', 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'slide_button_link', __( 'Button Link', 'arkan' ), 'link', array( 'return_format' => 'array', 'wrapper' => array( 'width' => '50' ) ) ),
						),
					)
				),

				/* ------------------------------------------------ About */
				arkan_acf_tab( 'about', __( 'About Section', 'arkan' ) ),
				arkan_acf_f( 'home_about_enabled', __( 'Show About Section', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'home_about_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Who are we?', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_about_title', __( 'Title', 'arkan' ), 'text', array( 'instructions' => __( 'Wrap a word in &lt;span&gt; to accent it, e.g. &lt;span&gt;About&lt;/span&gt; Arkan', 'arkan' ), 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_about_text', __( 'Text', 'arkan' ), 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
				arkan_acf_f(
					'home_about_boxes',
					__( 'Highlight Boxes', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add Box', 'arkan' ),
						'max'          => 3,
						'sub_fields'   => array(
							arkan_acf_f( 'box_icon', __( 'Icon Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
							arkan_acf_f( 'box_title', __( 'Title', 'arkan' ), 'text' ),
						),
					)
				),

				/* --------------------------------------------- Projects */
				arkan_acf_tab( 'projects', __( 'Projects Section', 'arkan' ) ),
				arkan_acf_f( 'home_projects_enabled', __( 'Show Projects Section', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'home_projects_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Discover', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_projects_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => 'Creative <span>Projects</span>', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_projects_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
				arkan_acf_f( 'home_projects_filter', __( 'Show Category Filter', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1, 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_projects_count', __( 'Number of Projects', 'arkan' ), 'number', array( 'default_value' => 6, 'min' => 1, 'wrapper' => array( 'width' => '50' ) ) ),

				/* ----------------------------------------- Testimonials */
				arkan_acf_tab( 'testimonials', __( 'Testimonials Section', 'arkan' ) ),
				arkan_acf_f( 'home_testimonials_enabled', __( 'Show Testimonials', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'home_testimonials_image', __( 'Background Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				arkan_acf_f( 'home_testimonials_overlay', __( 'Overlay Darkness', 'arkan' ), 'range', array( 'min' => 0, 'max' => 9, 'default_value' => 6 ) ),
				arkan_acf_f( 'home_testimonials_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Testimonials', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_testimonials_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => "What Client's Say?", 'wrapper' => array( 'width' => '50' ) ) ),

				/* ------------------------------------------------- Blog */
				arkan_acf_tab( 'blog', __( 'Latest News Section', 'arkan' ) ),
				arkan_acf_f( 'home_blog_enabled', __( 'Show Latest News', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'home_blog_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Blog', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_blog_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => '<span>Latest</span> News', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'home_blog_count', __( 'Number of Posts', 'arkan' ), 'number', array( 'default_value' => 3, 'min' => 1 ) ),
			),
		)
	);
}

/**
 * Page / post / CPT banner.
 */
function arkan_acf_group_banner() {
	acf_add_local_field_group(
		array(
			'key'        => 'group_arkan_banner',
			'title'      => __( 'Page Banner', 'arkan' ),
			'menu_order' => 5,
			'position'   => 'normal',
			'location'   => array(
				array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ),
				array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ) ),
				array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'project' ) ),
				array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'service' ) ),
			),
			'fields'     => arkan_acf_banner_fields(),
		)
	);
}

/**
 * Project details.
 */
function arkan_acf_group_project() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_project',
			'title'    => __( 'Project Details', 'arkan' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'project' ) ) ),
			'fields'   => array(
				arkan_acf_f( 'project_number', __( 'Project Number', 'arkan' ), 'text', array( 'instructions' => __( 'Shown above the title on cards, e.g. "Project P.01". Leave empty to auto-number.', 'arkan' ) ) ),
				arkan_acf_f( 'project_intro', __( 'Intro Paragraph', 'arkan' ), 'textarea', array( 'rows' => 3, 'instructions' => __( 'Shown above the image slider on the project page.', 'arkan' ) ) ),
				arkan_acf_f( 'project_gallery', __( 'Image Slider', 'arkan' ), 'gallery', array( 'return_format' => 'array', 'preview_size' => 'medium', 'insert' => 'append' ) ),
				arkan_acf_f( 'project_year', __( 'Year', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
				arkan_acf_f( 'project_company', __( 'Company', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
				arkan_acf_f( 'project_name', __( 'Name', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
				arkan_acf_f( 'project_location', __( 'Location', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
				arkan_acf_f(
					'project_features',
					__( 'Checklist', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add Item', 'arkan' ),
						'sub_fields'   => array( arkan_acf_f( 'feature_text', __( 'Text', 'arkan' ), 'text' ) ),
					)
				),
				arkan_acf_f( 'project_featured_home', __( 'Feature in Home Hero Slider', 'arkan' ), 'true_false', array( 'ui' => 1, 'instructions' => __( 'Only used when the Home hero slider has no manual slides.', 'arkan' ) ) ),
			),
		)
	);
}

/**
 * Service details.
 */
function arkan_acf_group_service() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_service',
			'title'    => __( 'Service Details', 'arkan' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'service' ) ) ),
			'fields'   => array(
				arkan_acf_f( 'service_number', __( 'Number', 'arkan' ), 'text', array( 'instructions' => __( 'Displayed on the card, e.g. 01. Leave empty to auto-number.', 'arkan' ), 'wrapper' => array( 'width' => '30' ) ) ),
				arkan_acf_f( 'service_card_image', __( 'Card Background', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => __( 'Falls back to the featured image.', 'arkan' ), 'wrapper' => array( 'width' => '70' ) ) ),
				arkan_acf_f( 'service_gallery', __( 'Gallery', 'arkan' ), 'gallery', array( 'return_format' => 'array', 'preview_size' => 'medium', 'insert' => 'append' ) ),
			),
		)
	);
}

/**
 * Team member.
 */
function arkan_acf_group_team() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_team',
			'title'    => __( 'Team Member Details', 'arkan' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'team' ) ) ),
			'fields'   => array(
				arkan_acf_f( 'team_role', __( 'Role / Qualification', 'arkan' ), 'text', array( 'instructions' => __( 'e.g. dipl. Arch ETH/SIA', 'arkan' ) ) ),
				arkan_acf_f(
					'team_socials',
					__( 'Social Links', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add Link', 'arkan' ),
						'sub_fields'   => arkan_acf_social_subfields( 'team_social' ),
					)
				),
			),
		)
	);
}

/**
 * Testimonial.
 */
function arkan_acf_group_testimonial() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_testimonial',
			'title'    => __( 'Testimonial Details', 'arkan' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'testimonial' ) ) ),
			'fields'   => array(
				arkan_acf_f( 'testimonial_role', __( 'Role / Company', 'arkan' ), 'text', array( 'instructions' => __( 'e.g. Crowne Plaza Owner', 'arkan' ) ) ),
			),
		)
	);
}

/**
 * About page template.
 */
function arkan_acf_group_about_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_about_tpl',
			'title'    => __( 'About Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-about.php', 'about' ),
			'fields'   => array(
				arkan_acf_tab( 'about_intro', __( 'Intro', 'arkan' ) ),
				arkan_acf_f( 'about_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'About', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'about_title', __( 'Title', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'about_stat_number', __( 'Stat Number', 'arkan' ), 'text', array( 'default_value' => '24', 'wrapper' => array( 'width' => '30' ) ) ),
				arkan_acf_f( 'about_stat_label', __( 'Stat Label', 'arkan' ), 'textarea', array( 'rows' => 2, 'new_lines' => 'br', 'default_value' => "Years\nOf Experience", 'wrapper' => array( 'width' => '70' ) ) ),
				arkan_acf_f(
					'about_portraits',
					__( 'Portraits', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add Portrait', 'arkan' ),
						'sub_fields'   => array(
							arkan_acf_f( 'portrait_image', __( 'Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
							arkan_acf_f( 'portrait_name', __( 'Name', 'arkan' ), 'text' ),
						),
					)
				),

				arkan_acf_tab( 'about_skills', __( 'Skills', 'arkan' ) ),
				arkan_acf_f(
					'about_skills',
					__( 'Skill Bars', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add Skill', 'arkan' ),
						'sub_fields'   => array(
							arkan_acf_f( 'skill_title', __( 'Title', 'arkan' ), 'text' ),
							arkan_acf_f( 'skill_value', __( 'Percent', 'arkan' ), 'number', array( 'min' => 0, 'max' => 100, 'default_value' => 80 ) ),
						),
					)
				),

				arkan_acf_tab( 'about_team', __( 'Team', 'arkan' ) ),
				arkan_acf_f( 'about_team_enabled', __( 'Show Team Section', 'arkan' ), 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				arkan_acf_f( 'about_team_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Creative Thinkers', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'about_team_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => 'Team <span>Members</span>', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'about_team_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
			),
		)
	);
}

/**
 * Contact page template.
 */
function arkan_acf_group_contact_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_contact_tpl',
			'title'    => __( 'Contact Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-contact.php', 'contact' ),
			'fields'   => array(
				arkan_acf_f(
					'contact_locations',
					__( 'Offices', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add Office', 'arkan' ),
						'max'          => 2,
						'sub_fields'   => array(
							arkan_acf_f( 'location_city', __( 'City', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'location_label', __( 'Accent Word', 'arkan' ), 'text', array( 'default_value' => 'Office', 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'location_address', __( 'Address', 'arkan' ), 'textarea', array( 'rows' => 2, 'new_lines' => 'br' ) ),
							arkan_acf_f( 'location_phone', __( 'Phone', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f( 'location_email', __( 'Email', 'arkan' ), 'email', array( 'wrapper' => array( 'width' => '50' ) ) ),
							arkan_acf_f(
								'location_socials',
								__( 'Social Links', 'arkan' ),
								'repeater',
								array(
									'layout'       => 'table',
									'button_label' => __( 'Add Link', 'arkan' ),
									'sub_fields'   => arkan_acf_social_subfields( 'location_social' ),
								)
							),
						),
					)
				),
				arkan_acf_f( 'contact_form_title', __( 'Form Heading', 'arkan' ), 'text', array( 'default_value' => 'Have a Project? - <span>Lets Talk</span>' ) ),
				arkan_acf_f( 'contact_form_id', __( 'Contact Form 7 ID', 'arkan' ), 'text' ),
				arkan_acf_f( 'contact_map', __( 'Google Maps Embed', 'arkan' ), 'textarea', array( 'rows' => 3, 'instructions' => __( 'Paste the full &lt;iframe&gt; embed code, or just the src URL.', 'arkan' ) ) ),
			),
		)
	);
}

/**
 * Gallery page template.
 */
function arkan_acf_group_gallery_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_gallery_tpl',
			'title'    => __( 'Gallery Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-gallery.php', 'gallery' ),
			'fields'   => array(
				arkan_acf_f( 'gallery_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'Images', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'gallery_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => '<span>Image</span> Gallery', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'gallery_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
				arkan_acf_f( 'gallery_images', __( 'Images', 'arkan' ), 'gallery', array( 'return_format' => 'array', 'preview_size' => 'medium', 'insert' => 'append' ) ),
			),
		)
	);
}

/**
 * FAQ page template.
 */
function arkan_acf_group_faq_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_faq_tpl',
			'title'    => __( 'FAQ Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-faq.php', 'faq' ),
			'fields'   => array(
				arkan_acf_f(
					'faq_items',
					__( 'Questions', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add Question', 'arkan' ),
						'sub_fields'   => array(
							arkan_acf_f( 'faq_question', __( 'Question', 'arkan' ), 'text' ),
							arkan_acf_f( 'faq_answer', __( 'Answer', 'arkan' ), 'textarea', array( 'rows' => 3, 'new_lines' => 'br' ) ),
						),
					)
				),
			),
		)
	);
}

/**
 * Process page template.
 */
function arkan_acf_group_process_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_process_tpl',
			'title'    => __( 'Process Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-process.php', 'process' ),
			'fields'   => array(
				arkan_acf_f( 'process_subtitle', __( 'Subtitle', 'arkan' ), 'text', array( 'default_value' => 'How We Work', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f( 'process_title', __( 'Title', 'arkan' ), 'text', array( 'default_value' => 'Our <span>Process</span>', 'wrapper' => array( 'width' => '50' ) ) ),
				arkan_acf_f(
					'process_steps',
					__( 'Steps', 'arkan' ),
					'repeater',
					array(
						'layout'       => 'block',
						'button_label' => __( 'Add Step', 'arkan' ),
						'sub_fields'   => array(
							arkan_acf_f( 'step_image', __( 'Image', 'arkan' ), 'image', array( 'return_format' => 'array', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => '40' ) ) ),
							arkan_acf_f( 'step_title', __( 'Title', 'arkan' ), 'text', array( 'wrapper' => array( 'width' => '60' ) ) ),
							arkan_acf_f( 'step_text', __( 'Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
						),
					)
				),
			),
		)
	);
}

/**
 * Services page template (a page that lists the service CPT).
 */
function arkan_acf_group_services_template() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_arkan_services_tpl',
			'title'    => __( 'Services Page', 'arkan' ),
			'location' => arkan_acf_page_location( 'template-services.php', 'services' ),
			'fields'   => array(
				arkan_acf_f( 'services_intro', __( 'Intro Text', 'arkan' ), 'textarea', array( 'rows' => 3 ) ),
				arkan_acf_f( 'services_count', __( 'Number of Services', 'arkan' ), 'number', array( 'default_value' => -1, 'instructions' => __( 'Use -1 to show all.', 'arkan' ) ) ),
			),
		)
	);
}
