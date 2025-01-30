jQuery(function () {
    'use strict';

    (function ($, window, document, undefined) {
        $('#kontaktformularForm')
            .on('click', '.btn-add', function (e) {
                e.preventDefault();

                var controlForm = $('.upload-btn:first'),
                    currentEntry = $(this).parents('.entry:first');
                    let $label_span = $(this).prev('label').find('span');


                var valchk = currentEntry.find('input.alexzol').val();
                if (!valchk) {
                    var add_error = $label_span.data('add-error');
                    // data-add-error
                    $label_span.html(add_error);
                    return false;
                }

                var count = controlForm.find('input.alexzol').length;
                // limit 5 files
                if (count > 4) {
                    return false;
                }
                let clone = $(currentEntry.clone());
                let newEntry = clone.appendTo(controlForm);

                newEntry.find('input').val('');
                var span_caption = $label_span.data('span-caption');
                newEntry.find('span.mwpl-upload-btn').text(span_caption);
                controlForm
                    .find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add')
                    .addClass('btn-remove')
                    .removeClass('btn-success')
                    .addClass('btn-danger')
                    .html('<span class="fa fa-trash"></span>');
            })
            .on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            })
            .on('click', 'label.form-control', function (e) {
                $(this).prev('input.alexzol:first').click();

                e.preventDefault();
                return false;
            })
            .on('change', 'input.alexzol', function (e) {
                var $input = $(this),
                    $label = $input.next('label'),
                    labelVal = $label.html();
                var fileName = '';
                if (e.target.value) fileName = e.target.value.split('\\').pop();

                if (fileName) $label.find('span').html(fileName);
                else $label.html(labelVal);
                e.preventDefault();
                return false;
            });
        // $('#fileinput').change(function() {
        //     $('#selected_filename').text($('#fileinput')[0].files[0].name);
        //   });
    })(jQuery, window, document);

    $('#kontaktformularForm').submit(function (event) {
        event.preventDefault();

        $('#kontact_kontakt_status').html('Laden...');
        let data = new FormData(document.getElementById('kontaktformularForm'));
        //   var files = $('#fileStaff')[0].files[0];
        //   data.append('image', files);

        //   $.each($(".upload-btn input[type='file']")[0].files, function(i, file) {
        //     data.append('file[]', file);
        // });

        // let input_file = document.querySelector('input[type="file"]');
        // Array.from(input_file.files).forEach((f) => {
        // 	data.append('file[]', f);
        // });

        var ins = $(".upload-btn input[type='file']")
            .map(function () {
                return this.files;
            })
            .get();

        for (var x = 0; x < ins; x++) {
            if (x > 4) {
                break;
            }
            data.append('file', ins[x][0]);
        }

        // add oppo
        // for (let [key, value] of data) {
        //     console.log(`${key}: ${value}`)
        // }
        // for (let keyValuePair of data.entries()) {
        //     console.log(keyValuePair); //has form ['name','Alex Johnson']
        // }

        $.ajax({
            url: BloombaseUrl + '/azubikontakt/sendinfo',
            type: 'post',
            data: data,
            dataType: 'text',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#kontact_sendbtn').buttonSalo('loading');
            },
            success: function (response) {
                // alert(response)
                $('#kontact_kontakt_status').html(response);
                $('#bloom_kontakt_submit').removeAttr('disabled');
                if (
                    response !=
                    '<div class="alert alert-danger rounded-0" role="alert">Falsches Dateiformat</div>'
                ) {
                    console.log('Resp ' + response);
                    $('#bloom_kontakt_submit').buttonSalo('reset');
                }
            },
            error: function (data, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log(data);
                console.log(data.responseText);
                $('#kontact_kontakt_status').html(
                    '<div class="alert alert-danger rounded-0" role="alert">Something went wrong.Please try after some time.</div>'
                );
                console.log('Status: ' + textStatus);
                console.log('Error: ' + errorThrown);
                $('#bloom_kontakt_submit').buttonSalo('reset');
            },
        });
        return false;
    });
});
