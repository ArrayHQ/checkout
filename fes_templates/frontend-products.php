<?php
/**
 * Custom Vendor Product management page
 *
 * @package Checkout
 * @since Checkout 1.0
 */
?>

<?php global $products; ?>

<?php
	// Check which view to show products
	if( isset( $_COOKIE['product_view'] ) && $_COOKIE['product_view'] == 'list' ) {
		$product_view = 'list-view';
		$active_view  = 'list-active';

	} else if( isset( $_COOKIE['product_view'] ) && $_COOKIE['product_view'] == 'grid' ) {
		$product_view = 'grid-view';
		$active_view  = 'grid-active';

	} else {
		$product_view = 'list-view';
		$active_view = 'list-active';
	}
?>

<div class="fes-product-bar">
	<?php echo EDD_FES()->dashboard->product_list_status_bar(); ?>

	<div class="fes-product-view <?php echo $active_view; ?>">
		<div class="fes-product-grid"><i class="fa fa-th-large"></i> <?php _e( 'Grid View', 'checkout' ); ?></div>
		<div class="fes-product-list"><i class="fa fa-th-list"></i> <?php _e( 'List View', 'checkout' ); ?></div>
	</div>
</div>

<div class="fes-product-wrap <?php echo $product_view; ?>">
	<?php
	if( count( $products ) > 0 ) {
		foreach( $products as $product ) : ?>

			<div class="fes-product equal">

				<?php if ( has_post_thumbnail( $product->ID ) ) { ?>
					<div class="fes-product-image">
						<?php echo get_the_post_thumbnail(  $product->ID , 'portfolio-thumb' ); ?>
					</div>

				<?php } else if( has_shortcode( $product->post_content, 'gallery' ) ) {

						// Grab the first image from the gallery if we have one
						$gallery = get_post_gallery_images( $product->ID );

						$image_list = '<div class="fes-product-image">';

						// Grab only the first image from the gallery
						$i = 0;

						foreach( $gallery as $image ) {
							if( ++$i > 1 ) break;
							$image_list .= '<div><img src=" ' . $image . ' " /></div>';
						}

						$image_list .= '</div>';

						// Display gallery image
						echo $image_list;
					}
				?>

				<div class="fes-product-details">

					<h3><?php echo EDD_FES()->dashboard->product_list_title( $product->ID ); ?></h3>

					<ul>
						<li>
							<span class="fes-detail-list">
								<?php _e( 'Price:', 'checkout' ); ?>
							</span>
							<?php echo EDD_FES()->dashboard->product_list_price( $product->ID ); ?>
						</li>

						<li>
							<span class="fes-detail-list">
								<?php _e( 'Purchases:', 'checkout' ); ?>
							</span>
							<?php echo EDD_FES()->dashboard->product_list_sales_esc( $product->ID ); ?>
						</li>

						<li class="product-status">
							<?php echo EDD_FES()->dashboard->product_list_date( $product->ID ); ?>
						</li>
					</ul>

					<div class="fes-product-edit">
						<div class="fes-product-status">
							<?php echo EDD_FES()->dashboard->product_list_status( $product->ID ); ?>
						</div>

						<div class="fes-product-actions">
							<?php EDD_FES()->dashboard->product_list_actions( $product->ID ); ?></div>
					</div>
				</div><!-- .fes-product-details -->
			</div><!-- .fes-product -->

		<?php
		endforeach;
	} else {
		// No products found
		echo '<div class="fes-product"><div class="fes-product-details no-product">'. sprintf( __('No %s found','checkout'), EDD_FES()->helper->get_product_constant_name( $plural = true, $uppercase = false ) ).'</div></div>';
	} ?>


<?php EDD_FES()->dashboard->product_list_pagination(); ?>
</div>