$(document).ready(function() {
    $('.slider-wrapper .slider-item').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        items: 1,
        autoplay: true,
        autoplayTimeout: 3000,
    });
    $('.popular-product .product-item').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        items: 4,
        dots: false,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        // autoplay: true,
        // autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 2,
                nav: false
            },
            992: {
                items: 3,
            },
            1200: {
                items: 4,
            }
        }
    });

    $('.blog-post .blog-slider-item').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        items: 3,
        dots: false,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 2,
                nav: false
            },
            992: {
                items: 3,
            }
        }
    });

    // Active current menu
    $('nav ul li a').each(function() {
        var isActive = this.pathname === location.pathname;
        $(this).toggleClass('active-menu', isActive);
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() != 0) {
            $('#btn-top').stop().fadeIn(150);
        } else {
            $('#btn-top').stop().fadeOut(150);
        }
    });
    $('#btn-top').click(function() {
        $('body,html').stop().animate({ scrollTop: 0 }, 800);
    });

    // $('.img-zoom-product').elevateZoom({
    //     easing: true,
    //     zoomType: "inner",
    //     cursor: "crosshair",

    // });

    var zoomConfig = { cursor: 'crosshair', zoomType: "inner", easing: true };
    var image = $('.change-image .change-zoom-image');
    var zoomImage = $('.img-zoom-product');
    zoomImage.elevateZoom(zoomConfig);
    image.on('click', function() {
        // Remove old instance od EZ
        $('.zoomContainer').remove();
        zoomImage.removeData('elevateZoom');
        // Update source for images
        zoomImage.attr('src', $(this).data('image'));
        zoomImage.data('zoom-image', $(this).data('zoom-image'));
        // Reinitialize EZ
        zoomImage.elevateZoom(zoomConfig);
    });

});