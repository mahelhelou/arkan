<?php
/**
 * Search form.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="arkan-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="arkan-search-<?php echo esc_attr( uniqid() ); ?>"><?php esc_html_e( 'Search for:', 'arkan' ); ?></label>
	<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search here', 'arkan' ); ?>" required>
	<input type="submit" value="<?php esc_attr_e( 'Search', 'arkan' ); ?>">
</form>
