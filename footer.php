<?php
/**
 * Let's Talk bar, footer, closing wrappers.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
	</div><!-- #content -->

	<?php get_template_part( 'template-parts/global/lets-talk' ); ?>

	<?php
	$arkan_offices   = arkan_option( 'footer_offices', array() );
	$arkan_subtitle  = arkan_option( 'footer_subtitle', __( 'Contact Us', 'arkan' ) );
	$arkan_copyright = arkan_option( 'footer_copyright' );
	$arkan_col       = ( is_array( $arkan_offices ) && count( $arkan_offices ) > 1 ) ? 'col-md-4' : 'col-md-8';
	?>
	<!-- Footer -->
	<footer class="footer">
		<?php if ( ! empty( $arkan_offices ) ) : ?>
			<div class="top">
				<div class="container">
					<div class="row">
						<div class="col-md-4 mb-30">
							<div class="sub-title border-footer-light"><?php echo esc_html( $arkan_subtitle ); ?></div>
						</div>
						<?php foreach ( $arkan_offices as $arkan_office ) : ?>
							<div class="<?php echo esc_attr( $arkan_col ); ?>">
								<div class="item">
									<?php if ( ! empty( $arkan_office['office_city'] ) ) : ?>
										<h3>
											<?php echo esc_html( $arkan_office['office_city'] ); ?>
											<?php if ( ! empty( $arkan_office['office_label'] ) ) : ?>
												<span><?php echo esc_html( $arkan_office['office_label'] ); ?></span>
											<?php endif; ?>
										</h3>
									<?php endif; ?>

									<?php if ( ! empty( $arkan_office['office_address'] ) ) : ?>
										<p><?php echo wp_kses_post( $arkan_office['office_address'] ); ?></p>
									<?php endif; ?>

									<?php if ( ! empty( $arkan_office['office_phone'] ) ) : ?>
										<p class="phone"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $arkan_office['office_phone'] ) ); ?>"><?php echo esc_html( $arkan_office['office_phone'] ); ?></a></p>
									<?php endif; ?>

									<?php if ( ! empty( $arkan_office['office_email'] ) ) : ?>
										<p class="mail"><a href="mailto:<?php echo esc_attr( $arkan_office['office_email'] ); ?>"><?php echo esc_html( $arkan_office['office_email'] ); ?></a></p>
									<?php endif; ?>

									<?php
									if ( ! empty( $arkan_office['office_socials'] ) ) {
										arkan_social_links( $arkan_office['office_socials'], 'office_social_icon', 'office_social_url', 'div', 'social mt-2' );
									}
									?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="bottom">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<p>
							<?php
							if ( $arkan_copyright ) {
								echo wp_kses_post( $arkan_copyright );
							} else {
								/* translators: 1: year, 2: site name */
								printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'arkan' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
							}
							?>
						</p>
					</div>
					<div class="col-md-8">
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'container'      => false,
									'menu_class'     => 'footer-menu right',
									'depth'          => 1,
								)
							);
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</footer>

</div><!-- .content-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
