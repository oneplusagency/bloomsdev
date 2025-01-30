
jQuery(document).bind('DOMNodeInserted', function (e) {

    const $tooltip = jQuery('[data-toggle="tooltip"]');
    $tooltip.tooltip({
        html: true,
        trigger: 'click',
        placement: 'bottom',
    });
    $tooltip.on('show.bs.tooltip', () => {
        jQuery('.tooltip').not(this).remove();
    });


});
//
// // https://itchief.ru/lessons/javascript/javascript-sessionstorage-and-localstorage
// https://medium.com/@stasonmars/%D0%BA%D0%B0%D0%BA-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0%D1%82%D1%8C-%D1%81-localstorage-%D0%B2-javascript-5aad737535d4
// https://www.w3schools.com/jsref/prop_win_localstorage.asp


/**
 * @Author: oppo (1plus.de) @Date: 2020-05-29 13:00:43
 * @Desc:  new POP
 */

const employee_modal_body = $('#termineDataModal');


$('body #team-view .toggle-modal-sam').on('click', function () {
    $(this).parent().find('img.prevsalo').trigger('click');
});

// employee_modal_body.modal('show'); //pop up modal
employee_modal_body.on('click', '.modal-arrow', function (e) {
    // e.stopPropagation();
    // e.preventDefault();
    return false;
});


function toggleUnavailable() {
    console.log("toggleUnavailable");
}

