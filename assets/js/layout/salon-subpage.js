// jQuery(document).ready(function($) {
//     // [data-toggle="tooltip"]
//     $('.datytip').tooltip({
//         customClass: 'tooltip-ua',
//         animated: 'fade',
//         html: true,
//         placement: 'top'
//     });
// });

function showData(selection, div) {
    let buttons = jQuery('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    jQuery('#salon-info-data .tab-pane').fadeOut('slow');
    jQuery('#internal-map').fadeOut('slow');

    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn('slow');
}

function baseName(str) {
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if (base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}


const employee_modal_body = $('#employeeModal');
let employee_arr = $('#team-view img.prevsalo');
let employee_click = $('#team-view .toggle-modal-sam');
// employee_modal_body.modal('show'); //pop up modal
employee_modal_body.on('click', '.modal-arrow', function(e) {
    // e.stopPropagation();
    e.preventDefault();
    return false;
});


(function($) {

    function setModalContentBloom(id) {


        var modal_employee = employee_modal_body;

        // var img_click_modal = $(event.relatedTarget); // Button that triggered the modal
        var id = '#' + id + '-prevsalo';
        var img_click_modal = $(id); // Button that triggered the modal
        // console.log('img_click_modal: ' + JSON.stringify(img_click_modal));

        var title_employee = img_click_modal.data('title_employee');
        var employeeid = img_click_modal.data('employeeid');
        var salonid = img_click_modal.data('salonid');
        var img_employee = img_click_modal.data('img_employee');

        // add description 27.05.2020
        var employee_description = img_click_modal.data('employee_description');
        modal_employee.find('.employee_description').html(employee_description);

        var termine_url_base = BloombaseUrl + '/termine/salon/' + salonid
        var termine_url = termine_url_base;
        if (employeeid) {
            var termine_url = termine_url + '/mitarbeiter/' + employeeid;
        }


        modal_employee.find('.modal-title>span').text(title_employee);
        modal_employee.find('.termine-url').prop('href', termine_url);

        //  http://localhost/f3-url-shortener/termine/salon/8/mitarbeiter/967
        var stylebook_url = BloombaseUrl + '/stylebook/salon/' + salonid + '/mitarbeiter/' + employeeid;
        modal_employee.find('.stylebook-url').prop('href', stylebook_url);
        // modal_employee.find('.data-termine-url').prop('src', img_employee);
        modal_employee.find('.modal-employee-img').css("background-image", "url(" + img_employee + ")");

        /* set next and previous buttons */
        var $indexNumber = img_click_modal.data('index');
        $('.modal .modal-arrow-left').data('index', ($indexNumber - 1 >= 0) ? $indexNumber - 1 : employee_arr.length - 1);
        $('.modal .modal-arrow-right').data('index', ($indexNumber + 1 < employee_arr.length) ? $indexNumber + 1 : 0);


        /**
         * @Author: oppo @Date: 2020-05-26 18:32:30
         * @Desc: if you opened the very first employee, then the left arrow should not be displayed.
         */
        $('.modal .modal-arrow').show();
        if ($indexNumber == 0) {
            // fix oppo 12.01.2021 - missing left arrow to go back
            // $('.modal .modal-arrow-left').hide();
        }

        // stylebook-modal
        // var carous = modal_employee.find('#stylebook-modal');
        $('#stylebook-modal').data('salonid', salonid).data('employeeid', employeeid);

        var stylebook_carousel = $('#stylebook-data');

        // clear carousel
        $('#stylist-slider .carousel-inner', modal_employee).empty();
        // $("#stylebook-data").css("display", "none");

        // $('#stylebook-data', modal_employee).hide();
        var stylebook_carousel = $('#stylebook-data');


        $('.yakcho-nema', employee_modal_body).show();

        //Stylebook
        $('#stylebook-modal>strong', modal_employee).text('Stylebook von ' + title_employee);
        // https://stackoverflow.com/questions/178325/how-do-i-check-if-an-element-is-hidden-in-jquery

        // Animation complete.
        //<--- TRUE if Visible False if Hidden
        if (employeeid && salonid) {
            // if (true) {
            $('.yakcho-nema', employee_modal_body).hide();
            // clear carousel
            $('.carousel-inner', modal_employee).html('');
            // .success() and .error() has been deprecated and thus .done() and .fail() should be used instead.

            $('#stylebook-result').html('');
            $('#stylebook-result').prepend(
                '<div id="alsamulainloader">Laden...<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
            );


            // $('#stylist-slider').html('opop');
            var url = BloombaseUrl + "/stylebook/stylistSlider";
            var data_obj = {
                employeeid: employeeid,
                title_employee: title_employee,
                salonid: salonid
            };

            $.getJSON(url, data_obj).done(function(data) {

                $('#alsamulainloader').remove();
                $('#stylebook-result').html('');

                if (data.length > 0) {

                    // $('.yakcho-nema', employee_modal_body).show();
                    $('.yakcho-nema', employee_modal_body).slideDown();

                    var pict = data[0].TH_PICTURE;
                    // console.log('pict 0 : ' + pict);
                    $.each(data, function(key, val) {

                        let basename = val.UMLAUTNAME;
                        if (!basename) {
                            // basename = val.TH_PICTURE.basename();
                            basename = baseName(val.TH_PICTURE);
                        }

                        if (key == 0) {
                            $('.carousel-inner', stylebook_carousel).append(
                                '<div class="carousel-item active">' +
                                '<a href="javascript:void(0);"><img src="' + val.TH_PICTURE + '" class="ss-img-thumbnail ss-img-fluid d-block w-100" alt="' + basename + '" /></a>' +
                                '</div>'
                            );
                        } else {

                            if (key > 0) {
                                // var num = Math.floor(Math.random() * 10 + 1);
                                key = key + 1
                                $('.carousel-inner', stylebook_carousel).append(
                                    '<div class="carousel-item">' +
                                    '<a href="javascript:void(0);"><img src="' + val.TH_PICTURE + '" class="ss-img-thumbnail ss-img-fluid d-block w-100" alt="' + key + ') ' + basename + '" /></a>' +
                                    '</div>'
                                );
                            }
                        }
                    });
                } else {

                    // 25.05.2020 12:01 if not stylebook - hide
                    // $('.yakcho-nema',employee_modal_body).hide();
                    // return;
                    // $('.carousel-inner', stylebook_carousel).append(
                    //     '<div class="carousel-item active no_image_available">' +
                    //     '<img src="' + BloombaseUrl + '/assets/images/no_image_available-de.svg" class="ss-img-thumbnail ss-img-fluid d-block w-100" alt="kein Bild verfügbar" />' +
                    //     '</div>'
                    // );
                }

                $("#stylebook-data carousel-item").removeClass("active");
                $("#stylebook-data carousel-item:first").addClass("active");

            }).fail(function(jqXMLHttpRequest, textStatus, errorThrown) {
                $('#alsamulainloader').remove();
                $('#stylebook-result').html('');
                console.dir(jqXMLHttpRequest);
                // alert('Ajax data request failed: "' + textStatus + ':' + errorThrown + '" - see javascript console for details.');
                var err = textStatus + ", " + errorThrown;
                console.log("stylistSlide Failed: " + err);
            });

        } else {

            // clear carousel
            // $('.carousel-inner', modal_employee).html('');
        }

        // alert($('#stylebook-modal').data('employeeid'));
        //$('#btnUpdate').filter(":visible").animate({ width: "toggle" });


    };

    // fill modal content by id
    employee_arr.on('click', function() {
        setModalContentBloom($(this).data('index'));
        employee_modal_body.modal('show'); //pop up modal
    });
    // i class="fa fa-info-circle
    employee_click.on('click', function() {
        $(this).parent().find('img.prevsalo').trigger('click');
    });


    $('.modal').on('click', '.modal-arrow-left', function() { setModalContentBloom($(this).data('index')); });
    $('.modal').on('click', '.modal-arrow-right', function() { setModalContentBloom($(this).data('index')); });

})(jQuery);


jQuery(document).ready(function($) {

    $("#stylebook-data carousel-item").removeClass("active");
    $("#stylebook-data carousel-item:first").addClass("active");

    $('#stylebook-modal').on('click', function(e) {
        e.preventDefault();
        //  show alwais 11.03.2020
        // $('#stylebook-data').fadeToggle('800');
        return false;
    });

});

// 12.01.2021
// when clicking on tabs, the content should always be centered
jQuery(document).ready(function($) {
    $('#page-salon-info').on('click', 'li.nav-item>a', function(e) {
		jQuery('#salon-info-data .tab-pane').fadeOut('slow');
        var href = $(this).attr('href'),
            tdiv = $(href);
        if (tdiv.length) {
			jQuery(tdiv).fadeIn('slow');	
            var hh = tdiv.height(),
                chh = 100;
            // alert(hh)
            if (hh < 100) { chh = 150 }
            $('html, body').delay(200).animate({
                //scrollTop: hh + chh
                scrollTop: parseInt(jQuery(this).offset().top)
            }, '600');

        }
    });
    
    $('#slider-images').carousel({ 
        interval: 0.5, 
        cycle: true 
    });
});