<?php
/**
 * Contact page — applied automatically to the page with the slug "contact".
 *
 * No template assignment needed. The body is shared with the assignable
 * "Contact Page" template so both routes stay in sync.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/page/contact' );
get_footer();
