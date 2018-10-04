(function($) {

    $(document).ready(function() {

        // Escape key closes pricing on downloads
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                $(".edd_download_buy_button").removeClass("show-pricing");
                $(".edd_price").removeClass("edd-price-hide");
                $(".close-pricing").removeClass("close-pricing-show");
            }
        });


        // Price detail toggle
        $(document).on('click', '.download-price-toggle a', function(e) {
            $(this).each(function() {

                // Get the height of the hidden slide up area
                var hiddenHeight = $(this).closest(".edd-download,.edd_download").find(".edd_download_buy_button_inside").outerHeight(true);

                // Get the height of the download column
                var availableHeight = $(this).closest(".edd-download,.edd_download").outerHeight(true);

                // If there's enough room, slide up the purchase button, otherwise link directly to the download
                if (hiddenHeight < availableHeight) {
                    e.preventDefault();

                    // Close all price overlays
                    $(".edd_download_buy_button").removeClass("show-pricing");

                    // Reset pricing show/hide buttons
                    $(".edd_price").removeClass("edd-price-hide");
                    $(".close-pricing").removeClass("close-pricing-show");

                    // Hide the price upon clicking
                    $(this).find(".edd_price").addClass("edd-price-hide");

                    // Show the Close Pricing button
                    $(this).parent().find(".close-pricing").addClass("close-pricing-show");

                    // Show the price overlay
                    $(this).closest(".entry-header,.edd_download_inner").find(".edd_download_buy_button").addClass("show-pricing");

                    return false;
                } else {
                    return true;
                }
            });
        });


        // Close price detail
        $(".download-wrapper .close-pricing").click(function() {
            // Hide the price overlay
            $(".edd_download_buy_button").removeClass("show-pricing");

            // Reset pricing show/hide buttons
            $(".edd_price").removeClass("edd-price-hide");
            $(".close-pricing").removeClass("close-pricing-show");

            return false;
        });


        // Sticky sidebar for single download pages
        $(window).on("resize load", function() {
            var current_width = $(window).width();

            // Above tablet size
            if (current_width > 768) {
                // Sticky sidebar on download pages
                if (checkout_load_js_vars.load_sticky) {

                    // Calculate the offset due to padding on the purchase box
                    var paddingTop = $(".purchase-box .download-aside:first-child").css("padding-top");
                    var paddingTopInteger = parseInt(paddingTop, 10);

                    // Calculate distance to bottom of screen
                    var length = $("#secondary").height() - $("#sticker").height() + $("#secondary").offset().top;

                    // While we're scrolling let's do this stuff
                    $(window).scroll(function() {

                        // Calculate scroll positions
                        var scroll = $(this).scrollTop() + paddingTopInteger;

                        // Get height of sticky sidebar
                        var height = $("#sticker").height();

                        // Get width of sticky sidebar
                        var width = $("#secondary").width();

                        // Get height of viewport
                        viewportHeight = $(window).height();

                        if (scroll < $("#secondary").offset().top) {
                            // Initial sidebar position
                            $("#sticker").css({
                                'position': 'absolute',
                                'top': '0',
                                'width': '100%'
                            });

                        } else if (height > viewportHeight) {
                            // If the purchase box is taller than the viewport, don't stick the sidebar
                            $("#sticker").css({
                                'position': 'relative',
                                'width': '100%'
                            });
                        } else if (scroll > length) {
                            // When we hit the bottom of the page, remove the fixed positioning
                            $("#sticker").css({
                                'position': 'absolute',
                                'bottom': "0",
                                'top': 'auto',
                                'width': '100%'
                            });
                        } else {
                            // Make the sidebar fixed while scrolling
                            $("#sticker").css({
                                'position': 'fixed',
                                'top': paddingTopInteger,
                                'height': height + 'px',
                                'width': width + 'px'
                            });

                            // Compensate for WP admin bar
                            $(".admin-bar #sticker").css({
                                'top': paddingTopInteger,
                            });
                        }
                    });
                }
                // Move purchase box back to sidebar on desktop
                $("#primary").find("#secondary").prependTo(".sticky-container");
            } else {
                // Move purchase box below post on tablet and mobile
                $("#secondary").insertAfter(".post");
            }
        });


        // Move Related Products below commments
        $(".single #edd-rp-single-wrapper").appendTo("#page");

        $(".single .edd-rp-item .edd_purchase_submit_wrapper").each(function() {
            $(this).find(".edd_download_quantity_wrapper").insertBefore(this);
        });


        // Move the tax details after the purchase button
        $(".edd_purchase_submit_wrapper").each(function() {
            $(this).find(".edd_purchase_tax_rate").insertAfter(this);
        });


        // Hide EDD page navs if empty
        if ($.trim($("#edd_download_pagination").html()) == '') {
            $("#edd_download_pagination").hide();
        }


        // Hide EDD clear divs on vendor archive
        $('.vendor-archive .edd_downloads_list div[style*=both]').remove();


        // If there are three prices, reorder the price tables to show featured price in center
        $(window).on("resize load", function() {
            var current_width = $(window).width();

            if ($(".featured-price .pricing-table-wrap").children().length === 3) {

                var $active = $(".pricing-table.featured").detach().addClass("featured"),
                    $lis = $(".pricing-table");

                if (current_width > 768) {
                    $active.insertBefore($lis.eq(Math.floor($lis.length / 2)));
                } else {
                    $active.insertBefore(".pricing-table:first-child");
                }

            }

            // Fade in the price tables after reorder
            $(".pricing-table").fadeTo(200, 1);

        });


        // Add column count fix for pricing table on Homepage Widget template
        $(".widget-section .pricing-table-variable-options").each(function() {
            $(this).after('<aside class="column price-column-fix column-shiv"></aside><aside class="column price-column-fix"></aside>');
        });


        // Remove one of the column count fixes for tablet layout
        $(window).on("resize load", function() {
            var current_width = $(window).width();

            if (current_width < 960) {
                $(".widget-section .pricing-table-variable-options + .column-shiv").each(function() {
                    $(this).remove();
                });
            }
        });


        // Toggle grid view in vendor dash
        $(".fes-product-grid").click(function() {

            // Set grid view cookie
            $.cookie("product_view", "grid");

            // Add active class to view button
            $(this).parent().removeClass("list-active").addClass("grid-active");;

            // Toggle view classes
            $(".fes-product-wrap").removeClass("list-view");
            $(".fes-product-wrap").addClass("grid-view");
        });

        // Toggle list view in vendor dash
        $(".fes-product-list").click(function() {

            // Set grid view cookie
            $.cookie("product_view", "list");

            // Add active class to view button
            $(this).parent().removeClass("grid-active").addClass("list-active");

            // Toggle view classes
            $(".fes-product-wrap").removeClass("grid-view");
            $(".fes-product-wrap").addClass("list-view");
        });


        // Show the added to cart alert
        $(".edd_purchase_submit_wrapper .edd-add-to-cart").each(function() {
            $(this).on("click", function() {

                // Show the added to cart alert on submit
                $(this).parent().find(".edd-cart-added-alert").show();

                // Hide the added to cart alert after a bit
                $(this).parent().find(".edd-cart-ajax-alert").addClass("added").delay(1200).fadeTo(200, 0);

            });
        });

    });

})(jQuery);
