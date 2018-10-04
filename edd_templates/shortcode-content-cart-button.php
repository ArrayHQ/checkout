<?php
/**
 * Only show this if the product has variable pricing.
 * All other purchase buttons are handled by the theme.
 */
?>


	<div class="edd_download_buy_button">
		<div class="edd_download_buy_button_inside">
			<?php echo edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ); ?>
		</div>
	</div>