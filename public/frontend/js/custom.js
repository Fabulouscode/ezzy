(function ($) {
    "use strict";
    jQuery(document).on("ready", function () {
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 200) {
                $(".navbar-area").addClass("is-sticky");
            } else {
                $(".navbar-area").removeClass("is-sticky");
            }
        });
        $(".navbar-nav li a").on("click", function (e) {
            var anchor = $(this);
            $("html, body")
                .stop()
                .animate({ scrollTop: $(anchor.attr("href")).offset().top - 70 }, 1500);
            e.preventDefault();
        });
        $('.download-appbtn').on("click", function (e) {
            var anchor = $(this);
            $("html, body")
                .stop()
                .animate({ scrollTop: $(anchor.attr("href")).offset().top - 70 }, 1500);
            e.preventDefault();
        });

        $(document).on("click", ".navbar-collapse.show", function (e) {
            if ($(e.target).is("a") && $(e.target).attr("class") != "dropdown-toggle") {
                $(this).collapse("hide");
            }
        });
        $(".screenshot-slider").owlCarousel({ loop: true, margin: 30, nav: false, dots: true, center: true, 
            responsive: { 0: { items: 1 },  576: { items: 2 }, 768: { items: 3 }, 992: { items: 4 }, 1200: { items: 5 } } });
        $(".how-use-slider").owlCarousel({ loop: true, margin: 10, nav: false, dots: true, center: true,  responsive: { 0: { items: 1 }, 600: { items: 3 }, 992: { items: 1 } } });
        $(".testimonial-slider").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
            dots: false,
            items: 1,
            
            responsive: { 0: { items: 1 }, 600: { items: 2 }, 992: { items: 1 } },
        });
        $(".popup-vimeo").magnificPopup({ disableOn: 300, type: "iframe", mainClass: "mfp-fade", removalDelay: 160, preloader: false, fixedContentPos: false });
        $(".company-slider").owlCarousel({ loop: true, margin: 10, nav: false, dots: false,  autoplay: true, autolayTimeout: 1500, responsive: { 0: { items: 2 }, 600: { items: 3 }, 1000: { items: 5 } } });
        $(".testimonial-slider-area").owlCarousel({ loop: true, margin: 10, nav: true, navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"], dots: false,  items: 1 });
        $(".counter").counterUp({ delay: 15, time: 2000 });
        $(".case-study-slider").owlCarousel({ loop: true, margin: 30, nav: false, dots: true,  stagePadding: 20, autoplayHoverPause: true, responsive: { 0: { items: 1 }, 600: { items: 2 }, 1000: { items: 3 } } });
        $(".testimonial-slider-wrapper").owlCarousel({ loop: true, margin: 10, nav: true, dots: false,  items: 1 });
        $(".team-slider").owlCarousel({ loop: true, margin: 10, nav: false, dots: true,  responsive: { 0: { items: 1 }, 600: { items: 3 }, 1000: { items: 4 } } });
        $(".slider-for").slick({ slidesToShow: 1, slidesToScroll: 1, arrows: false, draggable: false, fade: true, asNavFor: ".slider-nav" });
        $(".slider-nav").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: ".slider-for",
            dots: false,
            arrows: true,
            centerMode: true,
            focusOnSelect: true,
            centerPadding: "20px",
            responsive: [
                { breakpoint: 450, settings: { dots: false, slidesToShow: 3, centerPadding: "0px" } },
                { breakpoint: 420, settings: { autoplay: true, dots: false, slidesToShow: 1, centerMode: false } },
            ],
        });
        $(window).on("load", function () {
            $(".top-btn").fadeOut();
        });
        $(window).scroll(function () {
            if ($(this).scrollTop() != 0) {
                $(".top-btn").fadeIn();
            } else {
                $(".top-btn").fadeOut();
            }
        });
        $(".top-btn").on("click", function () {
            $("html, body").animate({ scrollTop: 0 }, 3000);
            return false;
        });
        $(".newsletter-form").validator().on("submit", function (event) {
            if (event.isDefaultPrevented()) {
                formErrorSub();
                submitMSGSub(false, "Please enter your email correctly.");
            } else {
                event.preventDefault();
            }
        });
        function callbackFunction(resp) {
            if (resp.result === "success") {
                formSuccessSub();
            } else {
                formErrorSub();
            }
        }
        function formSuccessSub() {
            $(".newsletter-form")[0].reset();
            submitMSGSub(true, "Thank you for subscribing!");
            setTimeout(function () {
                $("#validator-newsletter").addClass("hide");
            }, 4000);
        }
        function formErrorSub() {
            $(".newsletter-form").addClass("animated shake");
            setTimeout(function () {
                $(".newsletter-form").removeClass("animated shake");
            }, 1000);
        }
        function submitMSGSub(valid, msg) {
            if (valid) {
                var msgClasses = "validation-success";
            } else {
                var msgClasses = "validation-danger";
            }
            $("#validator-newsletter").removeClass().addClass(msgClasses).text(msg);
        }

        $("#Container").mixItUp();
        $(".popup-gallery").magnificPopup({ type: "image" });
        new WOW().init();
        $(window).on("load", function () {
            $(".loader-content").fadeOut(1000);
        });
    });
})(jQuery);
