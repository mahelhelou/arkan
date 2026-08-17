<?php
/**
 * About page body — a 1:1 conversion of frontend/about.html.
 *
 * Shared by page-about.php (matched on the "about" slug) and
 * page-templates/template-about.php (assignable template), so both routes
 * render identically.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;

$page_id = get_the_ID();

arkan_banner();

$subtitle  = arkan_field( 'about_subtitle', $page_id );
$title     = arkan_field( 'about_title', $page_id );
$stat_num  = arkan_field( 'about_stat_number', $page_id );
$stat_lbl  = arkan_field( 'about_stat_label', $page_id );
$portraits = arkan_field( 'about_portraits', $page_id, array() );
$skills    = arkan_field( 'about_skills', $page_id, array() );
?>

<!-- About -->
<section class="about section-padding">
	<div class="container">
		<div class="row">

			<div class="col-lg-4 col-md-4 mb-30 animate-box" data-animate-effect="fadeInUp">
				<?php if ( $subtitle ) : ?>
					<div class="sub-title border-bot-light"><?php echo esc_html( $subtitle ); ?></div>
				<?php endif; ?>
			</div>

			<div class="col-lg-4 col-md-8 animate-box" data-animate-effect="fadeInUp">
				<?php if ( $title ) : ?>
					<div class="section-title"><?php echo wp_kses_post( $title ); ?></div>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
					?>
				</div>

				<?php if ( $stat_num ) : ?>
					<div class="states">
						<ul class="flex">
							<li class="flex">
								<div class="numb valign">
									<h1><?php echo esc_html( $stat_num ); ?></h1>
								</div>
								<div class="text valign">
									<p><?php echo wp_kses_post( $stat_lbl ); ?></p>
								</div>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $portraits ) ) : ?>
				<div class="col-lg-4 col-md-6 animate-box" data-animate-effect="fadeInUp">
					<?php foreach ( $portraits as $portrait ) : ?>
						<?php $img = arkan_image_url( isset( $portrait['portrait_image'] ) ? $portrait['portrait_image'] : '', 'arkan-team' ); ?>
						<?php if ( $img ) : ?>
							<div class="wrap">
								<div class="con">
									<img src="<?php echo esc_url( $img ); ?>" class="img-fluid" alt="<?php echo esc_attr( isset( $portrait['portrait_name'] ) ? $portrait['portrait_name'] : '' ); ?>">
									<?php if ( ! empty( $portrait['portrait_name'] ) ) : ?>
										<div class="info">
											<h4 class="name"><?php echo esc_html( $portrait['portrait_name'] ); ?></h4>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( ! empty( $skills ) ) : ?>
			<div class="row">
				<div class="col-lg-8 offset-lg-4 col-md-12 mt-5 animate-box" data-animate-effect="fadeInUp">
					<div class="row">
						<div class="col-md-12 skills">
							<?php foreach ( $skills as $skill ) : ?>
								<?php $value = isset( $skill['skill_value'] ) ? (int) $skill['skill_value'] : 0; ?>
								<div class="skill-item">
									<h6><?php echo esc_html( isset( $skill['skill_title'] ) ? $skill['skill_title'] : '' ); ?> <i>(<?php echo esc_html( $value ); ?>%)</i></h6>
									<div class="skill-progress">
										<div class="progres" data-value="<?php echo esc_attr( $value ); ?>%"></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php
/* ------------------------------------------------------------------ Team */
if ( arkan_field( 'about_team_enabled', $page_id, true ) ) :
	$team = arkan_query( 'team', -1 );
	?>
	<section class="team section-padding">
		<div class="container">
			<?php
			arkan_section_heading(
				array(
					'subtitle'  => arkan_field( 'about_team_subtitle', $page_id ),
					'title'     => arkan_field( 'about_team_title', $page_id ),
					'text'      => arkan_field( 'about_team_text', $page_id ),
					'row_class' => 'mb-4',
				)
			);
			?>
			<div class="row">
				<?php if ( $team->have_posts() ) : ?>
					<div class="col-md-12 owl-carousel owl-theme">
						<?php
						while ( $team->have_posts() ) :
							$team->the_post();
							arkan_team_card();
						endwhile;
						?>
					</div>
					<?php
				else :
					arkan_empty_notice( __( 'team members', 'arkan' ), __( 'Team', 'arkan' ), 'col-md-12' );
				endif;
				?>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endif;
