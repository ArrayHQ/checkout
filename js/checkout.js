(function($) {

    $(document).ready(function() {

        // Initialize responsive slides on columns and posts
        var $slides = $(".post .rslides, .edd_download .rslides");

        $slides.responsiveSlides({
            auto: false,
            nav: true,
            speed: 200,
        });


        // Add touch support to responsive slides
        $(".post .rslides").each(function() {
            $(this).swipe({
                swipeLeft: function() {
                    $(this).parent().find(".rslides_nav.prev").click();
                },
                swipeRight: function() {
                    $(this).parent().find(".rslides_nav.next").click();
                }
            });
        });


        // Initialize responsive slides for product slider
        $(".split-section .rslides").responsiveSlides({
            auto: false,
            nav: true,
            manualControls: '#product-pager',
            speed: 200,
            navContainer: ".slide-navs"
        });


        // Add touch support to responsive slides
        $(".product-slides li").each(function() {
            $(this).swipe({
                swipeLeft: function() {
                    $(this).closest(".split-section").find(".rslides_nav.prev").click();
                },
                swipeRight: function() {
                    $(this).closest(".split-section").find(".rslides_nav.next").click();
                }
            });
        });


        // Add active class to thumbnail paging
        $(".rslides_tabs li:first-child").addClass("rslides_here");


        // Add class to WP gallery attachment links
        $('.gallery-item a[href*="attachment"]').each(function() {
            $(this).closest(".gallery").addClass("attachment-link");
        });


        // Add lightbox gallery to default WP gallery
        $(".gallery-item").each(function() {
            // Get url of image for data src
            var dataSrc = $(this).find("a").attr("href");

            // Get the image caption
            var caption = $(this).find(".gallery-caption").html();

            // Add data src attribute
            $(this).attr('data-src', dataSrc);

            // Add caption attribute
            if (typeof caption !== 'undefined') {
                $(this).attr('data-sub-html', '<h3>' + caption + '</h3>');
            }
        });


        // Initialize the lightbox gallery
        $(".post [id^=lightGallery], .post-text [data-link='file'], .edd_download [id^=lightGallery]").each(function() {
            $(this).lightGallery({
                showThumbByDefault: true,
                mode: 'fade',
                speed: 250,
                thumbMargin: 10,
                thumbWidth: 125,
                loop: true,
                onOpen: galleryHeight
            });
        });


        // Add a little padding to the lightbox gallery
        function galleryHeight() {

            // Get the height of the caption area
            setTimeout(function() {
                // Get height of thumb navs
                var thumbHeight = $(".thumb_cont").height();

                // Add some padding to the bottom to compensate for the thumbs
                $(".lightGallery-slide").css({
                    'padding-bottom': thumbHeight + 'px'
                });

                $("#lightGallery-Gallery.open #lightGallery-slider .lightGallery-slide").fadeIn(200);

            }, 100);

        }
        galleryHeight();


        // Equalize columns
        function equalHeight() {
            // Equalize column heights
            $(".equal, .page-template-template-vendor .edd_download, .vendor-archive .edd_download, .footer-widget").matchHeight();
        }
        equalHeight();


        // Add custom styling to checkboxes and radios
        $("select:not(.multiselect,.search-select)").each(function() {
            $(this).wrap("<div class='select'></div>");
        });


        // Center main navigation drop down
        $(".main-navigation li").each(function() {
            if ($(this).find("ul").length > 0) {
                var parent_width = $(this).outerWidth(true);
                var child_width = $(this).find("ul").outerWidth(true);
                var new_width = parseInt((child_width - parent_width) / 2);
                $(this).find("ul").css('margin-left', -new_width + "px");
            }
        });


        // Header Menu Toggle
        $(".menu-toggle").click(function() {
            $(".logo, .hero-title, .main-navigation, .site-header-bg-wrap").toggle();

            $(".menu-toggle span").toggle();
        });


        // Responsive mobile navigation functions
        $(window).on("resize load", function() {
            // Add class to desktop nav for toggling
            $(".main-navigation").addClass('desktop-nav');

            var current_width = $(window).width();

            // If width is below iPad size
            if (current_width < 769) {

                // Remove desktop nav class, add mobile nav class
                $(".main-navigation").removeClass('desktop-nav');
                $(".main-navigation").addClass('mobile-nav');

                // Toggle sub menus on mobile
                $(".menu").find("li.menu-item-has-children:not(.header-search)").click(function() {
                    $(this).toggleClass("show-mobile-sub active-sub-menu");
                    return false;
                });

                // Don't fire sub menu toggle if a user is trying to click the link
                $(".menu-item-has-children a, .main-navigation #s, .main-navigation #searchsubmit").click(function(e) {
                    e.stopPropagation();
                    return true;
                });

            } else {
                $(".main-navigation").addClass('desktop-nav');
                $(".main-navigation").removeClass('mobile-nav');
                $(".logo, .hero-title, .main-navigation, .site-header-bg-wrap ").show();
            }

        });


        // Detect mobile/tablet orientation
        var current_width = $(window).width();

        // Allow drop menus to be clicked on landscape tablet
        if (current_width > 600) {
            function orient() {
                if (window.orientation == 90 || window.orientation == -90) {
                    // Add landscape class to body
                    $("body").addClass("landscape");

                    // Remove desktop nav class
                    $(".main-navigation").removeClass("desktop-nav");

                    orientation = 'landscape';

                    return false;
                }
            }

            // Call orientation function on page load
            $(function(){
                orient();
            });

            // Call orientation function on orientation change */
            $(window).bind( 'orientationchange', function(e){
                orient();
            });
        }


        // Focus on header search upon click
        $(".header-search").click(function() {
            $(this).find("#s").focus();
        });


        // Add button class to EDD submits
        $("#edd_profile_editor_submit").addClass("button");


        // Move MailChimp button for styling
        var mcInputHeight = $(".site-footer #mailbag_mailchimp .mailbag-input:first-of-type input").outerHeight();

        $(".site-footer #mailbag_mailchimp .mailbag-input:last-of-type").each(function() {
            $(this).find(".button").css("height", mcInputHeight).insertAfter("#mailbag_mailchimp_email");
        });


        // Fitvids
        $(".post").fitVids();


        // Testimonial masonry
        if ((checkout_masonry_js_vars.load_masonry) === 'true') {
            $(".testimonial-section-inside").masonry();
        }

    });

})(jQuery);
