// const { result } = require("lodash");

// const { forEach } = require("lodash");

$(document).ready(function() {
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();
    $('.nav-right  .dropdown-toggle').click(function() {
        $('.dropdown-menu-right').toggleClass('show');
    })

    $('#sidebar-menu .arrow').click(function() {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function() {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });

    $("input[name='check-all-role']").click(function() {
        var checked = $(this).is(':checked');
        $('.user-role .role-item input:checkbox').prop('checked', checked);
    });


    // Change menu sort
    $('.action-menu #menu-parent').change(function() {
        let parent = $(this);
        let val = parent.val();
        let menuSort = $('.action-menu #menu-sort');
        if (parseInt(val) != 0)
            menuSort.hide();
        else
            menuSort.show();
    });

    //  show preview single thumnail
    function previewImage(input, preview) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.show();
                preview.attr('src', e.target.result);
                preview.hide();
                preview.fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.thumbnail-preview').change(function(e) {
        let parent = $(this).closest('.thumb-post');
        let preview = $('#thumbnail-post', parent);
        previewImage(this, preview);
    });

    $('#slider-preview').change(function(e) {
        let parent = $(this).closest('.thumb-slider');
        let preview = $('#slider-thumbnail', parent);
        previewImage(this, preview);
    });

    $('#thumbnail-category-preview').change(function(e) {
        let parent = $(this).closest('.thumb-category');
        $('.thumbnail-exits-cate').css('display', 'none');
        let preview = $('.thumbnail-category', parent);
        previewImage(this, preview);
    });


    $('.feature-image').on('change', function() { //on file input change
        if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
        {

            var data = $(this)[0].files; //this file data
            $.each(data, function(index, file) { //loop though each file
                if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) { //check supported file type
                    var fRead = new FileReader(); //new filereader
                    fRead.onload = (function(file) { //trigger function on successful read
                        return function(e) {
                            var img = $('<img/>').addClass('thumbnail').attr('src', e.target.result); //create image element 
                            $('.thumbnail-feature-wrapper').append(img); //append image to output element
                        };
                    })(file);
                    fRead.readAsDataURL(file); //URL representing the file's data.
                }
            });
            $('#content .card-body .feature-image-wrapper').hide();
        } else {
            alert("Your browser doesn't support File API!"); //if File API is absent
        }
    });


    // change image
    function changeImage(input, selector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                selector.attr('src', e.target.result);
                console.log(selector.attr('src', e.target.result));
                selector.hide();
                selector.fadeIn(700);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
    $('#change-image').change(function(e) {
        let parent = $(this).closest('.change-image-post');
        $('.post-thumbnail').css('display', 'none');
        let selector = $('.thumbnail-preview', parent);
        changeImage(this, selector);
    });

    $('#change-image-product').change(function(e) {
        let parent = $(this).closest('.thumb-product');
        let selector = $('.feature-product', parent);
        previewImage(this, selector);
    });

    // remove image feature image

    // $('.remove-thumb .close-image').click(function() {
    //     let parent = $(this).closest('.remove-thumb');
    //     let current = $('.close-image', parent);
    //     let data_key = current.data('key');
    //     let image = $('.thumb-feature-product', parent);
    //     let image_key = image.data('key');
    //     if (data_key == image_key) {
    //         $('.remove-thumb .thumb-feature-product[data-key="' + image_key + '"]').remove();
    //         $('.remove-thumb .close-image[data-key="' + data_key + '"]').remove();
    //     }
    // });

    // Tu dong cap nhat slug khi thay doi title

    $('.custom-title .change-title').keyup(function() {
        var title = $(this).val();
        var item = title.toLowerCase().split(' ').join('-');
        var slug = item.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        $('.custom-slug .change-slug').val(slug);
    });

    // search user
    function setupFormSearch() {
        var parent = $('.form-search-result');
        var keyword = $('.keyword', parent).val();
        let actionURL = parent.attr('action');
        $('.loader-wrapper').show();
        var token = $('meta[name="csrf-token"]').attr('content');
        var data = { keyword: keyword, _token: token };
        $.ajax({
            url: actionURL,
            method: 'get',
            data: data,
            dataType: 'text',
            success: function(data) {
                $('.table-checkall').html(data);
                $('.loader-wrapper').hide();
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    }

    $('#form-search-user').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-page').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-category-post').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-post').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-category-product').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-color').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-product').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-menu').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-order').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-inventory').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-role').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-slider').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-size').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-customer').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });

    $('#search-form-feedback').submit(function(e) {
        e.preventDefault();
        setupFormSearch();
    });
    //end search



    function setupPagination(url, object, status) {
        var page = object.attr('href').split('page=')[1],
            token = $('meta[name="csrf-token"]').attr('content'),
            status = status,
            data = { page: page, _token: token, status: status };
        console.log(page);
        $.ajax({
            url: url,
            header: {
                'X-CSRF-TOKEN': token
            },
            method: 'GET',
            data: data,
            dataType: 'text',
            success: function(data) {
                $('.table-data').html(data);
            },
            error: function(xhr, ajaxOption, throwError) {
                alert(xhr.status);
                alert(throwError);
            }
        });
    }

    // pagination
    $(document).on('click', '.table-data-user .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-user'),
            url = parent.data('action'),
            current = $(this);
        setupPagination(url, current);
    });

    $(document).on('click', '.table-data-page .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-page'),
            url = parent.data('action'),
            status = '',
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #page-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #page-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #page-trash'),
                status = currentTab.data('status');

        }
        setupPagination(url, current, status);
    });

    //pagination module post
    $(document).on('click', '.table-data-post .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-post'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #post-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #post-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #post-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });

    //pagination module products
    $(document).on('click', '.table-data-product .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-product'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #product-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #product-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #product-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });

    //Color
    $(document).on('click', '.table-data-color .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-color'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #color-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #color-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #color-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });

    //Size
    $(document).on('click', '.table-data-size .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-size'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #size-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #size-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #size-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });

    //pagination module order
    $(document).on('click', '.table-data-order .pagination-info a', function(event) {
        // alert(123);
        event.preventDefault();
        var parent = $(this).closest('.table-data-order'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #order-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #order-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #order-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });

    //Invertory
    $(document).on('click', '.table-data-inventory .pagination-info a', function(event) {
        // alert(123);
        event.preventDefault();
        var parent = $(this).closest('.table-data-inventory'),
            url = parent.data('action'),
            status = '',
            current = $(this);
        console.log(url);
        setupPagination(url, current, status);
    });

    //Role user
    $(document).on('click', '.table-data-role .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-role'),
            url = parent.data('action'),
            status = '',
            current = $(this);
        setupPagination(url, current, status);
    });

    //Slider
    $(document).on('click', '.table-data-slider-pagination .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-slider-pagination'),
            url = parent.data('action'),
            status = '',
            current = $(this);
        setupPagination(url, current, status);
    });

    //Customer
    $(document).on('click', '.table-data-customer-admin .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-customer-admin'),
            url = parent.data('action'),
            status = '',
            current = $(this);
        setupPagination(url, current, status);
    });

    //Feedback
    $(document).on('click', '.table-data-feedback .pagination a', function(event) {
        event.preventDefault();
        var parent = $(this).closest('.table-data-feedback'),
            url = parent.data('action'),
            current = $(this);
        if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
            var currentTab = $('.card-body .analytic #feedback-active'),
                status = currentTab.data('status');
        } else if (getParamUrl('status') == 'pendding') {
            var currentTab = $('.card-body .analytic #feedback-pendding'),
                status = currentTab.data('status');
        } else {
            var currentTab = $('.card-body .analytic #feedback-trash'),
                status = currentTab.data('status');
        }
        setupPagination(url, current, status);
    });


    //end pagination

    //start delete record
    function setupDeleteRecord(current, active, pendding, trash, numberActive, numberTrash, numberPendding) {
        var id = current.data('id'),
            url = current.attr('href'),
            parent = current.closest('.element'),
            itemActive = active,
            itemTrash = trash,
            itemPendding = pendding,
            data = { id: id, _token: token },
            token = $('meta[name="csrf-token"]').attr('content'),
            element_id = parent.data('id');
        $('.loader-wrapper').show();
        if (confirm('Bạn có chắc chắn muốn xóa bản ghi này không?')) {
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (typeof data == 'object') {
                        if (data.status == 1) {
                            if (data.id == element_id) {
                                parent.remove();
                                $('.loader-wrapper').hide();
                                $('.form-message').html(data.message).show();
                                if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
                                    if (Number(itemActive) > 0) {
                                        let result = '(' + Number(itemActive - 1) + ')';
                                        numberActive.text(result);
                                    } else {
                                        $('.result').text('Không tìm thấy bản ghi');
                                    }
                                    let countItemTrash = '(' + Number(itemTrash + 1) + ')';
                                    numberTrash.text(countItemTrash);
                                } else if (getParamUrl('status') == 'pendding') {
                                    if (Number(itemPendding) > 0) {
                                        let result = '(' + Number(itemPendding - 1) + ')';
                                        numberPendding.text(result);
                                    } else {
                                        $('.result').text('Không tìm thấy bản ghi');
                                    }
                                    let countItemTrash = '(' + Number(itemTrash + 1) + ')';
                                    numberTrash.text(countItemTrash);
                                }
                            }
                        } else {
                            $('.loader-wrapper').hide();
                            $('.form-message').html(data.message).show();
                        }
                    }

                },
                error: function(xhr, ajaxOption, throwError) {
                    alert(xhr.status);
                    alert(throwError);
                }
            });
        } else {
            $('.loader-wrapper').hide();
        }
    }

    $(document).on('click', '.table-user .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            userActive = $('.card-body .analytic .user-active').data('active'),
            userTrash = $('.card-body .analytic .user-trash').data('trash'),
            userPendding = $('.card-body .analytic .user-pendding').data('pendding'),
            numberActive = $('.card-body .analytic .user-active span'),
            numberTrash = $('.card-body .analytic .user-trash span'),
            numberPendding = $('.card-body .analytic .user-pendding span');
        setupDeleteRecord(current, userActive, userPendding, userTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-page .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #page-active').data('active'),
            pagePendding = $('.card-body .analytic #page-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #page-trash').data('trash'),
            numberActive = $('.card-body .analytic  #page-active span'),
            numberTrash = $('.card-body .analytic #page-trash span'),
            numberPendding = $('.card-body .analytic #page-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-category-post .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = '',
            pagePendding = '',
            pageTrash = '',
            numberActive = '',
            numberTrash = '',
            numberPendding = '';
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-post .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #post-active').data('active'),
            pagePendding = $('.card-body .analytic #post-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #post-trash').data('trash'),
            numberActive = $('.card-body .analytic  #post-active span'),
            numberTrash = $('.card-body .analytic #post-trash span'),
            numberPendding = $('.card-body .analytic #post-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });


    $(document).on('click', '.table-data-category-product .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = '',
            pagePendding = '',
            pageTrash = '',
            numberActive = '',
            numberTrash = '',
            numberPendding = '';
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-product .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #product-active').data('active'),
            pagePendding = $('.card-body .analytic #product-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #product-trash').data('trash'),
            numberActive = $('.card-body .analytic  #product-active span'),
            numberTrash = $('.card-body .analytic #product-trash span'),
            numberPendding = $('.card-body .analytic #product-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-color .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #color-active').data('active'),
            pagePendding = $('.card-body .analytic #color-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #color-trash').data('trash'),
            numberActive = $('.card-body .analytic  #color-active span'),
            numberTrash = $('.card-body .analytic #color-trash span'),
            numberPendding = $('.card-body .analytic #color-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });


    $(document).on('click', '.table-data-menu .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = '',
            pagePendding = '',
            pageTrash = '',
            numberActive = '',
            numberTrash = '',
            numberPendding = '';
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-role .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = '',
            pagePendding = '',
            pageTrash = '',
            numberActive = '',
            numberTrash = '',
            numberPendding = '';
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });


    //delete order 
    $(document).on('click', '.table-data-order .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #order-active').data('active'),
            pagePendding = $('.card-body .analytic #order-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #order-trash').data('trash'),
            numberActive = $('.card-body .analytic  #order-active span'),
            numberTrash = $('.card-body .analytic  #order-trash span'),
            numberPendding = $('.card-body .analytic #order-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    //delete slider

    $(document).on('click', '.table-data-slider .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = '',
            pagePendding = '',
            pageTrash = '',
            numberActive = '',
            numberTrash = '',
            numberPendding = '';
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    $(document).on('click', '.table-data-size .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #size-active').data('active'),
            pagePendding = $('.card-body .analytic #size-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #size-trash').data('trash'),
            numberActive = $('.card-body .analytic  #size-active span'),
            numberTrash = $('.card-body .analytic  #size-trash span'),
            numberPendding = $('.card-body .analytic #size-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });


    $(document).on('click', '.table-data-feedback .delete-record', function(e) {
        e.preventDefault();
        var current = $(this),
            pageActive = $('.card-body .analytic #feedback-active').data('active'),
            pagePendding = $('.card-body .analytic #feedback-pendding').data('pendding'),
            pageTrash = $('.card-body .analytic #feedback-trash').data('trash'),
            numberActive = $('.card-body .analytic  #feedback-active span'),
            numberTrash = $('.card-body .analytic #feedback-trash span'),
            numberPendding = $('.card-body .analytic #feedback-pendding span');
        setupDeleteRecord(current, pageActive, pagePendding, pageTrash, numberActive, numberTrash, numberPendding);
    });

    //end delete record


    // get parameter url
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    }

    // get value param
    function getParamUrl(url) {
        let searchParams = new URLSearchParams(window.location.search);
        return searchParams.get(url);
    }

    //active tab
    $('.analytic a:first-child').addClass('active');
    if (getParamUrl('status') == 'trash' || getParamUrl('status') == 'pendding') {
        $('.analytic a:first-child').removeClass('active');
    }


    // ajax action module 
    function setupAjax(current, url, activeUser, trashUser, pendingUser) {
        var id = [],
            parent = current.closest('.form-data-action'),
            infoAction = $('.info-action', parent).val(),
            url = url + '?action=' + infoAction,
            token = $('meta[name="csrf-token"]').attr('content'),
            userActive = activeUser,
            userTrash = trashUser,
            userPendding = pendingUser,
            data = { id: id, _token: token, infoAction: infoAction };
        $('input:checked', parent).each(function() {
            id.push($(this).val());
            console.log(id.push($(this).val()));
            for (let i = 0; i < id.length; i++) {
                if (id[i] == 'on') {
                    id.splice(id[i], 1);
                }
            }
        });
        if (infoAction !== 'choose') {
            $('.loader-wrapper').show();
            if (id.length > 0) {
                if (confirm('Bạn có chắc chắn muốn thực hiện chức năng này không?')) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(data) {
                            if (typeof data == 'object') {
                                if (data.status == 1) {
                                    id.forEach(function(item) {
                                        $('.element[data-id="' + item + '"]', parent).remove();
                                    });
                                    if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 200);
                                        let countUserActice = Number(userActive - id.length);
                                        if (countUserActice == 0) {
                                            $('.result').text('Không tìm thấy bản ghi').show();
                                        }
                                    } else if (getParamUrl('status') == 'trash') {
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 200);
                                        let countUserTrash = Number(userTrash - id.length);
                                        if (countUserTrash == 0) {
                                            $('.result', parent).text('Không tìm thấy bản ghi').show();
                                        }
                                    } else if (getParamUrl('status') == 'pendding') {
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 200);
                                        let countUserPendding = Number(userPendding - id.length);
                                        if (countUserPendding == 0) {
                                            $('.result').text('Không tìm thấy bản ghi').show();
                                        }
                                    }

                                    $('.form-message').html(data.message).show();
                                    $('.loader-wrapper').hide();
                                } else {
                                    $('.form-message').html(data.message).show();
                                    $('.loader-wrapper').hide();
                                }
                            }

                        },
                        error: function(xhr, ajaxOption, throwError) {
                            alert(xhr.status);
                            alert(throwError);
                        }
                    });
                } else {
                    $('.loader-wrapper').hide();
                }
            } else {
                $('.loader-wrapper').hide();
                $('.message').html("Bạn cần chọn bản ghi để thực hiện chức năng này").show();
            }
        } else {
            $('.message').html("Bạn cần chọn chức năng để thực hiện thao tác trên bản ghi").show();
        }
    }

    // action user
    if (getParamUrl('status') == 'active' || !getQueryVariable("status")) {

        //action user
        $('.form-action .action-user').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                userActive = $('.card-body .analytic .user-active').data('active'),
                userTrash = $('.card-body .analytic .user-trash').data('trash'),
                userPendding = '';

            setupAjax(current, url, userActive, userTrash, userPendding);
        });

        //action page
        $('.form-action-page .action-page').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                pageActive = $('.card-body .analytic #page-active').data('active'),
                pageTrash = $('.card-body .analytic #page-trash').data('trash'),
                pagePendding = $('.card-body .analytic #page-trash').data('pendding');

            setupAjax(current, url, pageActive, pageTrash, pagePendding);
        });

        //action post

        $('.form-action-post .action-post').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #post-active').data('active'),
                categoryPendding = $('.card-body .analytic #post-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #post-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action product
        $('.form-action-product .action-product').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #product-active').data('active'),
                categoryPendding = $('.card-body .analytic #product-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #product-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });


        //action color
        $('.form-action-color .action-color').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #color-active').data('active'),
                categoryPendding = $('.card-body .analytic #color-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #color-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action size
        $('.form-action-size .action-size').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #size-active').data('active'),
                categoryPendding = $('.card-body .analytic #size-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #size-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action order
        $('.form-action-order .action-order').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #order-active').data('active'),
                categoryPendding = $('.card-body .analytic #order-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #order-trash').data('trash');
            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action feedback
        $('.form-action-feedback .action-feedback').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #feedback-active').data('active'),
                categoryPendding = $('.card-body .analytic #feedback-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #feedback-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });


    } else if (getParamUrl('status') == 'trash') {
        //action user
        $('.form-action .action-user').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                userActive = $('.card-body .analytic .user-active').data('active'),
                userTrash = $('.card-body .analytic .user-trash').data('trash'),
                userPendding = 0;

            setupAjax(current, url, userActive, userTrash, userPendding);
        });

        //action page
        $('.form-action-page .action-page').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                pageActive = $('.card-body .analytic #page-active').data('active'),
                pageTrash = $('.card-body .analytic #page-trash').data('trash'),
                pagePendding = $('.card-body .analytic #page-trash').data('pendding');

            setupAjax(current, url, pageActive, pageTrash, pagePendding);
        });


        //action post

        $('.form-action-post .action-post').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #post-active').data('active'),
                categoryPendding = $('.card-body .analytic #post-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #post-trash').data('trash');
            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action product
        $('.form-action-product .action-product').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #product-active').data('active'),
                categoryPendding = $('.card-body .analytic #product-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #product-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action color
        $('.form-action-color .action-color').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #color-active').data('active'),
                categoryPendding = $('.card-body .analytic #color-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #color-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action size
        $('.form-action-size .action-size').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #size-active').data('active'),
                categoryPendding = $('.card-body .analytic #size-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #size-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action order
        $('.form-action-order .action-order').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #order-active').data('active'),
                categoryPendding = $('.card-body .analytic #order-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #order-trash').data('trash');
            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action feedback
        $('.form-action-feedback .action-feedback').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #feedback-active').data('active'),
                categoryPendding = $('.card-body .analytic #feedback-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #feedback-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

    } else if (getParamUrl('status') == 'pendding') {

        //action page
        $('.form-action-page .action-page').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                pageActive = $('.card-body .analytic #page-active').data('active'),
                pageTrash = $('.card-body .analytic #page-trash').data('trash'),
                pagePendding = $('.card-body .analytic #page-trash').data('pendding');
            setupAjax(current, url, pageActive, pageTrash, pagePendding);
        });

        //action post
        $('.form-action-post .action-post').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #post-active').data('active'),
                categoryPendding = $('.card-body .analytic #post-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #post-trash').data('trash');
            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action product
        $('.form-action-product .action-product').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #product-active').data('active'),
                categoryPendding = $('.card-body .analytic #product-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #product-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action color
        $('.form-action-color .action-color').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #color-active').data('active'),
                categoryPendding = $('.card-body .analytic #color-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #color-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action size
        $('.form-action-size .action-size').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #size-active').data('active'),
                categoryPendding = $('.card-body .analytic #size-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #size-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action order
        $('.form-action-order .action-order').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #order-active').data('active'),
                categoryPendding = $('.card-body .analytic #order-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #order-trash').data('trash');
            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

        //action feedback
        $('.form-action-feedback .action-feedback').click(function(e) {
            e.preventDefault();
            var parent = $(this).closest('.form-data-action'),
                current = $(this),
                url = parent.attr('action'),
                categoryActive = $('.card-body .analytic #feedback-active').data('active'),
                categoryPendding = $('.card-body .analytic #feedback-pendding').data('pendding'),
                categoryTrash = $('.card-body .analytic #feedback-trash').data('trash');

            setupAjax(current, url, categoryActive, categoryTrash, categoryPendding);
        });

    } else {
        $('.message').html("Yêu cầu của bạn không hợp lệ").show();
    }


});