function setModalContentBloom(id) {

    let employee_arr = $('#termine-content .prevsalo');
    var modal_employee = employee_modal_body;

    // var img_click_modal = $(event.relatedTarget); // Button that triggered the modal
    var id = '#' + id + '-prevsalo';
    var img_click_modal = $(id); // Button that triggered the modal
    // console.log('img_click_modal: ' + JSON.stringify(img_click_modal));

    var alluser = img_click_modal.data('alluser');

    var title_employee = img_click_modal.data('title_employee');

    // var employeeid = img_click_modal.data('employeeid');
    var employeeid = img_click_modal.data('mid'); // !!! mid here not employeeid


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
    modal_employee.find('.modal-employee-img').html("<img style='width:100%;height:auto;' src='" + img_employee + "'/>");

    /* set next and previous buttons */
    if (alluser) {
        var $indexNumber = img_click_modal.data('index');
        $('.modal .modal-arrow-left').data('index', ($indexNumber - 1 >= 0) ? $indexNumber - 1 : employee_arr.length - 1);
        $('.modal .modal-arrow-right').data('index', ($indexNumber + 1 < employee_arr.length) ? $indexNumber + 1 : 0);

        $('.modal .modal-arrow').show();
        if ($indexNumber == 0) {
            $('.modal .modal-arrow-left').hide();
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


        // $('#stylebook-result').html('');
        // $('#stylebook-result').prepend(
        //     '<div id="alsamulainloader">Laden...<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
        // );

        // $('#stylist-slider .carousel-inner').empty();
        var url = BloombaseUrl + "/stylebook/stylistSlider";
        $.getJSON(url, {
            employeeid: employeeid,
            title_employee: title_employee,
            salonid: salonid
        }).done(function (data) {

            // $('#alsamulainloader').remove();
            // $('#stylebook-result').html('');

            if (data.length > 0) {

                $('.yakcho-nema', employee_modal_body).slideDown();

                var pict = data[0].TH_PICTURE;
                // console.log('pict 0 : ' + pict);
                $.each(data, function (key, val) {

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

            }

            $("#stylebook-data carousel-item").removeClass("active");
            $("#stylebook-data carousel-item:first").addClass("active");

        }).fail(function (jqXMLHttpRequest, textStatus, errorThrown) {
            console.dir(jqXMLHttpRequest);
            // alert('Ajax data request failed: "' + textStatus + ':' + errorThrown + '" - see javascript console for details.');
            var err = textStatus + ", " + errorThrown;
            console.log("stylistSlide Failed: " + err);

            // $('#alsamulainloader').remove();
            // $('#stylebook-result').html('');
        });

    } else {
        // clear carousel
        // $('.carousel-inner', modal_employee).html('');
    }
};

$.validator.addMethod("noPlusSign", function (value, element) {
    return this.optional(element) || value.indexOf("+") < 0;;
}, 'Ungültige E-Mail-Adresse');


$('.modal').on('click', '.modal-arrow-left', function () { setModalContentBloom($(this).data('index')); });
$('.modal').on('click', '.modal-arrow-right', function () { setModalContentBloom($(this).data('index')); });

// https://stackoverflow.com/questions/17715274/jquery-click-function-doesnt-work-after-ajax-call
// $('#termine-content .prevsalo')
$('body').on('click', '#termine-content .termine-data-box .prevsalo', function () {
    // alert("success");
    setModalContentBloom($(this).data('index'));
    employee_modal_body.modal('show'); //pop up modal
});


// *********************** TERMINE BEGIN   ***********************
$('body').on('click', '#termine-content .termine-data-box .notAvailable', function (e) {
    // $('#termineDataModal').modal('hide');
    e.stopPropagation();
    // e.preventDefault();
    return false;
});


/**
 * @param {object|string} field
 * @param {string} value
 * @private
 */
function _setLocalStorageValue(field, value, set_empty) {
    if (typeof set_empty === 'undefined') {
        set_empty = true;
    }
    if (typeof Storage !== 'undefined') {
        //PM (03.04.2018) bloom`s // Webseite // Terminbuchung // Smartphone-Optimierung :: bug fix / iOS7-iPhone4
        var key;
        try {
            if (typeof field === 'string') {
                if (set_empty || value != '') {
                    //PM (03.04.2018)
                    key = field;
                    // alert(key)
                    localStorage.setItem(key, value);

                    //localStorage.setItem(field, value);
                }
            } else {
                if (set_empty || field.val() != '') {
                    //PM (03.04.2018)
                    key = field.attr('id');
                    // alert(key)
                    value = field.val();
                    localStorage.setItem(key, value);
                    //localStorage.setItem(field.attr('id'), field.val());
                }
            }
        } catch (e) {
            if (key !== undefined) APPOINTMENT_MAKER.set(key, value);
        }
    }
}

/**
 * @param {string} field
 * @private {string}
 */
function _getLocalStorageValue(field) {
    if (typeof Storage !== 'undefined') {
        return localStorage.getItem(field) || APPOINTMENT_MAKER.get(field); //PM (03.04.2018) bloom`s // Webseite // Terminbuchung // Smartphone-Optimierung :: bug fix / iOS7-iPhone4

        //return localStorage.getItem(field);
    } else {
        return $('#' + field).val();
    }
}

/**
 * sets the value of the local storage
 * @param {string} field
 * @returns {Boolean}
 */
function _restoreField(field) {
    var value = _getLocalStorageValue(field);
    // alert(value)
    if (
        value ===
        $('#' + field + ' option')
            .first()
            .val()
    ) {
        return false;
    }
    if ($('#' + field + " option[value='" + value + "']").length > 0) {
        $('#' + field).val(value);
        return true;
    }
    return false;
}


let service_field_id = 'servicePackageField';

function _getDienstleistungId() {

    var service_field_id = 'servicePackageField';

    var dienstleistungId = $('body #servicePackageField option:selected').data('childid');

    var hairlength = $.totalStorage.getItem('hairlength');
    // alert('hairlength: ' + hairlength + ' / dienstleistungId: ' + dienstleistungId)
    if (dienstleistungId && hairlength == true) {
        // alert(' true ! dienstleistungId: ' + dienstleistungId)
        return dienstleistungId;

    } else {

        if (!dienstleistungId || hairlength !== 'true') {
            dienstleistungId = $('body #servicePackageField').val();
            // alert(' prev dienstleistungId: ' + dienstleistungId)
            // return dienstleistungId;

        }
        //console.log('dienstleistungId 1 - ', dienstleistungId);
        if (!dienstleistungId) {
            dienstleistungId = _getLocalStorageValue(service_field_id);
            // alert(' New dienstleistungId: ' + dienstleistungId)
        }
        //console.log('dienstleistungId 2 - ', dienstleistungId);
        return dienstleistungId;
    }
}
/****************   OLD CODE   **************** */

let slick_options = {
    // centerMode: false,
    // centerPadding: '60px',
    slidesToShow: 11,
    slidesToScroll: 11,
    // variableWidth: true,
    // centerMode: true,
    infinite: false,
    responsive: [{
        breakpoint: 1440,
        settings: {
            slidesToShow: 6,
            slidesToScroll: 6
        }
    },
    {
        breakpoint: 1024,
        settings: {
            slidesToShow: 4,
            slidesToScroll: 4
        }
    },
    {
        breakpoint: 600,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 2
        }
    },
    {
        breakpoint: 480,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 1
        }
    }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
    prevArrow: '<button type="button" class="slick-prev"><svg width="44" height="60"><polyline points="30 10 10 30 30 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline></svg></button>',
    nextArrow: '<button type="button" class="slick-next"><svg width="44" height="60"><polyline points="14 10 34 30 14 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline></svg></button>'
};

// datepicker
var selectAppointmentNavigation;


const millisInDaynow = 24 * 60 * 60 * 1000;
// const dayDelta = 7;
const dayDelta = 0;

jQuery(document).ready(function ($) {

    $.datepicker.setDefaults($.datepicker.regional['de']);

    let sevenday = new Date().getDay + dayDelta;
    let valueDate = $.totalStorage('datepicker');

    if (!valueDate) {
        let d = Date.parse(sevenday);
        defaultDate = new Date(d);
    } else {

        let d = Date.parse(valueDate);
        defaultDate = new Date(d);
    }


    //    Date.prototype.valid = function() {
    //        return isFinite(this);
    //    }
    //    let ikatz = new Date(defaultDate);
    //    if (!ikatz.valid()) {
    //        let d = Date.parse(sevenday);
    //        defaultDate = new Date(d);
    //        $.totalStorage('datepicker', defaultDate);
    //    }




    let qw = $('#datepicker').datepicker({

        closeText: "Schließen",
        prevText: "&#x3C;Zurück",
        nextText: "Vor&#x3E;",
        currentText: "Heute",
        monthNames: ["Januar", "Februar", "März", "April", "Mai", "Juni",
            "Juli", "August", "September", "Oktober", "November", "Dezember"
        ],
        monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun",
            "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"
        ],
        dayNames: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"],
        dayNamesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
        dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],

        maxDate: '+10w',
        dateFormat: 'DD, dd. MM yy',
        altFormat: 'yy-mm-dd',
        altField: '#thealtdate',
        regional: ['de'],
        numberOfMonths: 1,
        showButtonPanel: false,
        minDate: 0,

        onSelect: function (dateText, inst) {


            if (dateText) {
                $('#datepicker-error').remove();
            }
            const dateRaw = dateText.toString('YYYY-MM-DD');

            var iso_date = $('#thealtdate').val();

            var selectedDate = new Date(iso_date);
            $.totalStorage('datepicker', selectedDate);

            //var $valid = jQuery('#page-termine #termineAjaxformWizard').valid();
            var $valid = 1;
            if ($valid) {
                $('#termin-finden').show();
                $('#termine-ajax').html('');

            }

        }
    }).datepicker("setDate", defaultDate);

    if (defaultDate) {
        let todayDate = new Date();
        todayDate.setHours(0, 0, 0, 0);
        let diffDays = parseInt((defaultDate - todayDate) / millisInDaynow, 10);

        if (diffDays < 0 || (isNaN(diffDays))) {
            qw.datepicker('setDate', new Date().getDay + dayDelta);
        }
    }


    // **** TEST ****
    $(document).ready(function () {
        //href="#pills-terminbuchung"
        // ActivTabTermin('pills-terminbuchung');
        // ActivTabTermin('pills-fertig');
        // 3. Meine Daten
        // ActivTabTermin('pills-personaliche-daten');
    });

    function ActivTabTermin(id) {
        // $('.nav-pills li.nav-item > a[href="#' + id + '"]').trigger('click');
        $('.nav-pills li.nav-item > a[href="#' + id + '"]').tab('show');
    }

    function fillTabFour() {

        var counter = 0;
        let meine_buchung = $('#meine-buchung'); // first id for TAB  4
        if (counter == 0) {

            let available_tab_arr = $.totalStorage('available_termine');
            let user_data_tab_arr = $.totalStorage('user_data_tab_three');
            // console.log('available -> 4.Meine Buchung: ' + JSON.stringify(available_tab_arr));

            if (available_tab_arr instanceof Array && user_data_tab_arr instanceof Array) {

                let tab3 = available_tab_arr[0];
                let tab4 = user_data_tab_arr[0];

                let vorname = tab4['vorname'];
                let nachname = tab4['nachname'];
                let email = tab4['email'];
                let mobilenumber = tab4['mobilenumber'];

                let ComingSoon = false;
                if (ComingSoon) {
                    //(after rain)
                    meine_buchung.html('We’re working hard to make this website available again on Thursday .... You will then find a new design and our new collection!');
                } else {
                    // ****  SET DATA FOR  4. Meine Buchung   *****
                    $('#persoenliche-daten .personal-name', meine_buchung).text(vorname + ' ' + nachname);
                    $('#persoenliche-daten .personal-phone', meine_buchung).text(mobilenumber);
                    $('#persoenliche-daten .personal-email', meine_buchung).text(email);
                    //  from storage /
                    // SALON
                    $('#meinebuchungsalonAddress', meine_buchung).text(tab3['salonAddress']);
                    // $('#meinebuchungTerminDate', meine_buchung).text(tab3['termineDate']);
                    $('#meinebuchungTerminDate', meine_buchung).text(tab3['wochenTagFertig']);
                    $('#meinebuchungTerminTime', meine_buchung).text(tab3['termineTime']);
                    // SERVISE
                    $('#meinebuchungTerminServiseName', meine_buchung).text(tab3['dienstleistungName']);
                    $('#meinebuchungDienstleistungData i.fa', meine_buchung).prop('title', tab3['dienstleistungDescription']);
                    // USER
                    $('#meinebuchungMitarbeiterImg', meine_buchung).prop('src', tab3['mitarbeiterFile']);
                    $('#mitarbeiterFirstName', meine_buchung).text(tab3['mitarbeiterName']);
                }

                counter += 1;
            } else {
                $('#page-termine').bootstrapWizard('show', 1);
                // meine_buchung.empty();
            }

        }
    }


    // Wizard With Form Validation

    $('#page-termine').bootstrapWizard({

        nextSelector: '.termine-skache .btn-next',
        previousSelector: '.termine-skache .btn-previous',
        firstSelector: '.termine-skache .button-first',
        lastSelector: '.button-last',


        onTabShow: function (tab, navigation, index) {
            // console.log('onTabShow');

            tab.prevAll().addClass('done');
            tab.nextAll().removeClass('done');
            tab.removeClass('done');

            var $total = navigation.find('li').length;
            var $current = index + 1;
            // first tab
            // Weiter

            // HIDE AVALIBLE
            if ($current <= 1) {
                $('#page-termine').removeClass('hide');
                $("#termin-finden-li").removeClass('hide');
                $('#termine-ajax').removeClass('hide');

            } else {
                $('#page-termine #termin-finden-li').addClass('hide');
                $('#termine-ajax').addClass('hide');
            }
            // return true;

            if ($current == 4) {

                // ('#page-termine').bootstrapWizard('show', 5);
                // return;
                // let chk_arr = $.totalStorage('available_termine');
                // let tst = $.isArray(chk_arr);
                // alert(tst)
                fillTabFour();

            }

            if ($current == 5) {
                // ddressFive
                let salonaddress_map = $('#dateConfirm #ConfirmTerminAddress').text();
                var map_url = 'https://maps.google.com/maps?q=' + encodeURIComponent(salonaddress_map) + '&t=&z=13&ie=UTF8&iwloc=&output=embed';
                $(window).resize(function () {
                    // $('#mapframe').attr("src", map_url);
                    $('#output-map').html('<iframe id="mapframe" class="gray-map" src="' + map_url + '" width="100%" height="300px" frameborder="0" style="border:0;" allowfullscreen=""></iframe>');
                });

            }

            if ($current >= 5) {
                // 5. Fertig 01.07.2020
                $('#termine-ajax').html('');
            }

            var $percent = ($current / $total) * 100;
            var progressBar = $('#progressWizard').find('.progress-bar');
            progressBar.css('width', $percent + '%');
        },
        onTabClick: function (tab, navigation, index) {
            // console.log('onTabClick');
            // alert('on tab click disabled');
            return false;
        },
        onNext: function (tab, navigation, index) {
            // console.log('onNext');
            // return true;

            // test
            // $('#page-termine').bootstrapWizard('show', 3);
            // return true;
            if (index == 1) {
                /**
                 * @Date: 2020-08-24 11:29:09  if back then we do not load ajax
                 */
                return false;
                var $valid = jQuery('#page-termine #termineAjaxformWizard').valid();
                if (!$valid) {
                    return false;
                }
                return true;
            }
        },
        // add oppo test
        onShow: function (tab, navigation, index) {
            // console.log('onShow');
        },
        onPrevious: function (tab, navigation, index) {
            // console.log('onPrevious');

            var $current = index + 1;
            // first tab
            // Weiter

            if ($current == 1) {
                //btn-previous
                $('#page-termine #termin-finden-li').removeClass('hide');
                // Termin finden
                // Show btn Termin finden
                // $('#termin-finden').show();
            }
        },
        onLast: function (tab, navigation, index) {
            // console.log('onLast');
        }
    });
});



