<?php
/**
 * Template tags — every repeated chunk of the design lives here.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/* =========================================================================
 * Banner (banner-header)
 * ====================================================================== */

/**
 * Work out banner content for the current view.
 *
 * @param array $args Overrides: image, overlay, subtitle, subtitle_link, title, extra.
 * @return array
 */
function arkan_get_banner_data( $args = array() ) {
	$defaults = array(
		'image'         => '',
		'overlay'       => 4,
		'subtitle'      => '',
		'subtitle_link' => '',
		'title'         => '',
		'meta'          => '',
		'classes'       => '',
	);

	$data = wp_parse_args( $args, $defaults );

	if ( is_singular() ) {
		$post_id = get_queried_object_id();

		if ( '' === $data['image'] ) {
			$data['image'] = arkan_image_url( arkan_field( 'banner_image', $post_id ), 'arkan-slider' );
		}
		if ( '' === $data['image'] && has_post_thumbnail( $post_id ) ) {
			$data['image'] = get_the_post_thumbnail_url( $post_id, 'arkan-slider' );
		}

		$overlay = arkan_field( 'banner_overlay', $post_id, '' );
		if ( '' !== $overlay ) {
			$data['overlay'] = (int) $overlay;
		}

		if ( '' === $data['subtitle'] ) {
			$data['subtitle'] = arkan_field( 'banner_subtitle', $post_id );
		}
		if ( '' === $data['title'] ) {
			$data['title'] = arkan_field( 'banner_title', $post_id, get_the_title( $post_id ) );
		}
	}

	// Global fallback image.
	if ( '' === $data['image'] ) {
		$data['image'] = arkan_image_url( arkan_option( 'default_banner' ), 'arkan-slider' );
	}
	if ( '' === $data['image'] ) {
		$data['image'] = ARKAN_URI . 'assets/images/slider/1.jpg';
	}

	return $data;
}

/**
 * Output the page banner.
 *
 * @param array $args See arkan_get_banner_data().
 */
