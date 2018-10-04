<?php
/**
 * Icon Text Widget class
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit();


/**
 * Register the Icon Text widget
 */
function checkout_icon_text_widget() {
	register_widget( 'Checkout_Icon_Text_Widget' );
}
add_action( 'widgets_init', 'checkout_icon_text_widget' );


function checkout_icon_widget_scripts( $hook ) {

	if ( 'widgets.php' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'checkout-chosen', get_template_directory_uri() . "/inc/widgets/icon-select/chosen.css", array(), '1.3.0', 'screen' );
	wp_enqueue_script( 'checkout-chosen-script', get_template_directory_uri() . "/inc/widgets/icon-select/chosen.jquery.js", array( 'jquery' ), '1.3.0' );
	wp_enqueue_script( 'checkout-admin-widget', get_template_directory_uri() . "/inc/widgets/icon-select/icon-text.js", array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'checkout_icon_widget_scripts' );
add_action( 'customize_preview_init', 'checkout_icon_widget_scripts' );


/**
 * Returns an array of all Font Awesome names and values
 */
function checkout_fontawesome( $path ) {
	$css = wp_remote_fopen( $path );

	$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';

	preg_match_all( $pattern, $css, $matches, PREG_SET_ORDER );

	$icons = array();
	foreach( $matches as $match ) {
		$icons[$match[1]] = $match[2];
	}
	return $icons;
}

/**
 * The Icon Text Widget class
 */
class Checkout_Icon_Text_Widget extends WP_Widget {


	public function __construct() {

		$widget_ops = array(
			'classname' => 'widget-icon-text',
			'description' => __( 'Display an icon with custom text or HTML.', 'checkout' )
		);

		$control_ops = array(
			'id_base' => 'checkout-icon-text-widget',
		);
		parent::__construct( 'checkout-icon-text-widget', __( 'Checkout: Text and Icon', 'checkout' ), $widget_ops, $control_ops );
	}


	public function widget( $args, $instance ) {

		/** This filter is documented in wp-inc/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

		echo $args['before_widget']; ?>
			<div class="icon-text-widget">
				<i class="fa <?php echo $instance['icon_select']; ?>"></i>
			</div>
		<?php

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
		<?php
		echo $args['after_widget'];
	}


	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] =  $new_instance['text'];

		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) ); // wp_filter_post_kses() expects slashed

		}

		$instance['filter'] = isset( $new_instance['filter'] );
		$instance['icon_select'] = isset( $new_instance['icon_select'] ) ? $new_instance['icon_select'] : '';

		return $instance;
	}


	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'icon_select' => '' ) );
		$title = strip_tags( $instance['title'] );
		$text = esc_textarea( $instance['text'] );
		$icon_select = $instance['icon_select']; ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'checkout' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'icon_select' ); ?>"><?php _e( 'Select an icon:', 'checkout' ); ?></label>
			<select class="widefat icon-select" name="<?php echo esc_attr( $this->get_field_name( 'icon_select' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'icon_select' ) ); ?>" data-placeholder="<?php _e( 'Select an icon', 'checkout' ); ?>" style="font-family: FontAwesome">
				<?php
				$fontawesome = checkout_fontawesome( get_template_directory_uri() . '/inc/fonts/fontawesome/css/font-awesome.css' );
				ksort( $fontawesome );
				foreach( $fontawesome as $name => $value ) {
					$value = '&#x' . $value .';';
					$value = str_replace('\\', '', $value ); ?>
					<option class="fa <?php echo esc_attr( $name ); ?>" data-value="<?php echo $value; ?>" value="<?php echo esc_attr( $name ); ?>" <?php selected( $icon_select, $name ); ?> data-icon="<?php echo esc_attr( $name ); ?>"><?php echo str_replace( 'fa-', '', $name ); ?></option>
				<?php } ?>
			</select>
		</p>

		<p><?php _e( 'Currently selected icon', 'checkout' ); ?></p>

		<p><i class="checkout-icon-placeholder fa <?php echo $icon_select; ?>" style="font-size: 50px;"></i></p>

		<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:', 'checkout' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Automatically add paragraphs', 'checkout' ); ?></label></p>

	<?php
	}

}