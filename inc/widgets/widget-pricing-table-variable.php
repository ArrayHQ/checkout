<?php
/**
 * Pricing Table Widget - Variable Pricing Options
 *
 * Displays a pricing table using the variable pricing options of a download.
 *
 * @author John Parris
 * @since 1.0.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit();


/**
 * Register the Pricing Table - Variable Pricing Options widget
 */
function checkout_pricing_table_variable_widget() {
	register_widget( 'Pricing_Table_Variable_Options' );
}
add_action( 'widgets_init', 'checkout_pricing_table_variable_widget' );



/**
 * Variable Pricing Options Widget Class
 *
 * @since 1.0.0
 */
class Pricing_Table_Variable_Options extends WP_Widget {

	/**
	 * Holds widget settings defaults. Populated in __construct().
	 *
	 * @var array
	 */
	protected $defaults;



	/**
	 * Constructor. Set default options and create widget.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// AJAX hook for fetching product price variations
		add_action( 'wp_ajax_checkout_check_for_download_price_variations', array( $this, 'checkout_check_for_download_price_variations' ) );

		$this->defaults = array(
			'download'        => '',
			'download_id'     => 'none',
			'price_variation' => '',
			'orderby'         => 'title',
			'order'           => 'ASC',
			'footnotes'       => false,
		);

		$widget_ops = array(
			'classname'   => 'pricing-table-variable-options',
			'description' => __( 'Displays a pricing table using the variable pricing options of the selected download.', 'checkout' ),
		);

		$control_ops = array(
			'id_base' => 'pricing-table-variable-options',
			'width'   => 380,
			'height'  => 350,
		);

		parent::__construct( 'pricing-table-variable-options', __( 'Checkout: Variable Pricing Table', 'checkout' ), $widget_ops, $control_ops );

	}



	/**
	 * Display the widget content.
	 *
	 * @since 1.0
	 *
	 * @param array $args Display arguments including before_widget and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Return early if we have no download ID or no variable prices
		if( ! isset( $instance['download'] ) || ! edd_has_variable_prices( absint( $instance['download'] ) ) ) {
			return;
		}

		// Set the download ID
		$download_id = absint( $instance['download'] );

		// Get the variable price options
		$prices = edd_get_variable_prices( $download_id );

		// Get the featured price option
		$featured = isset( $instance['price_variation'] ) && ! empty( $instance['price_variation'] ) ? absint( $instance['price_variation'] ) : false;

		echo $args['before_widget'];
 ?>

		<div class="<?php if( $featured ) { echo 'featured-price'; } ?>">
			<div class="pricing-table-wrap">
				<?php foreach( $prices as $key => $price ) : ?>
					<div itemscope class="pricing-table <?php if( $key == $featured ) { echo 'featured'; } ?>">
						<div class="pricing-table-top">
							<div class="pricing-table-price"><?php echo apply_filters( 'edd_download_price', edd_sanitize_amount( $price['amount'] ), $download_id, $key ); ?></div>
							<div class="pricing-table-price-desc"><?php echo $price['name']; ?></div>
						</div>
						<div class="pricing-table-features">
							<div class="download-details download-aside">
								<div class="download-features" itemprop="itemCondition">
									<ul>
									<?php

										$list_items = checkout_edd_download_details_list_items( $download_id, $key );

										if( $list_items ) :

											foreach( $list_items as $item ) :
												echo '<li class="price-feature">' . $item . '</li>';
											endforeach;

										endif;

										$list_item_all_prices = checkout_edd_download_details_list_items( $download_id, 'all' );

										if( $list_item_all_prices ) :

											foreach( $list_item_all_prices as $list_item ) :
												echo '<li class="all-prices-feature">' . $list_item . '</li>';
											endforeach;

										endif;
										?>
										</ul>
									</div>
								</div>
							<a class="button" href="<?php echo edd_get_checkout_uri(); ?>?edd_action=add_to_cart&amp;download_id=<?php echo absint( $download_id ); ?>&amp;edd_options%5Bprice_id%5D=<?php echo absint( $key ); ?>" title="<?php echo esc_attr( $price['name'] ); ?>">
								<?php
									$default_button_text = edd_get_option( 'buy_now_text', __( 'Buy Now', 'checkout' ) );
									echo $default_button_text;
								?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if( $instance['footnotes'] ) : ?>
				<div class="pricing-table-footnotes">
					<?php echo $instance['footnotes']; ?>
				</div>
			<?php endif; ?>
		</div><!-- .pricing-section -->

		<?php echo $args['after_widget'];
	}



	/**
	 * Updates an instance.
	 *
	 * This function checks that $new_instance is set correctly.
	 * The newly calculated value of $instance is returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$instance                    = $old_instance;
		$instance['download']        = strip_tags( $new_instance['download'] );
		$instance['price_variation'] = strip_tags( $new_instance['price_variation'] );
		$instance['footnotes']       = esc_html( $new_instance['footnotes'] );

		return $instance;
	}



	/**
	 * Echo the settings update form.
	 *
	 * @since 1.0
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		// Merge the current settings with the defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>

		<div class="variable-price-table-widget-column">

			<div class="variable-price-table-widget-column-box variable-price-table-widget-column-box-top">

				<?php
				$args = array(
					'post_type'        => 'download',
					'post_status'      => 'publish',
					'posts_per_page'   => -1,
					'orderby'          => 'title',
					'order'            => 'ASC',
					'meta_query'       => array(
						array(
							'key'     => '_variable_pricing',
							'value'   => '1',
							'compare' => '=',
						),
					),
				);
				$downloads = get_posts( $args ); ?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_name( 'download' ) ); ?>">
						<?php printf( __( 'Select a %1$s: ', 'checkout' ), edd_get_label_singular() ); ?>
					</label>

					<select class="checkout_download_select" id="<?php echo esc_attr( $this->get_field_id( 'download' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'download' ) ); ?>">
						<option value="none"><?php _e( 'None', 'checkout' ); ?></option>

						<?php foreach( $downloads as $download ) { ?>
							<option <?php selected( absint( $instance['download'] ), $download->ID ); ?> value="<?php echo esc_attr( $download->ID ); ?>"><?php echo esc_html( $download->post_title ); ?></option>
						<?php } ?>

					</select>
				</p>

				<p class="price-variation-wrapper">
					<label for="<?php echo esc_attr( $this->get_field_id( 'price_variation' ) ); ?>">
						<?php _e( 'Select a price option to feature:', 'checkout' ); ?><br>
					</label>
					<?php if( ! $instance['price_variation'] || empty( $instance['price_variation'] )  || 'none' == $instance['price_variation'] ) : ?>

						<select class="checkout_price_options_select" id="<?php echo esc_attr( $this->get_field_id( 'price_variation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'price_variation' ) ); ?>">
							<option value="none"><?php _e( 'None', 'checkout' ); ?></option>
						</select>
					<?php else : ?>
						<select class="checkout_price_options_select" id="<?php echo esc_attr( $this->get_field_id( 'price_variation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'price_variation' ) ); ?>">
							<option value="none"><?php _e( 'None', 'checkout' ); ?></option>
							<?php $variable_prices = edd_get_variable_prices( $instance['download'] );
							foreach( $variable_prices as $key => $price ) { ?>
								<option <?php selected( $instance['price_variation'], $key ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $price['name'] ); ?></option>
							<?php } ?>
						</select>
					<?php endif; ?>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'footnotes' ) ); ?>">
						<?php _e( 'Footnotes:', 'checkout' ); ?>
					</label>
					<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'footnotes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'footnotes' ) ); ?>" value="<?php echo esc_attr( $instance['footnotes'] ); ?>" rows="8" cols="20"><?php echo $instance['footnotes']; ?></textarea>
				</p>

			</div>

		<script>
			jQuery(document).ready(function ($) {
				$(".checkout_download_select").on( 'change', function() {
					var $this = $(this), download_id = $this.val();

					if( parseInt(download_id) > 0 ) {
						var postData = {
							action: 'checkout_check_for_download_price_variations',
							download_id: download_id
						};

						$.ajax({
							type: "POST",
							data: postData,
							url: ajaxurl,
							success: function (response) {
								$('.checkout_price_options_select').remove();
								$(response).appendTo( '.price-variation-wrapper' );
							}
						}).fail(function (data) {
							if ( window.console && window.console.log ) {
								console.log( data );
							}
						});
					}
				});
			});
		</script>

		</div>

		<?php

	}


	function checkout_check_for_download_price_variations() {
		if( ! current_user_can( 'edit_products' ) ) {
			die( '-1' );
		}

		$download_id = intval( $_POST['download_id'] );
		$download    = get_post( $download_id );

		if( 'download' != $download->post_type ) {
			die( '-2' );
		}

		if ( edd_has_variable_prices( $download_id ) ) {
			$variable_prices = edd_get_variable_prices( $download_id );


			$ajax_response = '<select class="checkout_price_options_select" name="' . esc_attr( $this->get_field_name( 'price_variation' ) ) .'">';
			$ajax_response.= '<option value="none">' . __( 'None', 'checkout' ) . '</option>';
			if ( $variable_prices ) {
					foreach ( $variable_prices as $key => $price ) {
						$ajax_response .= '<option value="' . esc_attr( $key ) . '">' . esc_html( $price['name'] )  . '</option>';
					}
				$ajax_response .= '</select>';
				echo $ajax_response;
			}

		}

		edd_die();
	}


}
