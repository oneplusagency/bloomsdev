// (function($) {
//     $('#stylistModal').on('show.bs.modal', function(event) {
//         var img_click_modal = $(event.relatedTarget); // Button that triggered the modal
//         // alert(img_click_modal)
//         // console.log('img_click_modal: ' + JSON.stringify(img_click_modal));
//         var modal_employee = $(this);
//         var title_employee = img_click_modal.data('title_employee');
//         var employeeid = img_click_modal.data('employeeid');
//         var salonid = img_click_modal.data('salonid');
//         var img_employee = img_click_modal.data('img_employee');
//         var termine_url = img_click_modal.data('termine_url');
//         if (employeeid) {
//             var termine_url = termine_url + '/mitarbeiter/' + employeeid;
//         }
//         modal_employee.find('.modal-title>span').text(title_employee);
//         modal_employee.find('.termine-url').prop('href', termine_url);
//         //  http://localhost/f3-url-shortener/termine/salon/8/mitarbeiter/967
//         var stylebook_url = BloombaseUrl + '/stylebook/salon/' + salonid + '/mitarbeiter/' + employeeid;
//         modal_employee.find('.stylebook-url').prop('href', stylebook_url);
//         // modal_stylebook.find('.data-termine-url').prop('src', img_employee);
//         modal_employee.find('.modal-stylebook-img').css("background-image", "url(" + img_employee + ")");
//     });
// })(jQuery);


const employee_modal_body = $('#stylistModal');

// employee_modal_body.modal('show'); //pop up modal
employee_modal_body.on('click', '.modal-arrow', function(e) {
    // e.stopPropagation();
    // e.preventDefault();
    return false;
});

let employee_arr = $('#page-stylebook-gallery .prevsalo');

// alert(employee_arr.length)

function setModalContentBloom(id) {

    // console.log('MM index  : ' + id);



    var filter_selected = $("#filterSelectStylebook option:selected").data('filter');

    let filter = filter_selected || 'all';
    /**
     * fix oppo @Date: 2020-12-02 16:14:55
     * @Desc:Wenn ich jedoch auf die Stylebooks daruflclicke und mit den Pfeilen weiter gehen werden alle Personen angezeigt und nicht nur die für Darmstadt.
     */
    var zibert = false;
    if (filter != 'all') {
        // fiter-tab25
        // employee_arr = $('#page-stylebook-gallery .prevsalo');
        var digit_salon = filter.match(/\d+/g).join([]);
        // alert(digit_salon)
        if (digit_salon && digit_salon > 0) {
            var zibert = true;
            employee_arr = $('#page-stylebook-gallery .fiter-tab' + digit_salon);

            employee_arr.each(function(intIndex) {
                // var index = $(this).data('index');
                $(this).data('zibert', (intIndex + 1));
                // console.log(intIndex);
                // var new_index = $(this).data('zibert');
                // console.log(new_index);
            });
        }
    }


    var modal_employee = employee_modal_body;

    // var img_click_modal = $(event.relatedTarget); // Button that triggered the modal
    var id = '#' + id + '-prevsalo';
    var img_click_modal = $(id); // Button that triggered the modal
    // console.log('img_click_modal: ' + JSON.stringify(img_click_modal));

    // var alluser = img_click_modal.data('alluser');
    var alluser = 1;

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
    // modal_employee.find('.stylebook-url').prop('href', stylebook_url);

    // modal_employee.find('.data-termine-url').prop('src', img_employee);
    // modal_employee.find('.modal-employee-img').css("background-image", "url(" + img_employee + ")");
    modal_employee.find('.modal-stylebook-img').css("background-image", "url(" + img_employee + ")");

    /* set next and previous buttons */
    // fix oppo @Date: 2020-12-02 16:14:55  / hide if only one images
    var arr_length = employee_arr.length;
    if (alluser && arr_length > 1) {

        if (zibert == true) {
            var $zibertindexNumber = Number(img_click_modal.data('zibert'));

            // alert($zibertindexNumber)

            var $indexNumber = img_click_modal.data('index');
            // alert($indexNumber)
            // alert($zibertindexNumber)
            // alert(arr_length)

            // arr_length = 3
            // $zibertindexNumber = 1, 2 , 3

            var tt_l = ($zibertindexNumber > 1) ? $indexNumber - 1 : 0;

            tt_l = Number(tt_l);
            var tt_r = ($zibertindexNumber + 1 <= arr_length) ? $indexNumber + 1 : 0;
            tt_r = Number(tt_r);
            // alert($indexNumber)
            // alert(arr_length)
            // alert(tt_r)
            // alert(tt_l)

            $('.modal .modal-arrow').show();

            if (tt_l <= 0 && $indexNumber != 1) {
                $('.modal .modal-arrow-left').hide();
            } else {
                $('.modal .modal-arrow-left').data('index', tt_l);
            }

            if (tt_r <= 0) {
                $('.modal .modal-arrow-right').hide();
            } else {
                $('.modal .modal-arrow-right').data('index', tt_r);
            }

        } else {
            var $indexNumber = img_click_modal.data('index');
            $('.modal .modal-arrow-left').data('index', ($indexNumber - 1 >= 0) ? $indexNumber - 1 : arr_length - 1);
            $('.modal .modal-arrow-right').data('index', ($indexNumber + 1 < arr_length) ? $indexNumber + 1 : 0);

            $('.modal .modal-arrow').show();
            if ($indexNumber == 0) {
                $('.modal .modal-arrow-left').hide();
            }
        }

    } else {
        $('.modal .modal-arrow').hide();
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
        // $('#stylist-slider .carousel-inner').empty();
        var url = BloombaseUrl + "/stylebook/stylistSlider";
        $.getJSON(url, {
            employeeid: employeeid,
            title_employee: title_employee,
            salonid: salonid
        }).done(function(data) {

            if (data.length > 0) {

                $('.yakcho-nema', employee_modal_body).show();

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
                            '<a href="' + stylebook_url + '"><img src="' + val.TH_PICTURE + '" class="ss-img-thumbnail ss-img-fluid d-block w-100" alt="' + basename + '" /></a>' +
                            '</div>'
                        );
                    } else {

                        if (key > 0) {
                            // var num = Math.floor(Math.random() * 10 + 1);
                            key = key + 1
                            $('.carousel-inner', stylebook_carousel).append(
                                '<div class="carousel-item">' +
                                '<a href="' + stylebook_url + '"><img src="' + val.TH_PICTURE + '" class="ss-img-thumbnail ss-img-fluid d-block w-100" alt="' + key + ') ' + basename + '" /></a>' +
                                '</div>'
                            );
                        }
                    }
                });
            } else {

            }

            $("#stylebook-data carousel-item").removeClass("active");
            $("#stylebook-data carousel-item:first").addClass("active");

        }).fail(function(jqXMLHttpRequest, textStatus, errorThrown) {
            console.dir(jqXMLHttpRequest);
            // alert('Ajax data request failed: "' + textStatus + ':' + errorThrown + '" - see javascript console for details.');
            var err = textStatus + ", " + errorThrown;
            console.log("stylistSlide Failed: " + err);
        });

    } else {
        // clear carousel
        // $('.carousel-inner', modal_employee).html('');
    }
};


