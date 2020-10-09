$(document).ready(function() {
    "use strict";

    // Mobile Menu
    $('.js-click-megamenu').on('click', function(event) {
        $(".box-mobile-menu").addClass('open')
        $(".menu-overlay").fadeIn();
        event.preventDefault()
    });
    $('.menu-overlay').on('click', function(event) {
        $(".box-mobile-menu").removeClass('open')
        $(".menu-overlay").fadeOut();
        event.preventDefault()
    });
    $('.close-menu').on('click', function(event) {
        $(".box-mobile-menu").removeClass('open')
        $(".menu-overlay").fadeOut();
        event.preventDefault()
    });

    // Toggle submenu mobile / responsive
    $(document).on('click', '.menu-item .toggle-submenu', function(e) {
        var $this = $(this);
        var $thisParent = $this.closest('.menu-item-has-children');
        if ($thisParent.length) {
            $thisParent.toggleClass('show-submenu').find('> .submenu').stop().slideToggle();
        }
        e.preventDefault();
        return false;
    });



    // CHOOSE NUMBER ORDER
    var value = parseInt($('#order-qty .quanlity-num').attr('value'));
    $('.quanlity #plus').click(function() {
        let order = $('#order-qty .quanlity-num');
        let numberOrder = parseInt(order.attr('max'));
        let minus = $('.quanlity #minus');
        if (value >= numberOrder) {
            value = numberOrder;
            $(this).prop('disabled', true);
            $(this).addClass('disabled');
        } else {
            value++;
            minus.prop('disabled', false);
            minus.removeClass('disabled');
        }
        $('#order-qty .quanlity-num').attr('value', value);
    });

    $('.quanlity #minus').click(function() {
        let plus = $('.quanlity #plus');
        if (value > 1) {
            plus.attr('disabled');
            value--;
            $('#order-qty .quanlity-num').attr('value', value);
            plus.prop('disabled', false);
            plus.removeClass('disabled');
        } else {
            $(this).prop('disabled', true);
            $(this).addClass('disabled');
        }
    });


    //Change product detail
    $(document).mouseup(function(e) {
        let listCategory = $('.list-category');
        if (!listCategory.is(e.target) // if the target of the click isn't the container...
            &&
            listCategory.has(e.target).length === 0) // ... nor a descendant of the container
        {
            listCategory.removeClass('show');
        }

    });

    $('.category-show').click(function(e) {
        let parent = $(this).closest('.filter-right-show');
        $('.list-category', parent).toggleClass('show');
    });


    //Change price fillter
    $('.submit-fillter-price input').click(function(e) {
        e.preventDefault();
        let current = $(this);
        let parent = current.closest('#price-fillter');
        let priceFillter = $('#price-fillter #price-change');
        var min = priceFillter.data("from"); // input data-from attribute
        var max = priceFillter.data("to"); // input data-to attribute
        var token = $('meta[name="csrf-token"]').attr('content');
        let url = $('.action-fillter-price', parent).data('action');
        let request = $('.action-request-url', parent).data('request');
        let data = { min: min, max: max, _token: token, request: request };
        $('.loader-wrapper').show();
        $.ajax({
            url: url,
            method: 'GET',
            data: data,
            dataType: 'text',
            success: function(data) {
                $('.result-fillter').html(data);
                $('.loader-wrapper').hide();
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });

    //Load more fillter
    var pageLoadmoreFillter = 1;
    var limitProductFillter = 8;
    $(document).on("click", '.load-more-fillter .load-more-item', function(e) {
        e.preventDefault();
        let parent = $(this).closest('.load-more-fillter');
        pageLoadmoreFillter++;
        var productFillter = parent.find('#total_product_fillter').val();
        let url = $('.action-load-more', parent).val();
        let token = $('meta[name="csrf-token"]').attr('content');
        var request = parent.find('.request-fillter').val();
        var min = parent.find('.fillter-min').val();
        var max = parent.find('.fillter-max').val();
        limitProductFillter = parseInt(limitProductFillter) + 8;
        $.ajax({
            url: url,
            method: 'post',
            data: { page: pageLoadmoreFillter, _token: token, slug: request, min: min, max: max },
            dataType: 'text',
            success: function(data) {
                if (productFillter <= limitProductFillter) {
                    $('.load-more-fillter .load-more-item').hide();
                }
                $('.result-load-more-fillter').append(data);
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });
    //end

    var pageLoadmore = 1;
    var productCategory = $('.load-more #total_product_cate').val();
    var limitProduct = 8;
    //LoadMore data
    $('.load-more .load-more-item').click(function(e) {
        e.preventDefault();
        let parent = $(this).closest('.load-more');
        pageLoadmore++;
        let url = $('.action-load-more', parent).val();
        let token = $('meta[name="csrf-token"]').attr('content');
        limitProduct = parseInt(limitProduct) + 8;
        $.ajax({
            url: url,
            method: 'POST',
            data: { page: pageLoadmore, _token: token },
            dataType: 'text',
            success: function(data) {
                $('.result-load-more').append(data);
                if (productCategory <= limitProduct) {
                    $('.load-more .load-more-item').hide();
                }

            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });

    var productOfCategory = $('.load-more-category #total_product_of_cate').val();
    var limitProductOfCate = 8;
    //LoadMore Date Product
    $('.load-more-category .load-more-item').on("click", function(e) {
        e.preventDefault();
        let parent = $(this).closest('.load-more-category');
        pageLoadmore++;
        let url = $('.action-load-more', parent).val();
        let token = $('meta[name="csrf-token"]').attr('content');
        limitProductOfCate = parseInt(limitProductOfCate) + 8;
        $.ajax({
            url: url,
            method: 'POST',
            data: { page: pageLoadmore, _token: token },
            dataType: 'text',
            success: function(data) {
                $('.result-load-more').append(data);
                if (productOfCategory <= limitProductOfCate) {
                    $('.load-more-category .load-more-item').hide();
                }

            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });

    //Hien thi size theo mau product detail

    function disableSizeProduct() {
        $('.product-size .size-info option').each(function(item) {
            let amount = $(this).attr('data-amount');
            if (amount == 0) {
                $(this).attr('disabled', true);
                $(this).addClass('disabled-size');
            } else {
                if (amount > 0) {
                    $('.product-size .size-info option:not(.disabled-size)').first().attr('selected', true);
                    $('.product-size .size-info').change(function() {
                        $('.product-size .size-info option:selected').removeAttr('selected');
                        $(this).find('option').attr('selected', true);
                    });
                }
            }
        });
    }

    function changeQtyOfSize() {
        var max = $('.product-size .size-info option:selected').first().data('amount');
        $('.product-detail-buy .quanlity .quanlity-num').attr('max', max);
        $('.product-size .size-info').change(function() {
            let current = $(this);
            let parent = current.closest(".product-info-detail");
            var option = $('option:selected', current).attr('data-amount');
            // parent.find('.product-color .color-item.active .color-info').attr('data-qty', option);
            $('.product-detail-buy .quanlity .quanlity-num').attr('max', option);
        });
    }


    $('.product-color .color-item .color-info[data-qty = "' + 0 + '"]').each(function(index, item) {
        var qty_product = $(this).data('qty');
        if (qty_product == 0) {
            $(this).closest('.product-info-detail').find('.product-out-stock').show();
            $('.product-info-detail .product-size').hide();
            $(this).closest('.product-color .color-item').attr('title', 'Sản phẩm tạm hết hàng');
            $('[data-toogle="tooltip"]').tooltip();
            $('.product-color .color-item .color-info[data-qty = "' + 0 + '"]').attr('disabled', true);
            $('.product-color .color-item .color-info[data-qty = "' + 0 + '"]').addClass('disabled');
            var sizeZezo = $('.product-size').find('.size-info option').attr('data-amount');
            if (sizeZezo == 0) {
                $('.product-size .size-info option[data-amount = "' + 0 + '"]').attr('disabled', true);
                $('.product-size .size-info option[data-amount = "' + 0 + '"]').addClass('disabled');
                $(this).closest('.product-info-detail').find('.product-detail-buy').append("<div class='overlay-product'></div>");
            }
        }
    });


    function getSizeOfColor() {
        $('.product-color .color-item').first().addClass('active');
        $('.product-color .color-item').first().find('.color-info').attr('checked', true);
        let parent = $('.product-info-detail');
        disableSizeProduct();
        changeQtyOfSize();
        $('.product-color .color-item').change(function(e) {
            e.preventDefault();
            let current = $(this);
            $('.product-color .color-item').find('.color-info').removeAttr('checked');
            $('.product-color .color-item').removeClass('active');
            $(this).find('.color-info').attr('checked', true);
            current.addClass('active');
            parent.find('.product-size').show();
            let colorItem = current.find('.color-info').val();
            var dataQty = current.find('.color-info').data('qty');
            let slug = current.find('.slug-product').val();
            let url = current.find('.action-change-size').val();
            let token = $('meta[name="csrf-token"]').attr('content');
            let data = { colorItem: colorItem, _token: token, slug: slug };
            if (dataQty > 0) {
                parent.find('.product-detail-buy .overlay-product').remove();
                parent.find('.product-out-stock').hide();
            }
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'text',
                data: data,
                success: function(data) {

                    $('.change-color-size').html(data);
                    disableSizeProduct();
                    changeQtyOfSize();

                },
                error: function(xhr, ajaxOption, throwError) {
                    alert(xhr.status);
                    alert(throwError);
                }
            });
        });
    }
    getSizeOfColor();




    //Change shipping 
    $('input[type=radio][name=shipping]').change(function() {
        let total = $('.text-price .total-order').data('total');
        let shipping = 0;
        if (this.value == 'delivery') {
            shipping = 30.000;
            $('.text-price #total-ship').text(shipping.toFixed(3) + 'đ');
        } else if (this.value == 'free') {
            $('.text-price #total-ship').text(shipping + 'đ');
        }
        let elementShipping = $('.text-price #total-ship').text();

        let subTotal = parseFloat(total.replace(/\./g, '')) + parseFloat(elementShipping.replace(/\./g, ''));
        let numberFomart = new Intl.NumberFormat('vi', { style: 'currency', currency: 'VND' }).format(subTotal);
        $('.text-price .total-order').html(numberFomart);


    });

    //AJAX UPDATE CART
    var countItemProduct = $('.st2-cart-icon .st2-cart-number').text();
    var numberProductCart = $('.hamadryad-minicart-number .number-product-item').text();
    $('.cart-row .minus-change').click(function(e) {
        e.preventDefault();
        var element = $(this).closest('.cart-row');
        var qty = parseInt($('.number-order-quantity', element).attr('value'));
        var plusChange = $('.plus-change', element);
        let dataPrice = $('.data-price', element).val();
        var productID = $('.number-order-quantity', element).data('product-id');
        countItemProduct--;
        numberProductCart--;
        qty--;
        console.log(qty);
        if (qty > 1) {
            plusChange.removeClass('disabled');
            plusChange.attr('disabled', false);
        } else if (qty == 1) {
            $(this).attr('disabled', true);
            $(this).addClass('disabled');

        }
        var action = $('.action-update-cart', element),
            url = action.data('action'),
            row = action.data('row'),
            id = action.data('id'),
            token = $('meta[name="csrf-token"]').attr('content'),
            data = { id: id, row: row, qty: qty, _token: token, dataPrice: dataPrice };
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            dataType: 'JSON',
            success: function(data) {
                $('.price-total', element).text(data.total);
                $('.cart-total .total-item').text(data.sub_total);
                $('.number-order-quantity', element).attr('value', qty);
                $('.data-qty', element).attr('value', qty);
                $('.st2-cart-icon .st2-cart-number').text(countItemProduct);
                $('.hamadryad-minicart-number .number-product-item').text(numberProductCart);
                $('.product-detail-info .product-quality .data-qty[data-id="' + productID + '"]').text(qty);
                $('.product-detail-info .product-cost .data-total-product[data-id="' + productID + '"]').text(data.total);
                $('.subtotal .total-price').text(data.sub_total);
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });



    $('.cart-row .plus-change').click(function(e) {
        e.preventDefault();
        var element = $(this).closest('.cart-row');
        var qty = parseInt($('.number-order-quantity', element).attr('value'));
        var action = $('.action-update-cart', element);
        var url = action.data('action');
        var id = action.data('id');
        var productID = $('.number-order-quantity', element).data('product-id');
        var max = parseInt($('.number-order-quantity', element).attr('max'));
        var token = $('meta[name="csrf-token"]').attr('content');
        var minusChange = $('.minus-change', element);
        let dataPrice = $('.data-price', element).val();
        countItemProduct++;
        numberProductCart++;
        qty++;
        if (qty < max) {
            minusChange.removeClass('disabled');
            minusChange.attr('disabled', false);
        } else if (qty == max) {
            $(this).addClass('disabled');
            $(this).attr('disabled', true);
        }
        var data = { id: id, qty: qty, _token: token, dataPrice: dataPrice };
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            dataType: 'JSON',
            success: function(data) {
                $('.price-total', element).text(data.total);
                $('.cart-total .total-item').text(data.sub_total);
                $('.number-order-quantity', element).attr('value', qty);
                $('.data-qty', element).attr('value', qty);
                $('.st2-cart-icon .st2-cart-number').text(countItemProduct);
                $('.hamadryad-minicart-number .number-product-item').text(numberProductCart);
                $('.product-detail-info .product-quality .data-qty[data-id="' + productID + '"]').text(qty);
                $('.product-detail-info .product-cost .data-total-product[data-id="' + productID + '"]').text(data.total);
                $('.subtotal .total-price').text(data.sub_total);
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    });

    // Change product image slider

    $('.slider-nav .change-image img').click(function(e) {
        e.preventDefault();
        let current = $(this);
        let imgUrl = current.data('image');
        let item = $('.change-pr-img img');
        let changeImage = item.attr('src', imgUrl);
        console.log(imgUrl);
        console.log(changeImage);
    });




    //------- Client say page_01 --------//  
    $('.active-review-carusel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        margin: 30,
        dots: true
    });

    $('.active-clientsaylist').owlCarousel({
        items: 2,
        loop: true,
        autoplayHoverPause: true,
        dots: false,
        autoplay: false,
        nav: true,
        navText: ["<span class='lnr lnr-arrow-up'></span>", "<span class='lnr lnr-arrow-down'></span>"],
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1,
            },
            768: {
                items: 1,
            }
        }
    });

    $('.active-brand-carusel').owlCarousel({
        items: 5,
        loop: true,
        autoplayHoverPause: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            455: {
                items: 2
            },
            768: {
                items: 3,
            },
            991: {
                items: 4,
            },
            1024: {
                items: 5,
            }
        }
    });


    //Scroll Menu page_02
    // document.addEventListener("DOMContentLoaded",function() {
    //     var menu = document.querySelectorAll('section.header-bottom');
    //     var menu = menu[0];
    //         //Truy xuất div menu
    //         var status="duoi150";
    //     window.addEventListener("scroll",function(){
    //         var x = pageYOffset;
    //         if(x > 150){
    //             if(status == "duoi150")
    //             {
    //                 status="tren150";
    //                 menu.classList.add('menutora');
    //             }
    //         }
    //         else{
    //             if(status=="tren150"){
    //             menu.classList.remove('menutora');
    //             status="duoi150";}
    //         }

    //     })
    // })

    // Search box homepage 1
    $('.js-search-box').on('click', function(event) {
        $(".search-box").addClass('search-box-active')
        $(".st1-search-box-bg").addClass('search-box-bg-active')
        event.preventDefault()
    });
    $('.close-search-box').on('click', function(event) {
        $(".search-box").removeClass('search-box-active')
        $(".st1-search-box-bg").removeClass('search-box-bg-active')
        event.preventDefault()
    });
    $('.st1-search-box-bg').on('click', function(event) {
        $(".search-box").removeClass('search-box-active')
        $(".st1-search-box-bg").removeClass('search-box-bg-active')
        event.preventDefault()
    });

    // Minicart
    $('.js-click-cart').on('click', function(event) {
        $(".hamadryad-minicart").addClass('open')
        $(".minicart-bg-overlay").fadeIn('slow');
        event.preventDefault()
    });
    $('.hamadryad-minicart-close').on('click', function(event) {
        $(".hamadryad-minicart").removeClass('open')
        $(".minicart-bg-overlay").fadeOut('slow');
        event.preventDefault()
    });
    $('.minicart-bg-overlay').on('click', function(event) {
        $(".hamadryad-minicart").removeClass('open')
        $(".minicart-bg-overlay").fadeOut('slow');
        event.preventDefault()
    });

    // Toggle submenu categories
    $(document).on('click', '.st3-categories-item .st3-categories-icon', function(e) {
        var $this = $(this);
        var $thisParent = $this.closest('.st3-categories-item-child');
        if ($thisParent.length) {
            $thisParent.toggleClass('show-subcate').find('> .sub-categories').stop().slideToggle();
        }
        e.preventDefault();
        return false;
    });



    // Slide Page 1


    $('.js-slide-previous').on('click', function(e) {
        $('.slick-prev').click();
        e.preventDefault();
    });

    $('.js-slide-next').on('click', function(e) {
        $('.slick-next').click();
        e.preventDefault();
    });

    // Sider Page3
    $('.categories-slide-right').slick({
        dots: false,
        arrows: false,
        autoplay: false,
        autoplaySpeed: 1500
    });


    // Sider About us
    $('.slide-aboutus').slick({
        dots: false,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 1500
    });

    // Slide Page 2
    $('.slide-page2').slick({
        dots: false,
        arrows: false,
        autoplay: false,
        autoplaySpeed: 1500
    });

    // Sider About us
    $('.slide-contactus').slick({
        dots: false,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 1500
    });

    //------- Deal of the week --------//  
    $('.active-review-carusel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        margin: 30,
        dots: true
    });

    $('.active-pro-dealof-theweek').owlCarousel({
        items: 4,
        loop: true,
        margin: 30,
        autoplayHoverPause: false,
        dots: false,
        autoplay: false,
        nav: true,
        navText: ["<span>Back</span>", "<span>Next</span>"],
        responsive: {
            0: {
                items: 1,
            },
            480: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1024: {
                items: 3,
            },
            1280: {
                items: 4,
            },
            1440: {
                items: 4,
            }
        }
    });

    $('.active-brand-carusel').owlCarousel({
        items: 4,
        loop: true,
        autoplayHoverPause: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            455: {
                items: 2
            },
            768: {
                items: 3,
            },
            991: {
                items: 4,
            },
            1024: {
                items: 4,
            },
            1920: {
                items: 4,
            }
        }
    });

    //------- Popular Product , Related Products--------//  
    $('.active-review-carusel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        margin: 30,
        dots: true
    });
    $('.active-pro-relerate').owlCarousel({
        items: 3,
        loop: true,
        margin: 30,
        autoplayHoverPause: true,
        dots: false,
        autoplay: false,
        nav: false,
        navText: ["<span class='lnr lnr-arrow-up'></span>", "<span class='lnr lnr-arrow-down'></span>"],
        responsive: {
            0: {
                items: 1,
            },
            480: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1024: {
                items: 3,
            },
            1280: {
                items: 4,
            },
            1440: {
                items: 4,
            }
        }
    });
    $('.active-brand-carusel').owlCarousel({
        items: 4,
        loop: true,
        autoplayHoverPause: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            455: {
                items: 2
            },
            768: {
                items: 3,
            },
            991: {
                items: 4,
            },
            1024: {
                items: 4,
            },
            1920: {
                items: 4,
            }
        }
    });

    // FOOTER HOMEPAGE 3
    $(document).on('click', '.icon-plus', function(e) {
        var $this = $(this);
        var $thisParent = $this.closest('.t-center-laptop');
        if ($thisParent.length) {
            $thisParent.toggleClass('show-submenu').find('> .f-content-list-item').stop().slideToggle();
        }
        e.preventDefault();
        return false;
    });

    // PRODUCT DETAIL V3
    $('.slider-single').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        vertical: true,
        arrows: false,
        verticalSwiping: true,
        asNavFor: '.slider-single',
        dots: false,
        focusOnSelect: true
    });

    // PRODUCT DETAIL V4
    $('.slider-single-v4').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav-v4'
    });
    $('.slider-nav-v4').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        verticalSwiping: true,
        asNavFor: '.slider-single-v4',
        dots: false,
        focusOnSelect: true
    });

    // POPUP QuickView
    $('.review').on('click', function(event) {
        $(".product-quick-view").addClass('quick-view-active')
        $(".product-quick-view-overlay").fadeIn();

        // Quick View galary image product
        $('.slide-quickview-single').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.slide-quickview-nav'
        });
        $('.slide-quickview-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            vertical: true,
            arrows: false,
            verticalSwiping: true,
            asNavFor: '.slide-quickview-single',
            dots: false,
            focusOnSelect: true
        });
        event.preventDefault()
    });
    $('.product-quick-view-overlay').on('click', function(event) {
        $(".product-quick-view").removeClass('quick-view-active')
        $(".product-quick-view-overlay").fadeOut()
        event.preventDefault()
    });
    $('.close-quick-view').on('click', function(event) {
        $(".product-quick-view").removeClass('quick-view-active')
        $(".product-quick-view-overlay").fadeOut()
        event.preventDefault()
    });

    // POPUP Auto
    $('.wishlist').on('click', function(event) {
        $(".popup").addClass('popup-active')
        $(".popup-overlay").fadeIn();
        event.preventDefault()
    });
    $('.popup-overlay').on('click', function(event) {
        $(".popup").removeClass('popup-active')
        $(".popup-overlay").fadeOut()
        event.preventDefault()
    });
    $('.close-popup').on('click', function(event) {
        $(".popup").removeClass('popup-active')
        $(".popup-overlay").fadeOut()
        event.preventDefault()
    });


    // List - Gird Shop Page Fullwidth
    $('.list-js').on('click', function(event) {
        $('.box-item-list-shoppage').addClass('item-list-shoppage');
        $('.layout').removeClass("col-xl-3 col-md-6 col-lg-4 col-sm-6 col-12 transition-06").delay(10).queue(function(next) {
            $(this).addClass("col-xl-6 col-lg-6 col-md-6 col-custom-12 col-sm-12 col-12 list-pb");
            next();
        });
    });
    $('.grid-js').on('click', function(event) {
        $(".box-item-list-shoppage").removeClass('item-list-shoppage');
        $('.layout').removeClass("col-xl-6 col-lg-6 col-md-6 col-custom-12 col-sm-12 col-12 list-pb");
        $('.layout').addClass("col-xl-3 col-md-6 col-lg-4 col-sm-6 col-12 transition-06");
    });

    // List - Gird Shop Page Fullwidth Sidebar Left
    $('.sidebarleft-list-js').on('click', function(event) {
        $('.box-item-list-shoppage').addClass('item-list-shoppage');
        $('.sidebar-left-layout').removeClass("col-xl-4 col-md-6 col-lg-4 col-sm-12 col-12 transition-05").delay(10).queue(function(next) {
            $(this).addClass("col-xl-12 col-lg-12 col-md-12 list-pb");
            next();
        });
    });
    $('.sidebarleft-grid-js').on('click', function(event) {
        $(".box-item-list-shoppage").removeClass('item-list-shoppage');
        $('.sidebar-left-layout').removeClass("col-xl-12 col-lg-12 col-md-12 list-pb").delay(10).queue(function(next) {
            $(this).addClass("col-xl-4 col-md-6 col-lg-4 col-sm-12 col-12 transition-05");
            next();
        });
    });

    // SET WIDTH SUBMENU PAGE 1
    function setWidthFollowScreenPage1(selectorv1, comparev1, sub1v1, dadv1, sub2v1) {
        if ($(window).width() >= 1025) {
            var width = $(comparev1).width() - sub1v1;
            $(selectorv1).css({ 'width': function() { return width + 'px' } })
        }

        $(window).resize(function() {
            if ($(window).width() >= 1025) {
                var width = $(comparev1).width() - sub1v1;
                $(selectorv1).css({ 'width': function() { return width + 'px' } })
            }
        })
    }
    setWidthFollowScreenPage1('.js-dropmenuv1', '.js-comparev1', '160', '.js-dadv1', '80');

    // SET WIDTH SUBMENU
    function setWidthFollowScreen(selector, compare, sub1, dad, sub2) {
        if ($(window).width() >= 1025) {
            var width = $(compare).width() - sub1;
            $(selector).css({
                'width': function() { return width + 'px' },
                'left': function() {
                    var left_val = (($(window).width() - width) / 2) - $(dad).position().left;
                    console.log($(dad).position().left)
                    console.log($(window).width())
                    console.log(($(window).width() - width) / 2)
                    return left_val - sub2 + 'px';
                }
            })
        }

        $(window).resize(function() {
            if ($(window).width() >= 1025) {
                var width = $(compare).width() - sub1;
                $(selector).css({
                    'width': function() { return width + 'px' },
                    'left': function() {
                        var left_val = -($(dad).position().left - (($(window).width() - width) / 2));
                        return left_val - sub2 + 'px';
                    }
                })
            }
        })
    }
    setWidthFollowScreen('.js-dropmenu', '.js-compare', '260', '.js-dad', '80');

    // Popup auto show
    setTimeout(function() {
        $('.popup').addClass('popup-active');
    }, 2000);

    //  Range Slider Filter Product Price
    $(".js-range-slider").ionRangeSlider({

        type: "double",
        // min: 0,
        // max: 1000,
        // from: 200,
        // to: 500,
        step: 500,
        prettify_separator: '.',
        grid: false,
        postfix: "đ",
    });

    //  Range Slider Filter Product Price
    $(".js-range-slider1").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000,
        from: 200,
        to: 500,
        grid: false
    });

    // SET HEIGHT BOX BG PAGE-1
    function setheightSlide(selector1, compare1) {
        if ($(window).width() >= 1025) {
            var height = $(compare1).height();
            height = height + 35;
            $(selector1).css({
                'height': function() {
                    console.log(height)
                    return height + 'px'
                }
            })
        }
        if ($(window).width() >= 1280) {
            var height = $(compare1).height();
            height = height + 75;
            $(selector1).css({
                'height': function() {
                    console.log(height)
                    return height + 'px'
                }
            })
        }
    }
    setheightSlide('.js-box-bg', '.js-box-space');

    // BTN SIDEBAR MOBIE
    function showSidebar() {
        $('.js-filter').on('click', function() {
            $('.js-fix-sidebar').toggleClass('sidebar-active');
            if ($('.js-fix-sidebar').hasClass('sidebar-active')) {
                $('.js-filter span').removeClass('fa-filter').addClass('fa-times');
            } else {
                $('.js-filter span').removeClass('fa-times').addClass('fa-filter');
            }
        })
    }
    showSidebar();

    // Add active class to the multi layout
    // var header = document.getElementById("multiLayout");
    // var btns = header.getElementsByClassName("view-icon");
    // for (var i = 0; i < btns.length; i++) {
    //     btns[i].addEventListener("click", function() {
    //         var current = document.getElementsByClassName("actived");
    //         console.log(current);
    //         if (current.length > 0) {
    //             current[0].className = current[0].className.replace(" actived", "");
    //         }
    //         this.className += " actived";
    //     });
    // }

});