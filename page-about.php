<?php
/**
 * About page — applied automatically to the page with the slug "about".
 *
 * No template assignment needed. The body is shared with the assignable
 * "About Page" template so both routes stay in sync.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/page/about' );
get_footer();
