<?php
/**
 * Custom meta box for EDD download details
 *
 * Some of this code was adapted from Easy Digital Downloads
 * and customized for our needs.
 *
 * @package Checkout
 * @since 1.0
 */


/**
 * Add Download Details metabox
 *
 * @since 1.0
 */
function add_meta_boxes() {
	add_meta_box( 'checkout-repeatable-fields', sprintf( __( '%1$s Details', 'checkout' ), edd_get_label_singular() ), 'checkout_download_details_box', 'download', 'normal', 'core' );
}
add_action( 'admin_init', 'add_meta_boxes' );


/**
 * Markup for the inputs
 *
 * @since 1.0
 */
function checkout_download_details_box() {
	global $post;

	$checkout_download_details = get_post_meta( $post->ID, 'checkout_download_details', true );
	$variable_pricing         = edd_has_variable_prices( $post->ID );
	$variable_display         = $variable_pricing ? '' : 'display: none;';

	wp_nonce_field( 'checkout_download_details_nonce', 'checkout_download_details_nonce' );
?>

	<p>
		<?php
		printf( __( 'Add bullet point details about this %1$s. These details are displayed in the sidebar of the %1$s and in the pricing tables widget.', 'checkout' ), strtolower( edd_get_label_singular() ) ); ?>
	</p>

	<div id="checkout_download_details" class="edd_meta_table_wrap">
		<table class="widefat edd_repeatable_table" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th style="width: 2%"></th>
					<th style="width: 70%"><?php _e( 'Bullet Point Text', 'checkout' ); ?></th>
						<th class="variable-option-assigment" style="width: 20%; <?php echo $variable_display; ?>"><?php _e( 'Price Assignment', 'checkout' ); ?></th>
					<th style="width: 3%"></th>
				</tr>
			</thead>
			<tbody class="edd-repeatables-wrap">
			<?php

			if( ! empty( $checkout_download_details ) ) :

				foreach( $checkout_download_details as $key => $value ) :
					$text         = isset( $value['text'] )         ? $value['text']         : '';
					$price_option = isset( $value['price_option'] ) ? $value['price_option'] : '';
					$index        = isset( $value['index'] )        ? $value['index']        : $key;
					$args         = apply_filters( 'checkout_download_detail_row_args', compact( 'text', 'price_option', 'index' ), $value );
					?>
					<tr class="edd_variable_prices_wrapper edd_repeatable_row" data-key="<?php echo esc_attr( $key ); ?>">
						<?php do_action( 'checkout_render_download_detail_row', $key, $args, $post->ID, $index ); ?>
					</tr>
				<?php
				endforeach;

			else :
		?>
				<tr class="edd_variable_prices_wrapper edd_repeatable_row">
					<?php do_action( 'checkout_render_download_detail_row', 1, array(), $post->ID, 1 ); ?>
				</tr>
			<?php
			endif;
		?>

				<tr>
					<td class="submit" colspan="4" style="float: none; clear:both; background:#fff;">
						<a class="button-secondary edd_add_repeatable" style="margin: 6px 0;"><?php _e( 'Add New Detail', 'checkout' ); ?></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
}

/**
 * Save download details
 *
 * @since 1.0
 */
function checkout_save_download_details( $post_id ) {

	if ( ! isset( $_POST['checkout_download_details_nonce'] ) || ! wp_verify_nonce( $_POST['checkout_download_details_nonce'], 'checkout_download_details_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Get existing download details
	$existing = get_post_meta( $post_id, 'checkout_download_details' );

	// Get the submitted download details
	$download_details = isset( $_POST['download_details'] ) ? $_POST['download_details'] : '';

	if( 1 == count( $download_details ) && '' == $download_details[1]['text'] ) {
		delete_post_meta( $post_id, 'checkout_download_details' );
		return;
	}

	if( $download_details && '' == $existing ) {
		add_post_meta( $post_id, 'checkout_download_details', $download_details );

	} elseif( $download_details && $download_details != $existing ) {
		update_post_meta( $post_id, 'checkout_download_details', $download_details );

	} elseif( '' == $download_details && $existing ) {
		delete_post_meta( $post_id, 'checkout_download_details', $existing );

	}
}
add_action( 'save_post', 'checkout_save_download_details' );



function checkout_render_download_detail_row( $key, $args = array(), $post_id, $index ) {
	global $edd_options;

	$defaults = array(
		'text'          => null,
		'price_option'  => null,
		'price_options' => edd_get_variable_prices( $post_id ),
	);
	$args = wp_parse_args( $args, $defaults );
	$variable_display = $args['price_options'] ? '' : ' style="display: none;"';
?>
	<td>
		<span class="edd-draghandle-anchor dashicons dashicons-move"></span>
		<input type="hidden" name="download_details[<?php echo absint( $key ); ?>][index]" class="edd_repeatable_index" value="<?php echo absint( $key ); ?>"/>
	</td>
	<td>
		<?php echo EDD()->html->text( array(
			'name'        => 'download_details[' . $key . '][text]',
			'value'       => esc_attr( $args['text'] ),
			'placeholder' => __( 'Enter a feature about this product.', 'checkout' ),
			'class'       => 'download_details_text large-text'
		) ); ?>
	</td>

	<td class="pricing"<?php echo $variable_display; ?>>
		<?php
		$options = array();

		if( $args['price_options'] ) {
			foreach( $args['price_options'] as $price_key => $price_option ) {
				$options[ absint( $price_key ) ] = esc_html( $price_option['name'] );
			}
		}

		$price_args = array(
			'options'          => $options,
			'name'             => 'download_details[' . $key . '][price_option]',
			'id'               => 'download_details[' . $key . '][price_option]',
			'selected'         => $args['price_option'],
			'show_option_all'  => __( 'All', 'checkout' ),
			'show_option_none' => false,
			'class'            => 'edd_repeatable_condition_field',
		);

		echo EDD()->html->select( $price_args ); ?>
	</td>

	<td>
		<a href="#" class="edd_remove_repeatable edd-remove-row" data-type="price" style="background: url(<?php echo admin_url('/images/xit.gif'); ?>) no-repeat;">&times;</a>
	</td>
<?php
}
add_action( 'checkout_render_download_detail_row', 'checkout_render_download_detail_row', 10, 4 );