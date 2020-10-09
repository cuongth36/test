$(document).ready(function() {
    var editor_config = {
        // path_absolute: "http://localhost/LaravelTutorial/unimart/",
        // path_absolute: "https://cuong-th36.herokuapp.com/",
        path_absolute: "https://cuong9x.herokuapp.com/",
        selector: "textarea.content",
        height: '450px',
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullpage | ' +
            'forecolor backcolor emoticons | help',
        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media |forecolor backcolor",
        relative_urls: false,
        menubar: 'favs file edit view insert format tools table help',
        file_browser_callback: function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file: cmsURL,
                title: 'Filemanager',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no"
            });
        }
    };

    tinymce.init(editor_config);




    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper

    var x = 1; //Initial field counter is 1

    var fieldHTML = $('.field_wrapper .append-item').html();

    $(addButton).click(function() {
        if (x < maxField) {

            x++;
            $(wrapper).append(fieldHTML);
        }
    });

    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e) {
        e.preventDefault();
        $('.remove_button').closest('.field_wrapper').find('.attr-item').not(':first').last().remove();
        // let parent = $(this).closest('.attr-item');
        // parent.not(':first').remove();
        x--;
    });

    let startDate = $('#start-date').val();
    let endDate = $('#end-date').val();
    $('input[name="dateranger"]').daterangepicker({ autoApply: true, startDate: startDate, endDate: endDate },
        function(start, end) {
            // $('#start-date').val(start.format('YYYY/MM/DD'));
            // $('#end-date').val(end.format('YYYY/MM/DD'));

            $('#start-date').val(start.format('MM/DD/YYYY'));
            $('#end-date').val(end.format('MM/DD/YYYY'));
        });
    //change the selected date range of that picker
    $('input[name="dateranger"]').data('daterangepicker').setStartDate(startDate);
    $('input[name="dateranger"]').data('daterangepicker').setEndDate(endDate);

    //set width, height canvar
    let myChart = $('#myChart')[0];
    myChart.width = 900;
    myChart.height = 650;
    $('#myChart').css('margin', 'auto');
    //Ajax revenue sell 

    // $('#form-action-statistical').submit(function(e) {
    //     e.preventDefault();
    //     let current = $(this);
    //     let url = current.attr('action');
    //     let startDate = $('#start-date', current).val();
    //     let endDate = $('#end-date', current).val();
    //     let token = $('meta[name="csrf-token"]').attr('content');
    //     let data = { startDate: startDate, endDate: endDate, _token: token };
    //     let action = url;
    //     $('.loader-absolute').show();
    //     $('.loader-wrapper').show();
    //     $.ajax({
    //         url: action,
    //         method: 'POST',
    //         data: data,
    //         dataType: 'json',
    //         success: function(data) {
    //             console.log(data.startDate);
    //             console.log(data.endDate);
    //             if (typeof data == 'object') {
    //                 if (data.status == 1) {
    //                     $('#date-ranger', current).val(data.startDate + ' - ' + data.endDate);
    //                     $('#total-statistical').text(data.total);
    //                 }
    //             }

    //             $('.loader-wrapper').hide();
    //             $('.loader-absolute').hide();
    //         },
    //         error: function(xhr, ajaxOption, throwError) {
    //             alert(xhr.status);
    //             alert(throwError);
    //         }
    //     });
    // });

});