$(document).ready(function () {
    // let t_arrr = $.totalStorage('available_termine')
    // let uu = getIemtByStorage(t_arrr, 'dienstleistungId');
    // console.log('uu:' + JSON.stringify(t_arrr));

    // https://javascript.ru/basic/array
    // $('#page-termine').bootstrapWizard('next');
    // [{"salonId":22,"termineDate":"2020-02-05","termineTime":"11:45 AM","mitarbeiterId":1150,"dienstleistungId":"67","dienstleistungName":"Foliensträhnen pur ","wochenTagFertig":"�?ереда 05 лютий 2020","mitarbeiterName":"Sina Kupfer","mitarbeiterFile":"http://localhost/f3-url-shortener/assets/images/employeeimage/emp_image.jpg"}]

    // $.totalStorage.deleteItem $.totalStorage.setItem $.totalStorage.getItem
});
// save to Form Cache **Meine Daten****
$(document).ready(function () {
    $('#persDataConfirm').FormCache();
});

// $('body').on('click', '.nav-disabled-click', function(e) {
//     // e.preventDefault();
//     // return false;
// });

// (function($) {})(jQuery);

$('#termin-finden').on('click', function (e) {
    $(this).on('click', function () {
        return false;
    }); //this works.
    e.preventDefault();
    $('#termineAjaxformWizard').submit();
});

$('#termin-confirm').on('click', function (e) {
    $(this).on('click', function () {
        return false;
    }); //this works.
    e.preventDefault();
    // $('#termin-confirm').buttonSalo('loading');
    $('#persDataConfirm').submit();
});

