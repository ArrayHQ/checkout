jQuery(document).ready(function($) {

	function runChosen() {
		var found = $("#widgets-right select[name^='widget-checkout-icon-text-widget']");
		found.each( function( index,value ) {
			$(value).chosen({
				width: "100%",
			}).change( function() {
				$selectedIcon = $(this).closest('.widget-content');
				$selectedIcon.find('.checkout-icon-placeholder').removeClass().addClass('checkout-icon-placeholder fa '+$(this).val());
			});
		});
	}
	runChosen();

	function current_icon() {
		$( ".chosen-container-single .chosen-single span" ).each(function(){

			var current_icon = $( this ).html();
			//console.log( current_icon );

			$(this).parent().find( '.checkout-icon-placeholder' ).removeClass().addClass('checkout-icon-placeholder fa fa-'+current_icon);

			$( this ).on('change',function(){
				alert("changed");
			});
		});
	}
	current_icon();

	$(document).on('widget-updated widget-added', function() {
		runChosen();
		current_icon();
	});
});