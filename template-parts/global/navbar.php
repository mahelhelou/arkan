<?php
/**
 * Navbar — a 1:1 copy of the markup in frontend/index.html.
 *
 * Every class, icon and dropdown level is preserved. Only href values differ:
 * they resolve through arkan_nav_url() to real WordPress destinations.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$home_url   = arkan_nav_url( 'home' );
$logo_id    = (int) get_theme_mod( 'custom_logo' );
$logo_url   = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
$projects   = arkan_nav_url( 'projects' );
$blog_url   = arkan_nav_url( 'blog' );
$is_home    = arkan_nav_is_active( 'home' );
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
	<div class="container">

		<!-- Logo -->
		<div class="logo-wrapper">
			<a class="logo" href="<?php echo esc_url( $home_url ); ?>">
				<?php if ( $logo_url ) : ?>
					<img src="<?php echo esc_url( $logo_url ); ?>" class="logo-img" alt="<?php bloginfo( 'name' ); ?>">
				<?php else : ?>
					<h2><?php bloginfo( 'name' ); ?> <span><?php bloginfo( 'description' ); ?></span></h2>
				<?php endif; ?>
			</a>
		</div>

		<!-- Button -->
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'arkan' ); ?>">
			<span class="navbar-toggler-icon"><i class="ti-menu"></i></span>
		</button>

		<!-- Menu -->
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="navbar-nav ms-auto">

				<!-- Home -->
				<li class="nav-item dropdown">
					<a <?php arkan_nav_link_class( 'home', true ); ?> href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><?php esc_html_e( 'Home', 'arkan' ); ?> <i class="ti-angle-down"></i></a>
					<ul class="dropdown-menu">
						<?php for ( $i = 1; $i <= 11; $i++ ) : ?>
							<li>
								<a href="<?php echo esc_url( $home_url ); ?>" <?php arkan_nav_item_class( $is_home && 1 === $i ); ?>>
									<span>
										<?php
										/* translators: %s: two-digit layout number */
										printf( esc_html__( 'Home Layout %s', 'arkan' ), esc_html( arkan_pad( $i ) ) );
										?>
									</span>
								</a>
							</li>
						<?php endfor; ?>
					</ul>
				</li>

				<!-- About -->
				<li class="nav-item">
					<a <?php arkan_nav_link_class( 'about' ); ?> href="<?php echo esc_url( arkan_nav_url( 'about' ) ); ?>"><?php esc_html_e( 'About', 'arkan' ); ?></a>
				</li>

				<!-- Services -->
				<li class="nav-item">
					<a <?php arkan_nav_link_class( 'services' ); ?> href="<?php echo esc_url( arkan_nav_url( 'services' ) ); ?>"><?php esc_html_e( 'Services', 'arkan' ); ?></a>
				</li>

				<!-- Projects -->
				<li class="nav-item dropdown">
					<a <?php arkan_nav_link_class( 'projects', true ); ?> href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><?php esc_html_e( 'Projects', 'arkan' ); ?> <i class="ti-angle-down"></i></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo esc_url( $projects ); ?>" <?php arkan_nav_item_class( is_post_type_archive( 'project' ) ); ?>><span><?php esc_html_e( 'Projects 01', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( $projects ); ?>" <?php arkan_nav_item_class(); ?>><span><?php esc_html_e( 'Projects 02', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( $projects ); ?>" <?php arkan_nav_item_class(); ?>><span><?php esc_html_e( 'Projects 03', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( arkan_nav_url( 'project_single' ) ); ?>" <?php arkan_nav_item_class( is_singular( 'project' ) ); ?>><span><?php esc_html_e( 'Project Page', 'arkan' ); ?></span></a></li>
					</ul>
				</li>

				<!-- Pages -->
				<li class="nav-item dropdown">
					<a <?php arkan_nav_link_class( 'pages', true ); ?> href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><?php esc_html_e( 'Pages', 'arkan' ); ?> <i class="ti-angle-down"></i></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo esc_url( arkan_nav_url( 'gallery' ) ); ?>" <?php arkan_nav_item_class( arkan_nav_is_active( 'gallery' ) ); ?>><span><?php esc_html_e( 'Gallery', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( arkan_nav_url( 'faq' ) ); ?>" <?php arkan_nav_item_class( arkan_nav_is_active( 'faq' ) ); ?>><span><?php esc_html_e( 'Faq', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( arkan_nav_url( 'process' ) ); ?>" <?php arkan_nav_item_class( arkan_nav_is_active( 'process' ) ); ?>><span><?php esc_html_e( 'Process', 'arkan' ); ?></span></a></li>
						<li class="dropdown-submenu dropdown">
							<a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" href="#"><span><?php esc_html_e( 'Other Pages', 'arkan' ); ?> <i class="ti-angle-right"></i></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo esc_url( arkan_nav_url( 'service_single' ) ); ?>" <?php arkan_nav_item_class( is_singular( 'service' ) ); ?>><span><?php esc_html_e( 'Services Page', 'arkan' ); ?></span></a></li>
								<li><a href="<?php echo esc_url( arkan_nav_url( '404' ) ); ?>" <?php arkan_nav_item_class( is_404() ); ?>><span><?php esc_html_e( '404 Page', 'arkan' ); ?></span></a></li>
							</ul>
						</li>
					</ul>
				</li>

				<!-- Blog -->
				<li class="nav-item dropdown">
					<a <?php arkan_nav_link_class( 'blog', true ); ?> href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><?php esc_html_e( 'Blog', 'arkan' ); ?> <i class="ti-angle-down"></i></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo esc_url( $blog_url ); ?>" <?php arkan_nav_item_class( is_home() ); ?>><span><?php esc_html_e( 'Blog 01', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( $blog_url ); ?>" <?php arkan_nav_item_class(); ?>><span><?php esc_html_e( 'Blog 02', 'arkan' ); ?></span></a></li>
						<li><a href="<?php echo esc_url( arkan_nav_url( 'post_single' ) ); ?>" <?php arkan_nav_item_class( is_singular( 'post' ) ); ?>><span><?php esc_html_e( 'Post Single', 'arkan' ); ?></span></a></li>
					</ul>
				</li>

				<!-- Contact -->
				<li class="nav-item">
					<a <?php arkan_nav_link_class( 'contact' ); ?> href="<?php echo esc_url( arkan_nav_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact', 'arkan' ); ?></a>
				</li>

			</ul>
		</div>

	</div>
</nav>