$('.modal').on('click', '.modal-arrow-left', function() { setModalContentBloom($(this).data('index')); });
$('.modal').on('click', '.modal-arrow-right', function() { setModalContentBloom($(this).data('index')); });

// https://stackoverflow.com/questions/17715274/jquery-click-function-doesnt-work-after-ajax-call
// $('#page-stylebook-gallery .prevsalo')
$('body').on('click', '#page-stylebook-gallery .prevsalo', function() {

    /**
     * fix oppo @Date: 2020-12-02 16:14:55
     * @Desc:Wenn ich jedoch auf die Stylebooks Darmstadt.
     */
    // var filter = $("#filterSelectStylebook option:selected").data('filter');
    // .js-tab25
    // alert(filter);
    var index = $(this).data('index');
    // console.log('index  : ' + index);
    setModalContentBloom(index);
    employee_modal_body.modal('show'); //pop up modal
});




// lazy Sizes Config

jQuery(document).ready(function($) {
    let stylebook_gallery_kaplichka = $('#stylebook-gallery-kaplichka');
    if (stylebook_gallery_kaplichka.size()) {

        // LazySizes
        window.lazySizesConfig = window.lazySizesConfig || {};
        // https: //github.com/patrickkunka/mixitup/issues/228
        // https://github.com/aFarkas/lazysizes/issues/181
        //only for demo in production I would use normal expand option
        window.lazySizesConfig.expand = 20;
        // window.lazySizesConfig.expand;
        // window.lazySizesConfig.expand = 225; //default is between 360-500 depending on viewport
        // window.lazySizesConfig.expFactor = 1.4; // default is 1.7
        // https://codepen.io/SitePoint/pen/egVMrd
        // https://github.com/aFarkas/lazysizes/issues/181
        // window.lazySizesConfig.loadMode = 0
        // window.lazySizesConfig.expand = 0
        // window.lazySizesConfig.expFactor = 0
        // window.lazySizesConfig.hFac = 0

        // MixItUp 3
//        let $containerMix = stylebook_gallery_kaplichka.mixItUp({
//            load: {
//                filter: 'all'
//            },
//            controls: {
//                toggleFilterButtons: false
//            },
//            selectors: {
//                target: '.mix'
//            },
//            animation: {
//                duration: 650,
//                effects: 'fade scale(0.5)'
//                    // effects: 'fade translateZ(-360px)'
//            }
//        });
        //stylebook_gallery_kaplichka.addClass('mixitup-ready');
        // Dropdown select
        $(function() {
            var $filterSelect = $('#filterSelectStylebook');
            $filterSelect.on('change', function() {
              
                if(this.value == "all"){
                    $(".stylebook-gallery-grid-img").fadeIn('slow');
                }else{
                    $(".stylebook-gallery-grid-img").fadeOut('slow');
                    $(this.value).fadeIn('slow');
                }
                
               
                $('#loadingPictures').html(
                    '<div id="alsamulainloader" class="infostylebooklooder"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
                );
                //$containerMix.mixItUp('filter', this.value);
                $('#loadingPictures').html('');
                
            });
            
            
        });

    }
});


// change active class on buttons
$('ul.simplefilter').each(function(i, buttonGroup) {
    var $buttonGroup = $(buttonGroup);
    $buttonGroup.on('click', 'li', function() {
        $buttonGroup.find('.active').removeClass('active');
        $(this).addClass('active');
    });
});

// // debounce so filtering doesn't happen every millisecond
// function debounce(fn, threshold) {
//     var timeout;
//     threshold = threshold || 100;
//     return function debounced() {
//         clearTimeout(timeout);
//         var args = arguments;
//         var _this = this;

//         function delayed() {
//             fn.apply(_this, args);
//         }
//         timeout = setTimeout(delayed, threshold);
//     };
// }