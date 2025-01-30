(function($) {
    function saveOrderbanner_field() {
        var data = $('#banner_field tbody tr.moksha')
            .map(function() {
                return $(this).data('tr');
            })
            .get();

        $.ajax({
            url: BloombaseUrl + '/bannerAkademieAdmin/setPosition',
            type: 'post',
            data: {
                ids: data,
            },
            success: function(data) {
                console.log(data);
            },
            error: function() {
                alert('AJAX has stopped responding. Please reload the page.');
            },
        });
    }

    function interval_field() {
        var conttable = $('#banner_field');
        // Show Input element
        conttable.on('click', '.edititem', function() {
            $('.txtedit').hide();
            $('.updatebutton').hide();
            $(this).next('.txtedit').show().focus();
            $(this).next('.txtedit').next('.updatebutton').show();
            $(this).hide();
        });

        conttable.on('click', '.updatebutton', function() {
            var cogo = $(this).prev('.txtedit', conttable);

            // Get edit id, field name and value
            var id = cogo.data('id');
            var split_id = id.split('_');
            var field_name = split_id[0];
            var edit_id = split_id[1];
            var value = cogo.val();
            value = parseFloat(value);

            if (value > 0) {
                // Hide Input element
                $(this).hide();
                cogo.hide();

                // Hide and Change Text of the container with input elmeent
                cogo.prev('.edititem', conttable).show();
                cogo.prev('.edititem', conttable).text(value);

                // Sending AJAX request

                $.ajax({
                    url: BloombaseUrl + '/bannerAkademieAdmin/interval',
                    type: 'post',
                    data: { field: field_name, value: value, id: edit_id },
                    success: function(response) {
                        console.log('Save successfully');
                    },
                    error: function(jqxhr, textStatus, error) {
                        // add whatever debug you want here.
                        var err = textStatus + ', ' + error;
                        console.log('Failed: ' + err);
                    },
                });
            } else {
                alert('Wert muss größer als 0 sein');
            }
        });
    }

    $(document).ready(function() {
        //  url: BloombaseUrl + '/termine/terminFinden',
        fetch_banner_data();

        function fetch_banner_data() {
            var action = 'fetch';
            $.ajax({
                url: BloombaseUrl + '/bannerAkademieAdmin/banner',
                method: 'POST',
                data: { action: action },
                success: function(data) {
                    $('#image_data').html(data);

                    $('#banner_field tbody').dragsort({
                        itemSelector: 'tr',
                        dragSelector: '#banner_field tbody .fa-arrows',
                        dragBetween: false,
                        dragEnd: saveOrderbanner_field,
                        scrollContainer: '#banner_field',
                    });

                    interval_field();
                },
                error: function(jqxhr, textStatus, error) {
                    // add whatever debug you want here.
                    var err = textStatus + ', ' + error;
                    console.log('Failed: ' + err);
                },
            });
        }

        // Insert Images

        $('#add_banner').click(function() {
            $('#imageModalbanner').modal('show');
            $('#image_form')[0].reset();
            $('.modal-title').text('Banner Bild einfügen');
            $('#image_id').val('');
            $('#action').val('insert');
            $('#insert').val('Einfügen');
        });


        // https://stackoverflow.com/questions/37441422/server-response-with-error-500-on-ajax-file-post
        $('#image_form').on('submit', function(event) {
            event.preventDefault();
            var image_name = $('#image').val();
            if (image_name == '') {
                alert('Bitte wählen Sie Bild');
                return false;
            } else {
                var extension = $('#image').val().split('.').pop().toLowerCase();
                if (
                    jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1
                ) {
                    alert('Ungültige Bilddatei');
                    $('#image').val('');
                    return false;
                } else {

                    let formData = new FormData(this);
                    // let formData = new FormData();
                    // formData.set('carousel_interval', 2000);
                    // formData.set('action', 'insert');
                    // // var files = $('#image').prop('files')[0];
                    // var files = $('#image')[0].files[0];
                    // formData.append('image', files);
                    formData.append('controller', 'bannerAkademieAdmin');
                    // image: {}
                    // banner-akademie.js:141 carousel_interval: 2000
                    // banner-akademie.js:141 action: insert
                    // banner-akademie.js:141 image_id:
                    // banner-akademie.js:141 type: akademie-banner


                    // formData.set('image', null);
                    // formData.set('image_id', null);
                    for (var pair of formData.entries()) {

                        if (pair[0] == 'image') {
                            console.log(pair[0] + ': ' + JSON.stringify(pair[1]));
                        } else {
                            console.log(pair[0] + ': ' + pair[1]);
                        }

                    }

                    $.ajax({
                        // url: BloombaseUrl + '/bannerAkademieAdmin/banner?test=1',
                        url: BloombaseUrl + '/bannerAkademieAdmin/banner',
                        // timeout: 6000,
                        // dataType: 'json',
                        dataType: 'text',
                        type: 'POST',
                        // contentType: 'application/json',
                        data: formData,
                        contentType: false,
                        processData: false,
                        cache: false,
                        // headers: {
                        //     'Content-Type': 'multipart/form-data',
                        //     'Authorization': `Basic RGV2ZWxvcG1lbnQ6MXA4NWFnMDY=`,

                        // },
                        // headers: { Authorization: 'Basic RGV2ZWxvcG1lbnQ6MXA4NWFnMDY=' },
                        success: function(data) {
                            alert(data);
                            fetch_banner_data();
                            $('#image_form')[0].reset();
                            $('#imageModalbanner').modal('hide');
                        },
                        error: function(jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            // jQuery('#btn_submit').removeClass('hidden');
                            // jQuery('#loaderClass').addClass('hidden');
                            console.log(msg);
                        },
                    });
                }
            }
        });

        // DELETE
        $(document).on('click', '.delete', function() {
            var image_id = $(this).attr('id');
            var action = 'delete';
            if (confirm('Möchten Sie dieses Bild wirklich entfernen?')) {
                $.ajax({
                    url: BloombaseUrl + '/bannerAkademieAdmin/banner',
                    method: 'POST',
                    data: { image_id: image_id, action: action },
                    success: function(data) {
                        alert(data);
                        fetch_banner_data();
                    },
                    error: function(jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        // jQuery('#btn_submit').removeClass('hidden');
                        // jQuery('#loaderClass').addClass('hidden');
                        console.log(msg);
                    },
                });
            } else {
                return false;
            }
        });

        // YOUTUBE
        $('#youtube_form').on('submit', function(event) {
            event.preventDefault();
            var youtube_url_name = $('#youtube_url').val();
            if (youtube_url_name == '') {
                alert('Bitte wählen Sie Youtube URL');
                return false;
            } else {
                $.ajax({
                    url: BloombaseUrl + '/bannerAkademieAdmin/banner',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        fetch_banner_data();
                        $('#youtube_form')[0].reset();
                        $('#youtubeModal').modal('hide');
                    },
                    error: function(jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        // jQuery('#btn_submit').removeClass('hidden');
                        // jQuery('#loaderClass').addClass('hidden');
                        console.log(msg);
                    },
                });
            }
        });
    });
})(jQuery);