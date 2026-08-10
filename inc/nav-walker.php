<?php
/**
 * Bootstrap 5 navigation walker matching the template markup.
 *
 * Produces:
 *   depth 0 : <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" ...>Label <i class="ti-angle-down"></i></a>
 *   depth 1 : <li class="dropdown-submenu dropdown"><a class="dropdown-item dropdown-toggle"><span>Label <i class="ti-angle-right"></i></span></a>
 *   depth 2+: <li><a class="dropdown-item"><span>Label</span></a>
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Arkan_Nav_Walker
 */
class Arkan_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Start of a sub-menu <ul>.
	 *
	 * @param string   $output Output buffer.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"dropdown-menu\">\n";
	}

	/**
	 * End of a sub-menu <ul>.
	 *
	 * @param string   $output Output buffer.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
	}

	/**
	 * Start of a menu item.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Menu id.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent      = $depth ? str_repeat( "\t", $depth ) : '';
		$has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );
		$is_current   = in_array( 'current-menu-item', (array) $item->classes, true )
			|| in_array( 'current-menu-ancestor', (array) $item->classes, true )
			|| in_array( 'current_page_item', (array) $item->classes, true )
			|| in_array( 'current_page_parent', (array) $item->classes, true );

		// ---------- <li> classes ----------
		$classes   = array_filter( (array) $item->classes );
		$li_classes = array();

		if ( 0 === $depth ) {
			$li_classes[] = 'nav-item';
			if ( $has_children ) {
				$li_classes[] = 'dropdown';
			}
		} elseif ( $has_children ) {
			$li_classes[] = 'dropdown-submenu';
			$li_classes[] = 'dropdown';
		}

		// Keep author-supplied custom classes from the menu screen.
		foreach ( $classes as $class ) {
			if ( 0 === strpos( $class, 'menu-item' ) || 0 === strpos( $class, 'current' ) || 0 === strpos( $class, 'page-item' ) || 0 === strpos( $class, 'page_item' ) ) {
				continue;
			}
			$li_classes[] = $class;
		}

		$li_classes = apply_filters( 'nav_menu_css_class', array_unique( $li_classes ), $item, $args, $depth );
		$class_attr = $li_classes ? ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '"' : '';
		$id_attr    = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id_attr    = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';

		$output .= $indent . '<li' . $id_attr . $class_attr . '>';

		// ---------- <a> classes ----------
		$link_classes = array( 0 === $depth ? 'nav-link' : 'dropdown-item' );
		if ( $has_children ) {
			$link_classes[] = 'dropdown-toggle';
		}
		if ( $is_current ) {
			$link_classes[] = 'active';
		}

		$atts = array(
			'title'  => $item->attr_title,
			'target' => $item->target,
			'rel'    => $item->xfn,
			'href'   => $item->url,
			'class'  => implode( ' ', $link_classes ),
		);

		if ( $has_children ) {
			$atts['href']                 = '#';
			$atts['role']                 = 'button';
			$atts['data-bs-toggle']       = 'dropdown';
			$atts['data-bs-auto-close']   = 'outside';
			$atts['aria-expanded']        = 'false';
		}
		if ( $is_current ) {
			$atts['aria-current'] = 'page';
		}

		$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' === $value || false === $value || is_null( $value ) ) {
				continue;
			}
			$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}

		// ---------- label ----------
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		if ( 0 === $depth ) {
			$label = $title . ( $has_children ? ' <i class="ti-angle-down"></i>' : '' );
		} else {
			$label = '<span>' . $title . ( $has_children ? ' <i class="ti-angle-right"></i>' : '' ) . '</span>';
		}

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . $label . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * End of a menu item.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}

	/**
	 * Fallback when no menu is assigned: list top-level pages in the same markup.
	 *
	 * @param array $args wp_nav_menu args.
	 */
	public static function fallback( $args = array() ) {
		$pages = get_pages(
			array(
				'sort_column' => 'menu_order,post_title',
				'parent'      => 0,
				'number'      => 8,
			)
		);

		if ( empty( $pages ) ) {
			return;
		}

		echo '<ul class="navbar-nav ms-auto">';
		echo '<li class="nav-item"><a class="nav-link' . ( is_front_page() ? ' active' : '' ) . '" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'arkan' ) . '</a></li>';
		foreach ( $pages as $page ) {
			printf(
				'<li class="nav-item"><a class="nav-link%3$s" href="%1$s">%2$s</a></li>',
				esc_url( get_permalink( $page->ID ) ),
				esc_html( $page->post_title ),
				is_page( $page->ID ) ? ' active' : ''
			);
		}
		echo '</ul>';
	}
}