// submit   // 3. Meine Daten
(function ($) {
    'use strict';
    $.validator.addMethod(
        'validcb1',
        function (value) {
            if ($('input:checked').length > 0) return true;
            else return false;
        },
        ''
    );
    $.validator.addMethod(
        'lettersonly',
        function (value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        },
        'Briefe nur bitte'
    ); // Letters only please

    $.validator.addMethod(
        'RufnummerDESimple',
        function (value, element) {
            value = value.replace(/\s+/g, '');
            return value.match(/^(0|\+49|0049)(15|16|17)\d+$/);
        },
        'Geben Sie gültige Telefonnummer ein'
    );

    $.validator.addMethod(
        'RufnummerDE',
        function (value, element) {
            return checkDePhoneNumber(value);
        },
        'Bitte geben Sie eine deutsche Handynummer ein'
    );

    // ** VALIDATE persDataConfirm form (TAB 3 ) ***
    $('#persDataConfirm').validate({
        // errorElem: 'div',
        ignore: ':hidden',
        errorClass: 'validation-error-label',
        rules: {
            vorname: {
                minlength: 3,
                maxlength: 15,
                required: {
                    depends: function () {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            nachname: {
                minlength: 3,
                maxlength: 15,
                required: {
                    depends: function () {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            email: {
                required: {
                    depends: function () {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                noPlusSign: true,
                email: true,
                minlength: 5
            },
            emailConfirm: {
                email: true,
                equalTo: '#email'
            },

            mobilenumber: {
                RufnummerDE: true,
                required: {
                    depends: function () {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
                // minlength: 10,
                // maxlength: 12,
                // digits: true
            }
        },
        messages: {
            emailConfirm: {
                equalTo: 'Die Eingabe Ihrer E-Mail-Adresse stimmt nicht überein'
            },
            mobilenumber: {
                required: 'Bitte geben Sie eine deutsche Handynummer ein',
                RufnummerDE: 'Bitte geben Sie eine deutsche Handynummer ein'
                // minlength: "Handynummer sollte eine 10-stellige Zahl sein",
                // maxlength: "Handynummer sollte eine 10-stellige Zahl sein",
                // digits: "Mobil sollten nur Zahlen enthalten"
            }
        },
        lang: 'de', // or whatever language option you have.
        highlight: function (element, errorClass) {
            $(element)
                .closest('.tt-form-group')
                // .removeClass('has-success')
                .addClass(errorClass);
        },
        success: function (element, errorClass) {
            $(element)
                .closest('.tt-form-group')
                .removeClass(errorClass);

            // $(element)
            //     .closest('.tt-form-group').find('label.validation-error-label').empty();

            //validation-error-label
        },
        submitHandler: function (form) {
            // FROM   tab 3. Meine Daten
            // https://dikarka.ru/javascript/10.shtml
            // https://learn.javascript.ru/array-methods
            // https://itchief.ru/javascript/associative-arrays  !!!
            let persDataConfirm = $('#tab3-confirm-meinedaten');
            let vorname = $('#vorname', persDataConfirm).val();
            let nachname = $('#nachname', persDataConfirm).val();
            let email = $('#email', persDataConfirm).val();
            let mobilenumber = $('#mobilenumber', persDataConfirm).val();

            // FROM   tab 2. Mein Termin
            // Bei Ausfall des Mitarbeiters:
            // type=radio Vertretung durch anderen Kollegen  or Einen neuen Termin vereinbaren
            // let vertretung_new = $('input[type=radio][name=vertretung]');
            let vertretung_new = $.totalStorage('vertretung_new');
            // $.totalStorage('vertretung', vertretung_new);

            // FROM   tab 3. Meine Daten
            // let vertretung = localStorage.getItem('vertretung');
            let vertretung = vertretung_new;
            // $.totalStorage('vertretung');
            let step_three = [{
                vertretung: vertretung,
                vorname: vorname,
                nachname: nachname,
                email: email,
                mobilenumber: mobilenumber
            }];
            // https://developer.mozilla.org/uk/docs/Web/JavaScript/Reference/Global_Objects/Array/concat
            // const array3 = array1.concat(array2);
            // let array_all = available_arr.concat(step_three);
            // console.log('array_all: ' + (JSON.stringify(array_all)))

            $.totalStorage('user_data_tab_three', step_three);
            // fillTabFour();

            // ***  Storage from TAB1  (Terminauswahl)  -> .available ***
            // 'salonId': salonId,
            // 'termineDate': termineDate,
            // 'termineTime': termineTime,
            // 'dienstleistungId': dienstleistungId,
            // 'dienstleistungName': ServiseName,
            // 'dienstleistungDescription': ServiseDescription,
            // 'wochenTagFertig': termineFulldate,
            // 'mitarbeiterId': mitarbeiterId,
            // 'mitarbeiterName': mitarbeiterName,
            // 'mitarbeiterFile': piv,
            // 'salonAddress': salonAddress,

            // -> FROM TAB 3 (3. Meine Daten) => step to TAB  4  (4. Meine Buchung  )
            $('#page-termine').bootstrapWizard('show', 3);
            // clear ajax finder html
            // $('#termine-ajax').html('');

        }
    });


    // ****************** VALIDATE termineAjaxformWizard form (TAB 1 ) *************************
    $(function () {
        $('#termineAjaxformWizard').validate({

            ignore: 'hidden',
            errorClass: 'validation-error-label',
            // highlight: function(element, errorClass) {$(element).removeClass(errorClass);},
            // errorPlacement: function(error, element){if (element.parents('div').hasClass('has-feedback')) {error.appendTo(element.parent() );}},
            rules: {
                valid: 'required'
            },
            messages: {
                option_salon: {
                    required: '* Dienst Salon ist erforderlich.'
                },
                // mitarbeiter: {required: "* Dienst Mitarbeiter ist erforderlich."},
                date: {
                    required: '* Dienst Datum ist erforderlich.'
                },
                servicePackage: {
                    required: '* Bitte Dienstleistung auswählen'
                }
            },

            lang: 'de', // or whatever language option you have.
            highlight: function (element, errorClass) {
                $(element)
                    .closest('.tt-form-group')
                    // .removeClass('has-success')
                    .addClass(errorClass);
            },
            success: function (element, errorClass) {
                $(element)
                    .closest('.tt-form-group')
                    .removeClass(errorClass);
            },

            // https://stackoverflow.com/questions/22208582/add-additional-data-to-form-serialize-ajax-post
            // 1) data: $form.serialize() + '&hours=' + JSON.stringify(selectedHours),
            // 2) var data = $form.serializeArray();

            submitHandler: function (form) {
                $.totalStorage.deleteItem('available_termine');

                $('#termin-finden').buttonSalo('loading');

                var dienstleistungId = _getDienstleistungId();

                let formData = new FormData($(form)[0]);
                // ** Display the key/value pairs  TO console !
                // for (var pair of formData.entries()) {
                //     console.log(pair[0] + ', ' + pair[1]);
                // }
                // option_salon, 16
                // termine.js:345 date, Donnerstag, 13. Februar 2020
                // termine.js:345 iso_date, 2020-02-13
                // termine.js:345 mitarbeiter, 1082
                // termine.js:345 servicePackage, 3

                $.ajax({
                    url: BloombaseUrl + '/termine/terminFinden',
                    // timeout: 6000,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    // contentType: 'application/json',
                    dataType: 'json',
                    data: formData,
                    dienstleistungId: _getDienstleistungId(),
                    beforeSend: function () {
                        $('#termine-ajax').html('');
                        $('#termine-ajax').prepend(
                            '<div id="alsamulainloader">Lade verfügbare Termine...<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div><p><br />Buchen Sie jetzt einfach, schnell und sicher!</p><p>Sofortige Terminbestätigung rund um die Uhr!</p></div>'
                        );
                    },
                    complete: function () {

                        $('#termin-finden').buttonSalo('reset');
                        $('#alsamulainloader').remove();
                        $('#storno-info').remove();

                        $('#termin-finden').hide();
                    },
                    success: function (res) {
                        // console.log(JSON.stringify(res));
                        if (isAnyObject(res) && res.error == false) {
                            $('#termine-ajax').html(res.html);

                            $('.multiple-items').slick(slick_options);
                            setTimeout(function () {
                                $('.multiple-items').slick('refresh');

                                // $('body .datytip').tooltip({
                                //     customClass: 'tooltip-ua',
                                //     html: true,
                                //     animated: 'fade',
                                //     placement: 'top'
                                // });
                                // $('.multiple-items').slick('reinit');
                                // $('#page-termine').bootstrapWizard('next');
                                // $('#page-termine').bootstrapWizard('show', 2);
                            }, 300);
                            // console.log('salonId:' + res.salonId);
                        }
                        // $('.alert-success').delay(1000).fadeIn();
                    },
                    error: function (jqxhr, textStatus, error) {
                        $('#alsamulainloader').remove();
                        // add whatever debug you want here.
                        var err = textStatus + ', ' + error;
                        console.log('Failed: ' + err);
                    }
                });
            }
        });
    });

    // *** if all the fields have not been selected, the button should not be shown  *** 25.05.2020 13:17
    // $('#termin-finden').hide();

    //.trigger('change')

    // $(document).ready(function() {

    //     let formfinder = $('#termineAjaxformWizard');
    //     $('input.form-control, select.form-control', formfinder).each(
    //         function(index) {
    //             var input = $(this);
    //             alert('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
    //         }
    //     );
    // });




    // $(window).on('load', function() { });

    let dienstleistung_Id = _getDienstleistungId();

    $(document).ready(function () {

        $('#termin-finden').hide();

        $("#servicePackageField").on("change", function (e) {

            let valueSelected = this.value;
            var optionSelected = $("option:selected", this);

            // alert(valueSelected)
            console.log(optionSelected);
            var $valid = jQuery('#page-termine #termineAjaxformWizard').valid();
            if (!$valid) {
                $('#termin-finden').hide();
                // return false;
            } else {
                $('#termin-finden').show();

            }

            // Ajax get ServicePackage  Description
            var $kriviruki_fav = jQuery("#termineAjaxformWizard i.dll.fa");
            // $kriviruki_fav.prop('title', '');
            // $kriviruki_fav.tooltip("hide");
            //  $kriviruki_fav.tooltip('dispose');

            if (valueSelected > 0) {


                var url = BloombaseUrl + '/termine/getServicePackageDescription';
                $.getJSON(url, {
                    format: 'JSON',
                    servicePackage: valueSelected
                })
                    .done(function (res) {
                        // 07.09.2021   fix oppo by lastServicePackage cookie selected
                        if (valueSelected) {
                            $.totalStorage.setItem('lastServicePackage', valueSelected);
                        }

                        // console.log(JSON.stringify(res));
                        if (isAnyObject(res) && res.error == false) {
                            // let ServiseName = res.ServicePackage.DisplayName;
                            let Servise_Description = res.ServicePackage.Description;

                            console.log('change servicePackageField Description: ' + Servise_Description);
                            // $('#termineAjaxformWizard .spf-flex i.fa').prop('title', Servise_Description);
                            // $('#servicePackageContainer i.dll').prop('title', Servise_Description);
                            var $kriviruki_fav = jQuery("#termineAjaxformWizard i.dll.fa");
                            // var $kriviruki_fav = jQuery('[data-toggle="tooltip"]');
                            // $kriviruki_fav.data('original-title', Servise_Description);
                            //$kriviruki_fav.prop('title', Servise_Description);
                            $kriviruki_fav.attr('data-original-title', Servise_Description);
                            /* $kriviruki_fav.tooltip({
                                title: Servise_Description,
                                animated: 'fade',
                                trigger: 'click',
                                container: "body",
                                placement: "top",
                                html: true,
                                delay: { show: 240, hide: 60 }
                            }); */

                            $(document).on('inserted.bs.tooltip', function (e) {
                                // var tooltip = $(e.target).data('bs.tooltip');
                                //$(tooltip.tip).addClass('tooltip-super-ua');
                            });

                        }
                    })
                    .fail(function (jqxhr, textStatus, error) {
                        var err = textStatus + ', ' + error;
                        console.log('Request Failed: ' + err);
                    });


                if (!optionSelected.data('childid')) {
                    //data-childid
                    return; // Wenn data-childid Attribut nicht vorhanden, dann tue auch nichts
                } else {

                    var lastServicePackageValue = $.totalStorage.getItem('lastServicePackage');
                    console.log('Dienstleistung : ' + lastServicePackageValue);
                    if (valueSelected) {
                        $.totalStorage.setItem('lastServicePackage', optionSelected.val());
                    }
                    console.log('valueSelected : ' + valueSelected);
                    console.log('Dienstleistung optSel : ' + optionSelected.val());

                    // localStorage.setItem('vertretung', valCheckedField);
                    // $.totalStorage('vertretung_new', valCheckedField);
                    // $.totalStorage.deleteItem $.totalStorage.setItem $.totalStorage.getItem

                    if ($.totalStorage.getItem('hairlength')) {
                        if (lastServicePackageValue !== 'undefined') {
                            checkServicepackageValue(lastServicePackageValue, e);
                        }
                        return;

                    } else {
                        openLightbox(e);
                        // hairDataModal
                        // employee_modal_body.modal('show'); //pop up modal
                    }
                }


                return false;
            }
            // .trigger('change')

        });

        $('body').on('click', '#hairLengthDontKnow', function (e) {
            $("#hairDataModal .moreInfo").slideToggle('1000', "swing", function () {
                // Animation complete.
            });
        });


        function checkServicepackageValue(lastServicePackageValue, e) {

            if (lastServicePackageValue != $.totalStorage.getItem('lastServicePackage')) {
                $.totalStorage.deleteItem('hairlength');
                openLightbox(e);
                return false;
            }
            return true;
        }

        function openLightbox(e) {
            // $('.lightboxContentPopUp').css('display', 'block');
            e.preventDefault();

            $('#hairDataModal').modal('show'); //pop up modal

            setTimeout(restoreFieldRadio, 100);



        }

        $('body').on('show.bs.modal', '#hairDataModal', function () {

            // $('#termin-finden-hair').on('click', function() {
            //     // selectAppointmentNavigation.setStep(4);
            //     $('#termin-finden').trigger('click');
            //     $('#hairDataModal').modal('hide');
            // });

            $('#termin-finden').on('click');

            var btnMergeField = $(this).find('#termin-finden-hair');

            btnMergeField.on('click', function () {

                $('#termin-finden').trigger('click');
                $('#hairDataModal').modal('hide');
            });

            $(this).on('hide.bs.modal', function () {
                btnMergeField.off('click');
                // $('#termin-finden').off('click');
            });
            // return false;

        });



        $('#hairDataModal').on('click', function () {
            if ($('input.short', this).is(':checked') || $('input.long', this).is(':checked')) {
                $('#termin-finden-hair').removeAttr('disabled');
            }
        });


        $('body').on('hidden.bs.modal', '#hairDataModal', function () {

            if ($('#lenghtLong', this).is(':checked')) {
                $.totalStorage.setItem('hairlength', "true");
            } else {
                $.totalStorage.deleteItem('hairlength');
            }

            // var value = $.totalStorage.getItem('hairlength');
            // alert(value)

        });
        // click to close modal hair
        $('body').on('click', '#lightboxCloseTermine', function (e) {
            console.log('hair Modal hide');
            clearSelectField('servicePackageField');
            // hide termin btn
            $('#termin-finden').hide();
            return false;
            // $(this).removeData('bs.modal');
        });


        function clearSelectField(field) {
            var defaulOption = $("#" + field + " option").first().val();
            $("#" + field).val(defaulOption);
            $.totalStorage.deleteItem(field);
        }

        /** CACHING **/

        var autoRestored = [];
        var radiofields = ["input[type=radio][name=hairlength]"];
        if (typeof (Storage) !== "undefined") {
            // Bei Ãnderung des Feldwertes diesen speichern
            $.each(radiofields, function (index, value) {
                $(value).on("change", saveValueRadio);
            });
        }

        /**
         * Wert des Radiobutton zwischenspeichern
         */
        function saveValueRadio() {
            var valCheckedField = $(this).val();
            $.totalStorage.setItem('hairlength', valCheckedField);
        }
        /**
         * sets the value of the local storage
         * @param {string} field
         * @returns {Boolean}
         */
        function restoreFieldRadio() {
            var value = $.totalStorage.getItem('hairlength');
            //change the field value
            $('input[type=radio][name=hairlength][value=' + value + ']').prop('checked', true);
            $('input[type=radio][name=hairlength][value=' + value + ']').trigger('click');
        }



        if (dienstleistung_Id) {

            if (dienstleistung_Id > 0) {

                var url = BloombaseUrl + '/termine/getServicePackageDescription';
                $.getJSON(url, {
                    format: 'JSON',
                    servicePackage: dienstleistung_Id
                })
                    .done(function (res) {


                        // console.log(JSON.stringify(res));
                        if (isAnyObject(res) && res.error == false) {
                            // let ServiseName = res.ServicePackage.DisplayName;
                            let Servise_Description = res.ServicePackage.Description;
                            // $('#servicePackageContainer i.dll').prop('title', Servise_Description);

                            console.log('dienstleistung_Id > 0 Description:  ' + Servise_Description);

                            var info = $('<i class="fa fa-info-circle dll" id="DienstleistungInfo" data-toggle="tooltip" data-placement="top" title="" data-original-title="' + Servise_Description + '" ></i>');
                            info.tooltip({
                                html: true,
                                trigger: 'click',
                                placement: 'bottom',
                            });

                            $("#infoContainer").html(info);


                            var $kriviruki_fav = jQuery("#termineAjaxformWizard i.dll.fa");
                            // $kriviruki_fav.prop('title', '');
                            // $kriviruki_fav.tooltip("hide");
                            //$kriviruki_fav.tooltip('dispose');
                            $kriviruki_fav.data('original-title', Servise_Description);
                            // $kriviruki_fav.prop('title', Servise_Description);
                            //$kriviruki_fav.attr('data-original-title', Servise_Description);
                            /*
                                                        $kriviruki_fav.tooltip({
                                                            title: Servise_Description,
                                                            animated: 'fade',
                                                            trigger: 'click',
                                                            container: "body",
                                                            placement: "top",
                                                            html: true,
                                                            delay: { show: 240, hide: 60 }
                                                        }); */

                            // $(document).on('inserted.bs.tooltip', function (e) {
                            //     var tooltip = $(e.target).data('bs.tooltip');
                            //     $(tooltip.tip).addClass('tooltip-super-ua bloomstip');
                            //     setTimeout(function () {
                            //         $('.tooltip-super-ua.bloomstip').css('display', 'none');
                            //     }, 3760);
                            // });


                        }
                    })
                    .fail(function (jqxhr, textStatus, error) {
                        var err = textStatus + ', ' + error;
                        console.log('Request Failed: ' + err);
                    });
                return false;
            }
            $("#servicePackageField").trigger('change');
            $("#salonMitarbeiterField").trigger('change');
        }

    });




    let kuzyuka = false;

    $('#termineAjaxformWizard').on('change', 'select', function (e) {

        // alert('The option with value ' + $(this).val());
        //option:selected
        var optionSelected = $("option:selected", this);
        // alert(optionSelected.val())
        var valueSelected = this.value;
        if (valueSelected == '') {
            // $('#termin-finden').show();
            kuzyuka = true;
        }

        var $valid = jQuery('#page-termine #termineAjaxformWizard').valid();
        if (!$valid) {
            $('#termin-finden').hide();

            // alert($(this).prop('id'))
            var ziz = $('#option_salon option:selected').val();
            // || typeof ziz == 'undefined'
            if (!ziz) {
                var serviceSel = $('#servicePackageField');
                $('option:first', serviceSel).prop('selected', true); //select first option
                $('#termineAjaxformWizard i.fa.dll').prop('title', '');
            }
            $('#termine-ajax').html('');
            return false;
        } else {

            var serviceSelval = $('#servicePackageField option:selected').val();
            if (serviceSelval) {
                $('#termin-finden').show();

            } else {
                $('#termin-finden').hide();
            }

        }

        /**
         * @Date: 2020-05-13 14:41:05
         * @Desc: when you change something in “Mitarbeiter or Salon�?,
         * the terms that were previously downloaded and displayed should not be displayed
         */
        $('#termine-ajax').html('');
        return false;
    });

    // alert(kuzyuka)

    $('#termineAjaxformWizard').on('click', '#datepicker', function (e) {

        // alert('The option with value ' + $(this).val());

    });

    // $('#termineAjaxformWizard select').on('oninput', function() {

    //     // if ($('#termineAjaxformWizard').valid()) $('#termin-finden').fadeIn();

    // });



    $('[data-original-title]').tooltip({
        animated: 'fade',
        placement: "top",
        html: true,
        delay: { show: 240, hide: 60 }
    });

    $('html').on('click', function (e) {
        if (typeof $(e.target).data('original-title') == 'undefined' &&
            !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').tooltip('hide');
        }
    });
    // big  table select ziruk_lnik
    $('#termine-data').on('click', '.available', function (e) {


        $(this).prepend(
            '<div id="alsamulainloader"><div style="margin-left: -10px;" class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
        );

        // ! clear second send sms
        $.totalStorage.deleteItem('appointmentId');
        // $.totalStorage('available_termine', null);
        $.totalStorage.deleteItem('available_termine');
        //confirm-tab-2
        e.preventDefault();
        // let formConfirmtab2 = $("#confirm-tab-2");
        let meinterminTab = $('#mein-termin-tab');
        let formConfirmtab2 = $('#dateConfirm');
        let dateUramap = $('#dateUramap');
        let salonId = $(this).data('salonid');
        let mitarbeiterId = $(this).data('mid');
        /**
         * @Date: 2020-04-27 18:03:18
         * @Desc: fix name - Lisa Täubl to Lisa
         */
        // let mitarbeiterName = $(this).data('miname');
        let mitarbeiterName = $(this).data('firstname');
        let mitarbeiterAvatar = $(this).data('miavatar');
        let termineTime = $(this).data('time');
        let termineFulldate = $(this).data('fulldate');
        let termineDate = $(this).data('date');
        let avadienstleistungId = _getDienstleistungId();


        // alert('.available' + avadienstleistungId)
        // return;
        //   $('#dateSelect').submit();

        var url = BloombaseUrl + '/termine/confirmTabtwo';
        $.getJSON(url, {
            format: 'JSON',
            option_salon: salonId,
            mitarbeiter: mitarbeiterId,
            servicePackage: avadienstleistungId
        })
            .done(function (res) {

                // hide ajax result on step 2
                // $('#termine-ajax').html('');
                $('#alsamulainloader').remove();


                // NEW upload\employeeimage
                let piv = BloombaseUrl + '/upload/employeeimage/' + mitarbeiterAvatar;
                $('#mitarbeiterFirstName', formConfirmtab2).text(mitarbeiterName);
                $('#mitarbeiterImg', formConfirmtab2).prop('src', piv);
                $('#ConfirmTerminDate', formConfirmtab2).text(termineFulldate);
                $('#ConfirmTerminTime', formConfirmtab2).text(termineTime);

                // console.log(JSON.stringify(res));
                if (isAnyObject(res) && res.error == false) {
                    let salonAddress = res.salonAddress;
                    $('#ConfirmTerminAddress', formConfirmtab2).text(salonAddress);

                    /** @Date: 2020-07-01 16:26:22
                     * @Desc: add map custom date dateUramap
                     */
                    let salonPhone = res.salonPhone;
                    $('#addressFive', dateUramap).text(salonAddress);
                    $('#mobilenumberFive', dateUramap).text(salonPhone);
                    var map_url = 'https://maps.google.com/maps?q=' + encodeURIComponent(salonAddress) + '&t=&z=13&ie=UTF8&iwloc=&output=embed';

                    $('#output-map').html('<iframe id="mapframe" class="gray-map" src="' + map_url + '" width="100%" height="300px" frameborder="0" style="border:0;" allowfullscreen=""></iframe>');
                    /** @Date: 2020-07-01 16:26:22
                     * @Desc: end
                     */

                    let ServiseName = res.ServicePackage.DisplayName;
                    $('#ConfirmTerminServiseName', formConfirmtab2).text(ServiseName);
                    // {"error":false,"salonAddress":"Schwalbacher Straße 26, Wiesbaden","ServicePackage":{"Description":"• Beratung/Waschen/Conditioner \r\n• professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen\r\n","DisplayName":"Finish"}}
                    let ServiseDescription = res.ServicePackage.Description;
                    //$('#dienstleistungDisplayName span.fa', formConfirmtab2).prop('title', ServiseDescription);
                    $('#dienstleistungDisplayName span.fa', formConfirmtab2).attr('data-original-title', ServiseDescription);

                    // GO TO tab  (2. Mein Termin)
                    $('#page-termine').bootstrapWizard('show', 1);
                    // Storage data
                    let step_two = new Array();
                    step_two.push({
                        salonId: salonId,
                        termineDate: termineDate,
                        termineTime: termineTime,
                        dienstleistungId: avadienstleistungId,
                        dienstleistungName: ServiseName,
                        dienstleistungDescription: ServiseDescription,
                        wochenTagFertig: termineFulldate,
                        mitarbeiterId: mitarbeiterId,
                        mitarbeiterName: mitarbeiterName,
                        mitarbeiterFile: piv,
                        salonAddress: salonAddress
                    });

                    // $('#termine-data').prop("disabled", true);
                    // $(this).prop("disabled", true);



                    $.totalStorage('available_termine', step_two);
                    // scroll Top tab  (2. Mein Termin)
                    // $('html, body')
                    //     .delay(200)
                    //     .animate({
                    //             scrollTop: parseInt(meinterminTab.offset().top + 10)
                    //         },
                    //         600
                    //     );

                    // Scroll screen to target element
                    $('body').scrollTo('#mein-termin-tab', 300, {
                        onAfter: function () {
                            // $('#settings-message').text('Got there!');
                        }
                    });


                } else {
                    alert('Request Failed');
                }
            })
            .fail(function (jqxhr, textStatus, error) {
                $('#alsamulainloader').remove();
                var err = textStatus + ', ' + error;
                console.log('Request Failed: ' + err);
            });
        return false;
    });

    // Storage tab  3. Meine Daten
    // jQuery(document).ready(function($) {
    //     // checkbox - Hiermit willige ich ein, dass meine Daten
    //     if ($('#pagecon_appointment_reconnect_id:checked').length > 0) {
    //         $("#pagecon_appointment_reconnect_id-error").remove();
    //     }
    //     $("#pagecon_appointment_reconnect_id").on("click", function() {
    //         // $(".checkbox input:checked").parent().remove();
    //         if ($(this).is(':checked')) {
    //             console.log('reconnect_id checked: ');
    //             $("#pagecon_appointment_reconnect_id-error").remove();
    //         } else {
    //             // console.log('reconnect_id no: ');
    //         }
    //     });
    // });
    // Storage tab (2. Mein Termin) and  (3. Meine Daten)
    jQuery(document).ready(function ($) {
        // (2. Mein Termin)  Bei Ausfall des Mitarbeiters: = У разі відмови працівника:
        let formConfirmtab2 = $('#dateConfirm');
        if (formConfirmtab2.length) {
            /***** Caching *****/
            var autoRestored = [];
            var fields = ['input[type=radio][name=vertretung]'];

            $.each(fields, function (index, value) {
                $(value).on('change', saveValue);
            });

            /**
             * Wert des Selectfield zwischenspeichern
             */
            function saveValue() {
                var valCheckedField = $(this).val();

                localStorage.setItem('vertretung', valCheckedField);
                $.totalStorage('vertretung_new', valCheckedField);
            }
            /**
             * sets the value of the session storage
             * @param {string} field
             * @returns {Boolean}
             */
            function restoreField() {
                // $.totalStorage('vertretung', false);
                // $.totalStorage.deleteItem('vertretung');
                // var value = localStorage.getItem('vertretung');
                var value = $.totalStorage('vertretung_new');
                console.log('vertretung: ' + value);
                // alert(value)
                //change the field value
                $('input[type=radio][name=vertretung][value=' + value + ']', formConfirmtab2).attr('checked', true);
            }
            restoreField();
        }
        // (3. Meine Daten) Hiermit willige ich ein = Я згоден з тим, що мої дані обробл�?ють�?�? в рамках функції комфорту. persDataConfirm
        let formConfirmtab3 = $('#persDataConfirm');
        if (formConfirmtab3.length) {
            (function () {
                const key = 'pagecon_appointment_reconnect_id',
                    checkbox = $('input[name=' + key + ']');
                checkbox.prop('checked', localStorage.getItem(key) === '1').on('click', function () {
                    //add/remove the reconnect id flag in/from the local storage
                    try {
                        if (this.checked) localStorage.setItem(key, '1');
                        else localStorage.removeItem(key);
                    } catch (e) { }
                });
            })();
        }


        if (formConfirmtab3.length) {
            // Phone numbers: only allow numbers
            $("#mobilenumber, #mobilenumberSecond").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter
                // 07.09.2021 fix  PLUS keyCode 187
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 187]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }


    });

    function openCodeDialog() {

    }

    const POPUP_QUERY = "#errorAuswahl"

    $("#getCode").click(function () {

        var ok = 1;

        $("#getCodeBtn").buttonSalo('loading');
        let available_tab_arr = $.totalStorage('available_termine');
        let user_data_tab_arr = $.totalStorage('user_data_tab_three');

        if (available_tab_arr instanceof Array && user_data_tab_arr instanceof Array) {


        } else {
            console.log('user_data Storage (' + key + ') empty');
            APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
            return false;
        }
        let tab3 = available_tab_arr[0];
        let tab4 = user_data_tab_arr[0];
        // https://stackoverflow.com/questions/11109795/how-do-i-post-an-array-of-objects-with-ajax-jquery-or-zepto
        // 2) jsonObj = new Object();
        // jsonObj['email'] = email;
        // jsonObj['course1'] = c1Val;
        // data: {"data": JSON.stringify(jsonObj)},
        let postData = { 'user_data_tab_three': tab4, 'available_termine': tab3, 'pagecon_appointment_reconnect_id': true };


        //PM (16.04.2018) used to test the connectivity to the server
        //alert([vorname, nachname, email, mobilenumber, salonId, mitarbeiterId, date, time, dienstleistungId, vertretung].join("---"))
        jQuery.ajax({
            url: BloombaseUrl + '/appontment/createAppontmentCodeWithoutAccount',
            // timeout: 6000,
            type: 'POST',
            dataType: 'json',
            data: postData,
            error: function alert(data) {
                $("#getCodeBtn").buttonSalo('reset');
                APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
            },
            // error: function(jqxhr, textStatus, error) {
            //     $("#getCodeBtn").buttonSalo('reset');
            //     // add whatever debug you want here.
            //     var err = textStatus + ', ' + error;
            //     console.log('Failed: ' + err);
            // },
            success: function (data) {

                // ! clear second send sms
                // $.totalStorage.deleteItem('appointmentId');

                console.log('1) getCode sms: ' + JSON.stringify(data));

                // ) getCode sms: {"success":true,"appointment_created":true,"appointment_id":"2307dcc7-0d29-4e48-92e0-e2958dfbed7f","sms_sent":true,"error_code":0}
                $("#getCodeBtn").buttonSalo('reset');
                //PM (11.03.2019) Bloom´s // Webseite // Terminbuchung // Einführung Kundenkonto/ReconnectID >> Produktion :: update the reconnect id
                try {
                    if (data.sms_sent || data.error_code > 0) {
                        // $('#page-termine').bootstrapWizard('show', 5);
                        APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, parseInt(data.error_code))
                    }
                    //SHOULD NOT MERGE
                    if (data.success) {

                        let appointmentId = data.appointment_id;
                        console.log('1) appointmentId : ' + appointmentId);
                        // *** New Storage appointmentId
                        $.totalStorage('appointmentId', appointmentId);
                        // OR old
                        $("#appointment_id").val(appointmentId) //required in case the code has to be resend
                        //DO NOT MERGE
                        // if (!data.sms_sent) $('#formCodeConfirm').submit() //move to the next page
                        if (!data.sms_sent) {
                            ActivTabTermin('pills-fertig');
                        } //move to the next page
                    } else if (data.error_code > 0) {
                        // $('#page-termine').bootstrapWizard('show', 5);
                        // APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, parseInt(data.error_code))
                        APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, data.error_code)
                    } else {
                        APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
                    }

                } catch (e) {
                    // console.error(e.message())
                }
            }
        });
    });


    $("#getCodeSecond").click(function () {

        $("#getCodeBtn").buttonSalo('loading');
        // var mobilenumberSecond = $("#mobilenumberSecond").val();
        // var appointmentId = $("#h_appointmentId").val();
        // *** New Storage appointmentId
        let appointmentId = $.totalStorage('appointmentId');
        let user_data_tab_arr = $.totalStorage('user_data_tab_three');

        console.log('2) resend appointmentId : ' + appointmentId);

        if (user_data_tab_arr instanceof Array) {

        } else {
            console.log('user_data Storage (' + key + ') empty');
            APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
            return false;
        }

        if (!appointmentId) {
            console.log('appointment Id (' + appointmentId + ') empty');
            APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
            return false;
        }

        let mobilenumber = getIemtByStorage(user_data_tab_arr, 'mobilenumber');
        let postData = { 'mobilenumber': mobilenumber, 'appointmentId': appointmentId };
        console.log('2) resend CodeSecond postData: ' + JSON.stringify(postData));

        jQuery.ajax({
            url: BloombaseUrl + '/appontment/resendConfirmationCode',
            // timeout: 6000,
            type: 'POST',
            dataType: 'json',
            data: postData,
            error: function alert(data) {
                $("#getCodeBtn").buttonSalo('reset');
                APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
            },
            success: function (res) {
                console.log('2) resend CodeSecond sms: ' + JSON.stringify(res));
                $("#getCodeBtn").buttonSalo('reset');

                if (isAnyObject(res) && res.success == true) {
                    let response = res.returncodevalue;
                    APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, parseInt(response))
                } else {
                    APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, -1)
                }
            }
        });



    });
    // test pop dialog
    // APPOINTMENT_MAKER.setConfirmationCodePopup(POPUP_QUERY, 0)
})(jQuery);