<?php
/**
 * The template for displaying 404 - Page Not Found.
 *
 * @package Checkout
 * @since Checkout 1.0
 */

get_header(); ?>

		<div id="main" class="site-main">
			<div id="primary" class="content-area">
				<div id="content" class="site-content container" role="main">
					<article class="post">
						<div class="post-content">
							<div class="post-text">
								<p><?php _e( 'It looks like nothing was found at this location. Try using the navigation menu or the search box to locate the page you were looking for.', 'checkout' ); ?></p>

								<?php get_search_form(); ?>

								<hr/>

								<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

								<hr/>

								<div class="widget widget_categories">
									<h2 class="widgettitle"><?php _e( 'Most Used Categories', 'checkout' ); ?></h2>
									<ul>
									<?php
										wp_list_categories( array(
											'orderby'    => 'count',
											'order'      => 'DESC',
											'show_count' => 1,
											'title_li'   => '',
											'number'     => 10,
										) );
									?>
									</ul>
								</div><!-- .widget -->

								<hr/>

								<?php
									$archive_content = '<p>' . __( 'Try looking in the monthly archives.', 'checkout' ) . '</p>';
									the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
								?>
							</div><!-- .post-text -->
						</div><!-- .post-content -->
					</article><!-- .post -->
				</div><!-- #content .site-content -->
			</div><!-- #primary .content-area -->
		</div><!-- #main .site-main -->

<?php get_footer(); ?>