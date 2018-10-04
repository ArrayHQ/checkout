<?php
/**
 * Pricing Table Widget - Standard Download (Non-variable downloads)
 *
 * Used to display a pricing table by comparing multiple downloads
 * using multiple instances of this widget.
 *
 * @author John Parris
 * @since 1.0.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit();


/**
 * Register the Pricing Table - Variable Pricing Options widget
 */
function checkout_pricing_table_standard_widget() {
	register_widget( 'Pricing_Table_Standard' );
}
add_action( 'widgets_init', 'checkout_pricing_table_standard_widget' );


/**
 * Variable Pricing Options Widget Class
 *
 * @since 1.0.0
 */
class Pricing_Table_Standard extends WP_Widget {

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

		$this->defaults = array(
			'download'    => '',
			'download_id' => 'none',
			'featured'    => '',
			'orderby'     => 'title',
			'order'       => 'ASC',
		);

		$widget_ops = array(
			'classname'   => 'pricing-table-standard',
			'description' => __( 'Used to display a pricing table by comparing multiple downloads. Add multiple instances of this widget to the Pricing Page Widget area to build a pricing table.', 'checkout' ),
		);

		$control_ops = array(
			'id_base' => 'pricing-table-standard',
			'width'   => 380,
			'height'  => 350,
		);

		parent::__construct( 'pricing-table-standard', __( 'Checkout: Pricing Table', 'checkout' ), $widget_ops, $control_ops );

	}



	/**
	 * Display the widget content.
	 *
	 * @since 1.0
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Return early if we have no download ID or no variable prices
		if( ! isset( $instance['download'] ) ) {
			return;
		}

		// Set the download ID
		$download_id = absint( $instance['download'] );

		// Get the featured price option
		$featured = isset( $instance['featured'] ) && ! empty( $instance['featured'] ) ? 'featured' : null;

		// Set the title
		$title = get_the_title( $download_id );

		echo $args['before_widget'];
 ?>
		<div itemscope class="pricing-table <?php echo $featured; ?>">
			<div class="pricing-table-top">
				<div class="pricing-table-price">
					<?php edd_price( $download_id ); ?>
				</div>
				<div class="pricing-table-price-desc"><?php echo esc_html( $title ); ?></div>
			</div>
			<div class="pricing-table-features">
				<?php checkout_edd_download_details( $download_id ); ?>
				<a class="button" href="<?php echo edd_get_checkout_uri(); ?>?edd_action=add_to_cart&amp;download_id=<?php echo absint( $download_id ); ?>" title="<?php echo esc_attr( $title ); ?>">
					<?php
						$default_button_text = edd_get_option( 'buy_now_text', __( 'Buy Now', 'checkout' ) );
						echo $default_button_text;
					?>
				</a>
			</div>
		</div>

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

		$instance              = $old_instance;
		$instance['download']  = strip_tags( $new_instance['download'] );
		$instance['featured']  = isset( $new_instance['featured'] );

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

		<div class="standard-price-table-widget-column">

			<div class="standard-price-table-widget-column-box standard-price-table-widget-column-box-top">

				<?php
				$args = array(
					'post_type'        => 'download',
					'post_status'      => 'publish',
					'posts_per_page'   => -1,
					'orderby'          => 'title',
					'order'            => 'ASC',
					'meta_query'       => array(
						'relation' => 'OR',
						array(
							'key'     => '_variable_pricing',
							'value'   => '0',
							'compare' => '=',
						),
						array(
							'key'     => '_variable_pricing',
							'compare' => 'NOT EXISTS',
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

				<p class="featured-download-wrapper">
					<input <?php checked( $instance['featured'], true ); ?> id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" type="checkbox" />
					<label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>">
						<?php printf( __( 'Feature this %s in the pricing table?', 'checkout' ), edd_get_label_singular() ); ?>
					</label>
				</p>

			</div>

		</div>

		<?php

	}

}