function arkan_banner( $args = array() ) {
	$d = arkan_get_banner_data( $args );
	?>
	<div class="banner-header valign bg-img bg-fixed <?php echo esc_attr( $d['classes'] ); ?>" data-overlay-dark="<?php echo esc_attr( $d['overlay'] ); ?>" data-background="<?php echo esc_url( $d['image'] ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 caption mt-60">
					<?php if ( $d['subtitle'] ) : ?>
						<div class="subtitle">
							<?php if ( $d['subtitle_link'] ) : ?>
								<a href="<?php echo esc_url( $d['subtitle_link'] ); ?>"><?php echo wp_kses_post( $d['subtitle'] ); ?></a>
							<?php else : ?>
								<?php echo wp_kses_post( $d['subtitle'] ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $d['title'] ) : ?>
						<div class="title"><?php echo wp_kses_post( $d['title'] ); ?></div>
					<?php endif; ?>
					<?php echo $d['meta']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- pre-built markup. ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/* =========================================================================
 * Section heading (sub-title + section-title + text)
 * ====================================================================== */

/**
 * Two-column section heading used all over the design.
 *
 * @param array $args subtitle, title, text, border (light|dark|footer-light), row_class.
 */
function arkan_section_heading( $args = array() ) {
	$a = wp_parse_args(
		$args,
		array(
			'subtitle'  => '',
			'title'     => '',
			'text'      => '',
			'border'    => 'light',
			'row_class' => 'mb-5',
		)
	);

	if ( ! $a['subtitle'] && ! $a['title'] && ! $a['text'] ) {
		return;
	}

	$border_class = 'footer-light' === $a['border'] ? 'border-footer-light' : 'border-bot-' . $a['border'];
	?>
	<div class="row <?php echo esc_attr( $a['row_class'] ); ?> animate-box" data-animate-effect="fadeInUp">
		<div class="col-md-4 mb-30">
			<?php if ( $a['subtitle'] ) : ?>
				<div class="sub-title <?php echo esc_attr( $border_class ); ?>"><?php echo wp_kses_post( $a['subtitle'] ); ?></div>
			<?php endif; ?>
		</div>
		<div class="col-md-8">
			<?php if ( $a['title'] ) : ?>
				<div class="section-title"><?php echo wp_kses_post( $a['title'] ); ?></div>
			<?php endif; ?>
			<?php if ( $a['text'] ) : ?>
				<p><?php echo wp_kses_post( $a['text'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/* =========================================================================
 * Social links
 * ====================================================================== */

/**
 * Render a set of social links from an ACF repeater value.
 *
 * @param array  $links     Repeater rows.
 * @param string $icon_key  Sub-field name for the icon.
 * @param string $url_key   Sub-field name for the URL.
 * @param string $wrapper   'div' (footer/contact) or 'ul' (team).
 * @param string $class     Wrapper class.
 */
function arkan_social_links( $links, $icon_key, $url_key, $wrapper = 'div', $class = 'social mt-2' ) {
	if ( empty( $links ) || ! is_array( $links ) ) {
		return;
	}

	$is_list = 'ul' === $wrapper;
	echo $is_list ? '<ul class="' . esc_attr( $class ) . '">' : '<div class="' . esc_attr( $class ) . '">';

	foreach ( $links as $link ) {
		$icon = isset( $link[ $icon_key ] ) ? $link[ $icon_key ] : '';
		$url  = isset( $link[ $url_key ] ) ? $link[ $url_key ] : '';
		if ( ! $url ) {
			continue;
		}
		$anchor = sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer"><i class="%s"></i></a>',
			esc_url( $url ),
			esc_attr( $icon ? $icon : 'ti-world' )
		);
		echo $is_list ? '<li>' . $anchor . '</li>' : $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}

	echo $is_list ? '</ul>' : '</div>';
}

/* =========================================================================
 * Cards
 * ====================================================================== */

/**
 * Project label, e.g. "Project P.01".
 *
 * @param int $post_id  Project ID.
 * @param int $position Loop position, used when no manual number is set.
 * @return string
 */
function arkan_project_number( $post_id = 0, $position = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$manual  = arkan_field( 'project_number', $post_id );
	if ( $manual ) {
		return $manual;
	}
	return sprintf(
		/* translators: %s: zero-padded project number */
		__( 'Project P.%s', 'arkan' ),
		arkan_pad( $position ? $position : 1 )
	);
}

/**
 * Project card used on the projects archive and the "Other Projects" carousel.
 *
 * @param int $position Loop position.
 */
function arkan_project_card( $position = 0 ) {
	?>
	<div class="items mb-4">
		<div class="con">
			<div class="img">
				<a href="<?php the_permalink(); ?>">
					<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'arkan-project', 'images/projects/01.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				</a>
			</div>
			<div class="info">
				<span class="category mb-0"><?php echo esc_html( arkan_project_number( get_the_ID(), $position ) ); ?></span>
				<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Service card.
 *
 * @param int $position Loop position, used for the number and the bg-N class.
 */
function arkan_service_card( $position = 1 ) {
	$number = arkan_field( 'service_number', get_the_ID(), arkan_pad( $position ) );
	$image  = arkan_image_url( arkan_field( 'service_card_image', get_the_ID() ), 'arkan-slider' );
	if ( ! $image && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_the_ID(), 'arkan-slider' );
	}

	// The original design used .bg-1 … .bg-6 with CSS backgrounds; keep that as fallback.
	$bg_class = 'bg-' . ( ( ( $position - 1 ) % 6 ) + 1 );
	$style    = $image ? ' style="background-image:url(' . esc_url( $image ) . ');"' : '';
	?>
	<div class="item <?php echo esc_attr( $bg_class ); ?> mb-4"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
		<div class="con">
			<a href="<?php the_permalink(); ?>">
				<div class="numb"><?php echo esc_html( $number ); ?></div>
				<h5><?php the_title(); ?></h5>
				<p><?php echo esc_html( arkan_excerpt( 20 ) ); ?></p>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Blog card used in the home "Latest News" carousel.
 */
function arkan_post_card() {
	$cats = get_the_category();
	?>
	<div class="item">
		<div class="post-img">
			<a href="<?php the_permalink(); ?>">
				<div class="img">
					<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'arkan-blog', 'images/blog/1.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				</div>
			</a>
		</div>
		<div class="cont">
			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<div class="info">
				<?php if ( ! empty( $cats ) ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><span><?php echo esc_html( $cats[0]->name ); ?></span></a>
				<?php endif; ?>
				<a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date( 'M, d' ) ); ?></a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Team member card.
 */
function arkan_team_card() {
	$role    = arkan_field( 'team_role', get_the_ID() );
	$socials = arkan_field( 'team_socials', get_the_ID(), array() );
	?>
	<div class="wrap">
		<div class="con">
			<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'arkan-team', 'images/team/1.jpg' ) ); ?>" class="img-fluid" alt="<?php the_title_attribute(); ?>">
			<div class="info">
				<h4 class="name"><?php the_title(); ?></h4>
			</div>
		</div>
		<?php arkan_social_links( $socials, 'team_social_icon', 'team_social_url', 'ul', 'social' ); ?>
		<?php if ( $role ) : ?>
			<p><?php echo esc_html( $role ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Gallery item with magnific popup zoom.
 *
 * @param array  $image ACF image array.
 * @param string $col   Bootstrap column classes.
 * @param string $title Link title.
 */
function arkan_gallery_item( $image, $col = 'col-md-4', $title = '' ) {
	$full  = arkan_image_url( $image, 'full' );
	$thumb = arkan_image_url( $image, 'arkan-project' );
	if ( ! $full ) {
		return;
	}
	$alt = is_array( $image ) && ! empty( $image['alt'] ) ? $image['alt'] : $title;
	?>
	<div class="<?php echo esc_attr( $col ); ?> gallery-item animate-box" data-animate-effect="fadeInUp">
		<a href="<?php echo esc_url( $full ); ?>" title="<?php echo esc_attr( $title ); ?>" class="img-zoom">
			<div class="gallery-box">
				<div class="gallery-img"><img src="<?php echo esc_url( $thumb ? $thumb : $full ); ?>" class="img-fluid mx-auto d-block" alt="<?php echo esc_attr( $alt ); ?>"></div>
				<div class="gallery-detail text-center"><i class="ti-fullscreen"></i></div>
			</div>
		</a>
	</div>
	<?php
}

/* =========================================================================
 * Pagination
 * ====================================================================== */

/**
 * Pagination styled like .pagination-wrap in the design.
 *
 * @param WP_Query|null $query Optional custom query.
 */
function arkan_pagination( $query = null ) {
	global $wp_query;
	$q = $query ? $query : $wp_query;

	if ( $q->max_num_pages < 2 ) {
		return;
	}

	// Pagination on a sub-loop (e.g. a page with its own query) uses ?page,
	// everything else uses ?paged.
	$current = max( 1, (int) get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : (int) get_query_var( 'page' ) );

	$links = paginate_links(
		array(
			'total'     => $q->max_num_pages,
			'current'   => $current,
			'type'      => 'array',
			'prev_text' => '<i class="ti-angle-left"></i>',
			'next_text' => '<i class="ti-angle-right"></i>',
			'mid_size'  => 1,
		)
	);

	if ( empty( $links ) ) {
		return;
	}

	// The original classes are kept intact — assets/css/wp-overrides.css styles
	// .page-numbers (both <a> and the current <span>) to match the design.
	?>
	<div class="row">
		<div class="col-md-12 text-center animate-box" data-animate-effect="fadeInUp">
			<ul class="pagination-wrap align-center mt-30">
				<?php foreach ( $links as $link ) : ?>
					<li><?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of paginate_links(). ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
}

/* =========================================================================
 * Post meta
 * ====================================================================== */

/**
 * "date / category" meta line used by the blog list and single post.
 *
 * @param int $post_id Post ID.
 */
function arkan_post_meta( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats    = get_the_category( $post_id );
	?>
	<div class="date">
		<span class="ti-time"></span> <?php echo esc_html( get_the_date( 'd.m.Y', $post_id ) ); ?>
		<?php if ( ! empty( $cats ) ) : ?>
			<span class="ti-tag"></span> <?php echo esc_html( $cats[0]->name ); ?>
		<?php endif; ?>
	</div>
	<?php
}

/* =========================================================================
 * Loop helpers
 * ====================================================================== */

/**
 * Query helper for a CPT loop.
 *
 * @param string $post_type Post type.
 * @param int    $count     Posts per page.
 * @param array  $extra     Extra WP_Query args.
 * @return WP_Query
 */
function arkan_query( $post_type, $count = -1, $extra = array() ) {
	return new WP_Query(
		array_merge(
			array(
				'post_type'           => $post_type,
				'posts_per_page'      => (int) $count,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'orderby'             => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'       => true,
			),
			$extra
		)
	);
}
