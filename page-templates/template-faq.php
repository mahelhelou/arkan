<?php
/**
 * Template Name: FAQ Page
 * Template Post Type: page
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id = get_the_ID();

arkan_banner();

$items = arkan_field( 'faq_items', $page_id, array() );
?>

<section class="section-padding">
	<div class="container">

		<?php if ( ! empty( $items ) ) : ?>
			<div class="row mb-5 animate-box" data-animate-effect="fadeInUp">
				<div class="col-md-12">
					<ul class="accordion-box clearfix">
						<?php
						$first = true;
						foreach ( $items as $item ) :
							?>
							<li class="accordion block<?php echo $first ? ' active-block' : ''; ?>">
								<div class="acc-btn<?php echo $first ? ' active' : ''; ?>"><?php echo esc_html( isset( $item['faq_question'] ) ? $item['faq_question'] : '' ); ?></div>
								<div class="acc-content<?php echo $first ? ' current' : ''; ?>">
									<div class="content">
										<div class="text"><?php echo wp_kses_post( isset( $item['faq_answer'] ) ? $item['faq_answer'] : '' ); ?></div>
									</div>
								</div>
							</li>
							<?php
							$first = false;
						endforeach;
						?>
					</ul>
				</div>
			</div>
		<?php endif; ?>

		<?php
		while ( have_posts() ) :
			the_post();
			if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
				?>
				<div class="row"><div class="col-md-12 entry-content"><?php the_content(); ?></div></div>
				<?php
			endif;
		endwhile;
		?>

	</div>
</section>

<?php
get_footer();
