<?php
/**
 * Shown when a loop returns nothing.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap no-results">
	<div class="con">
		<div class="title"><?php esc_html_e( 'Nothing found', 'arkan' ); ?></div>

		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, nothing matched your search terms. Try again with different keywords.', 'arkan' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It looks like nothing has been published here yet.', 'arkan' ); ?></p>
		<?php endif; ?>
	</div>
</div>
