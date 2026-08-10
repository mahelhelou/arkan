<?php
/**
 * Blog sidebar. Falls back to the design's default widgets when the
 * "Blog Sidebar" area is empty.
 *
 * @package Arkan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="col-lg-4 col-md-12 animate-box" data-animate-effect="fadeInUp">
	<div class="sidebar">

		<div class="search-box">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search', 'arkan' ); ?>">
				<button type="submit" class="search-submit"><span class="icon ti-search"></span></button>
			</form>
		</div>

		<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>

			<?php dynamic_sidebar( 'sidebar-blog' ); ?>

		<?php else : ?>

			<?php
			$arkan_cats = get_categories( array( 'hide_empty' => true ) );
			if ( $arkan_cats ) :
				?>
				<div class="widget category">
					<h6 class="title-widget"><?php esc_html_e( 'Categories', 'arkan' ); ?></h6>
					<ul class="rest">
						<?php foreach ( $arkan_cats as $arkan_cat ) : ?>
							<li>
								<span><a href="<?php echo esc_url( get_category_link( $arkan_cat->term_id ) ); ?>"><?php echo esc_html( $arkan_cat->name ); ?></a></span>
								<span class="ml-auto"><?php echo esc_html( arkan_pad( $arkan_cat->count ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php
			$arkan_recent = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 3,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			if ( $arkan_recent->have_posts() ) :
				?>
				<div class="widget last-post-thum">
					<h6 class="title-widget"><?php esc_html_e( 'Latest Posts', 'arkan' ); ?></h6>
					<?php
					while ( $arkan_recent->have_posts() ) :
						$arkan_recent->the_post();
						?>
						<div class="item">
							<div class="valign">
								<div class="img">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php echo esc_url( arkan_post_image_url( get_the_ID(), 'arkan-thumb', 'images/blog/1.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>">
									</a>
								</div>
							</div>
							<div class="cont">
								<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
								<span><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date() ); ?></a></span>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php
			$arkan_tags = get_tags( array( 'number' => 12 ) );
			if ( $arkan_tags ) :
				?>
				<div class="widget tags">
					<h6 class="title-widget"><?php esc_html_e( 'Tags', 'arkan' ); ?></h6>
					<div class="bt">
						<?php foreach ( $arkan_tags as $arkan_tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $arkan_tag->term_id ) ); ?>"><?php echo esc_html( $arkan_tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

		<?php endif; ?>

	</div>
</div>
