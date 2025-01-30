var selectAppointmentNavigation;

$(document).ready(function() {
    // Datum prüfen, sodass keine abgelaufenen Daten im localStorage stehen
    if (moment().isAfter(localStorage.getItem('datepicker'))) {
        localStorage.removeItem('datepicker');
    }
    var MAX_WIDTH_PHONE = 768;
    var MAX_WIDTH_TABLET = 959;

    var defaultDate = moment().toDate();
    var todayMinus = moment()
        .subtract(1, 'days')
        .toDate();
    var today = moment().toDate();
    //var nextMonth = moment().add(6, 'weeks').toDate();
    var nextMonth = moment()
        .add(10, 'weeks')
        .toDate(); //PM (11.12.2017) nextMonth update test

    //PM (05.03.2019)
    var PAGECON_WINDOW = $(window);

    // Get the width of the actual window, change the value if the window will be resized
    var windowWidth = PAGECON_WINDOW.width();

    PAGECON_WINDOW.on('resize', function() {
        windowWidth = PAGECON_WINDOW.width();
        if (windowWidth <= MAX_WIDTH_PHONE) $('.submit-panel').hide();
    });

    // Set window width on hidden Input Field, to give the Value to the helper.php file later on
    $('#widthOfWindow').val(windowWidth);

    var valueDate = localStorage.getItem('datepicker');
    if (!!valueDate) {
        var d = Date.parse(valueDate);
        defaultDate = new Date(d);
    }

    var i18n_DE = {
        previousMonth: 'Vormonat',
        nextMonth: 'Nächster Monat',
        months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
        weekdays: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        weekdaysShort: ['So.', 'Mo.', 'Di.', 'Mi.', 'Do.', 'Fr.', 'Sa.']
    };

    $('#datepicker').pikaday({
        format: 'dddd, DD. MMMM YYYY',
        minDate: today,
        defaultDate: defaultDate,
        setDefaultDate: defaultDate,
        maxDate: nextMonth,
        i18n: i18n_DE,
        firstDay: 1,
        onSelect: function(date) {
            selectAppointmentNavigation.setLocalStorageValue('datepicker', this.getMoment().format('YYYY-MM-DD'));
            //localStorage.setItem('datepicker', this.getMoment().format('YYYY-MM-DD'));
        }
    });

    /** PM (02.02.2018) bloom`s // Webseite // Smartphone-Optimierung :: @deprecated
  $('#datepicker-phone').pikaday({
    format: 'dddd, DD. MMMM YYYY',
    minDate: today,
    defaultDate: defaultDate,
    setDefaultDate: defaultDate,
    maxDate: nextMonth,
    bound: false,
    container: document.getElementById('datepicker-container'),
    theme: 'visible-phone phone-calendar',
    i18n: i18n_DE,
    firstDay: 1,
    onSelect: function (date) {
      selectAppointmentNavigation.setLocalStorageValue('datepicker', this.getMoment().format('YYYY-MM-DD'));
      //localStorage.setItem('datepicker', this.getMoment().format('YYYY-MM-DD'));
      $(".employer-wrapper").find(".termin-datum").find("span").text($("#datepicker-phone").val());
        //selectAppointmentNavigation.setStep(2);
      //selectAppointmentNavigation.setDisabledStates(false);
    }
  });
  */

    /**
     * PM (02.02.2018) bloom`s // Webseite // Smartphone-Optimierung
     * the mobile data picker has been PARTIALLY rewritten to enable a higher flexibility
     * @warning all these variables are used GLOBALLY
     * @see https://github.com/dbushell/Pikaday
     */
    var datePickerMobileFormat = 'dddd, DD. MMMM YYYY';
    var datePickerMobileElt = $('#datepicker-phone');
    var selectDatePicker = function(datePicker) {
        const dateRaw = datePicker.toString('YYYY-MM-DD'); //PM (03.04.2019)

        selectAppointmentNavigation.setLocalStorageValue('datepicker', dateRaw);

        var date = datePicker.toString(datePickerMobileFormat);
        datePickerMobileElt.val(date);
        //$(".employer-wrapper .termin-datum span").html(date)

        $('.employer-wrapper .termin-datum span').html(date);
    };
    var getDatePicker = function(date) {
        var datePicker = new Pikaday({
            format: datePickerMobileFormat,
            minDate: today,
            defaultDate: date,
            setDefaultDate: date,
            maxDate: nextMonth,
            bound: false,
            field: document.getElementById('datepicker-container'),
            theme: 'visible-phone phone-calendar',
            i18n: i18n_DE,
            firstDay: 1,
            onSelect: function(_) {
                selectDatePicker(datePicker);
            }
        });
        datePicker.show(); //s.e.
        datePickerMobileElt.val(datePicker.toString(datePickerMobileFormat)); //initialise the value buffer

        return datePicker;
    };
    var datePickerMobile = getDatePicker(defaultDate);

    $('#datepickerimage').click(function() {
        $('.datepickerDefault')
            .css('display', 'none')
            .addClass('notShown');
        $('.datepickerField')
            .css('opacity', '1')
            .removeAttr('disabled');

        $('#salonMitarbeiterField').removeAttr('disabled');
        $('.selectors_left_bottom').removeClass('deactiveSelector');

        setTimeout(function() {
            $('#datepicker').trigger('click');
        }, 200);
    });

    $('#datepickerimage_phone').click(function() {
        $('.datepickerDefault')
            .css('display', 'none')
            .addClass('notShown');
        $('.datepickerField')
            .css('opacity', '1')
            .removeAttr('disabled');
        //$('#datepicker').trigger("click");

        $('#salonMitarbeiterField').removeAttr('disabled');
        $('.selectors_left_bottom').removeClass('deactiveSelector');

        setTimeout(function() {
            $('#datepicker').trigger('click');
        }, 200);
    });

    $('.changeChoice').live('click', function() {
        $('.changeChoice').hide();
        $('.middle').empty();

        $('.selectors_right_top').fadeIn();
        $('.selectors_left_top').fadeIn();
        $('.selectors_right_bottom').fadeIn();
        $('.selectors_left_bottom').fadeIn();

        $('.submit').fadeIn();
    });

    // Terminauswahl
    $('.submit').live('click', function() {
        // Timer initialisieren
        getStartTime();
        window.setTimeout(checkTime, 1000);

        $('.submit').attr('disabled', 'disabled');
        $('#salonField').attr('disabled', 'disabled');
        $('#datepicker').attr('disabled', 'disabled');
        $('#salonMitarbeiterField').attr('disabled', 'disabled');
        $('#servicePackageField').attr('disabled', 'disabled');

        var salonId = $('#salonField').val();
        var mitarbeiterId = $('#salonMitarbeiterField').val();

        /*************************************/
        /* Option Langhaar Abfrage der DL-Id */
        /*************************************/
        var dienstleistungId = selectAppointmentNavigation.getDienstleistungId();

        //dienstleistungId = $("#servicePackageField").val();
        var date = $('#datepicker').val();

        var width = $('#widthOfWindow').val();

        if (salonId != '' && date != '' && dienstleistungId != '') {
            var mode = 'getTimeTable';

            $('.submit').hide();

            // Auswahl Ã¤ndern
            if (windowWidth <= MAX_WIDTH_PHONE) {
                $('.selectors_right_top').hide();
                $('.selectors_left_top').hide();
                $('.selectors_right_bottom').hide();
                $('.selectors_left_bottom').hide();
                $('.changeChoice').fadeIn();
            }

            $('.middle').fadeOut('slow', function() {
                $('.middle').empty();
                $('#howto').fadeIn('slow');
                $('#loading').fadeIn('slow', function() {
                    jQuery.ajax({
                        type: 'POST',
                        url: '/modules/mod_terminfinder/helper.php',
                        data: {
                            mode: mode,
                            salonId: salonId,
                            mitarbeiterId: mitarbeiterId,
                            dienstleistungId: dienstleistungId,
                            date: date,
                            width: width,
                            orientation: selectAppointmentNavigation.checkOrientation()
                        },
                        dataType: 'html',
                        error: function alarm() {
                            $('.changeChoice').hide(); // neu
                            $('.selectors_right_top').fadeIn(); // neu
                            $('.selectors_left_top').fadeIn(); // neu
                            $('.selectors_right_bottom').fadeIn(); // neu
                            $('.selectors_left_bottom').fadeIn(); // neu
                            $('.submit').fadeIn();
                            $('#loading').fadeOut('slow'); // neu
                            $('#salonField').removeAttr('disabled');
                            $('#datepicker').removeAttr('disabled');
                            $('#salonMitarbeiterField').removeAttr('disabled');
                            $('#servicePackageField').removeAttr('disabled');
                        },
                        success: function(data) {
                            $('#list').css('opacity', 0);
                            $('#loading').fadeOut('slow', function() {
                                $('.middle')
                                    .html(data)
                                    .fadeIn('slow', function() {
                                        var itemWidthContainer = $('#list').width();

                                        var config = {
                                            visible_items: 11,
                                            scrolling_items: 11,
                                            orientation: 'horizontal',
                                            circular: 'no',
                                            autoscroll: 'no',
                                            interval: 6000,
                                            direction: 'right'
                                        };

                                        if (windowWidth > MAX_WIDTH_PHONE && windowWidth <= MAX_WIDTH_TABLET) {
                                            config.visible_items = 9;
                                            config.scrolling_items = 9;

                                            var itemWidth = itemWidthContainer / config.visible_items - 5;

                                            $('#list .als-item').css('width', itemWidth);
                                            $('#list .als-item .m_name').css('width', itemWidth);
                                            $('#list .als-item .m_bild').css('width', itemWidth - 10);
                                            $('#list .mitarbeiter').css('width', itemWidth);
                                            $('#list .mitarbeiter .m_name').css('width', itemWidth);
                                            $('#list .mitarbeiter .m_bild').css('width', itemWidth - 10);
                                        } else if (windowWidth <= MAX_WIDTH_PHONE) {
                                            if (selectAppointmentNavigation.checkOrientation() === true) {
                                                config.visible_items = 5;
                                                config.scrolling_items = 5;
                                            } else {
                                                config.visible_items = 3;
                                                config.scrolling_items = 3;
                                            }

                                            var itemWidth = roundWidth(itemWidthContainer / config.visible_items - 5, 2);

                                            $('#list .als-item').css('width', itemWidth);
                                            $('#list .mitarbeiter').css('width', itemWidth);
                                            $('#list .mitarbeiter .m_name').css('width', itemWidth);
                                            $('#list .mitarbeiter .m_bild').css('width', itemWidth);
                                            $('#list .mitarbeiter .m_bild').css('height', itemWidth * 1.5);
                                        }

                                        //handles touch gestures on tablets and phones
                                        if (windowWidth <= MAX_WIDTH_TABLET) {
                                            $('#list').swipe({
                                                swipeLeft: function(event, direction, distance, duration, fingerCount, fingerData) {
                                                    if (direction === 'left' && $('#list .als-next').css('display') !== 'none') {
                                                        $('#list .als-next').trigger('click');
                                                    }
                                                },
                                                swipeRight: function(event, direction, distance, duration, fingerCount, fingerData) {
                                                    if (direction === 'right' && $('#list .als-prev').css('display') !== 'none') {
                                                        $('#list .als-prev').trigger('click');
                                                    }
                                                }
                                            });
                                        }

                                        $('#list').als(config);

                                        //fix for break of last element
                                        $('#list .als-item')
                                            .last()
                                            .css('margin-right', '0');
                                        $('#list').css('opacity', 1);
                                    });
                            });

                            $('.submit').removeAttr('disabled');
                            $('#salonField').removeAttr('disabled');
                            $('#datepicker').removeAttr('disabled');
                            $('#salonMitarbeiterField').removeAttr('disabled');
                            $('#servicePackageField').removeAttr('disabled');
                        }
                    });
                });
            });
        } else {
            $('#errorAuswahl').dialog({
                buttons: [{
                    text: 'Ok',
                    click: function() {
                        $(this).dialog('close');
                    }
                }],
                draggable: false,
                title: 'Hinweis',
                modal: true
            });
            $('.submit').removeAttr('disabled');
            $('#salonField').removeAttr('disabled');
            $('#datepicker').removeAttr('disabled');
            $('#salonMitarbeiterField').removeAttr('disabled');
            $('#servicePackageField').removeAttr('disabled');
            return false;
        }

        // Termin finden Button bei Änderung des Datums wieder anzeigen
        $('.datepickerField').live('click', function() {
            emptyList();
            if ($('#salonMitarbeiterField').val() != 'chooseMitarbeiter' && $('#servicePackageField').val() != '') {
                $('.submit').fadeIn();
            }
        });
    });

    $('.available').live('click', function() {
        $('#h_salonId').val($('#salonField').val());
        $('#h_date').val($(this).attr('date'));
        $('#h_time').val($(this).attr('time'));
        $('#h_mitarbeiterId').val($(this).attr('mid'));

        var dienstleistungId = selectAppointmentNavigation.getDienstleistungId();

        $('#h_dienstleistungId').val(dienstleistungId);

        if (windowWidth > MAX_WIDTH_PHONE) {
            $('#dateSelect').submit();
        } else {
            var btn = $('button[id="selectNext"]');
            btn.removeAttr('disabled');
            btn.unbind('click');
            $('.isActive').removeClass('isActive');
            $(this).addClass('isActive');
            btn.live('click', function() {
                $('#dateSelect').submit();
            });
        }
    });

    //Tooltip deaktivieren

    $('#salonTip').live('mouseover', function() {
        $('.salonTip').fadeIn('slow');
    });

    $('#salonTip').live('mouseleave', function() {
        $('.salonTip').fadeOut('fast');
    });

    /*
     *	Reihenfolge der Terminvereinbarung festlegen
     *
     */
    if ($('#salonField').val() == '0') {
        $('.datepickerDefault')
            .css('display', 'none')
            .addClass('notShown');
        $('.datepickerField')
            .css('opacity', '1')
            .removeAttr('disabled');
    } else {
        $('.datepickerDefault')
            .css('display', 'inline-block')
            .removeClass('notShown');
        $('.datepickerField')
            .css('opacity', '0')
            .attr('disabled', 'true');
    }

    $('#salonField').live('change', function() {
        if (!$('.datepickerDefault').hasClass('notShown')) {
            // false
            $('#salonMitarbeiterField').attr('disabled', 'true');
            $('.selectors_left_bottom').addClass('deactiveSelector');
        } else {
            // true
            $('.selectors_left_bottom').removeClass('deactiveSelector');
        }
        // Datum --> Mitarbeiter
        if ($(this).val() != '0') {
            // Datum
            $('.datepickerDefault').removeAttr('disabled');
            //$(".datepickerField").removeAttr("disabled");
            $('.selectors_right_top').removeClass('deactiveSelector');
            // Mitarbeiter
            $('#salonMitarbeiterField').attr('disabled', 'true');
            $('.selectors_left_bottom').addClass('deactiveSelector');
            // Dienstleitung
            $('#servicePackageField').attr('disabled', 'true');
            $('.selectors_right_bottom').addClass('deactiveSelector');

            if ($('.datepickerDefault').hasClass('notShown')) {
                $('.selectors_left_bottom').removeClass('deactiveSelector');
            }
        } else {
            $('.datepickerDefault')
                .attr('disabled', 'true')
                .css('display', 'inline-block');
            $('.datepickerField')
                .css('opacity', '0')
                .attr('disabled', 'true');
            $('.selectors_right_top').addClass('deactiveSelector');

            $('#salonMitarbeiterField').attr('disabled', 'true');
            $('.selectors_right_bottom').addClass('deactiveSelector');

            $('#servicePackageField').attr('disabled', 'true');
            $('.selectors_left_bottom').addClass('deactiveSelector');
        }
        servicePackageButton();
    });

    $('.datepickerDefault').live('click', 'onDatepickerClick');

    function onDatepickerClick(triggerIt) {
        $('.datepickerField')
            .css('opacity', '1')
            .removeAttr('disabled');
        $('.datepickerDefault')
            .css('display', 'none')
            .addClass('notShown');

        $('#salonMitarbeiterField').removeAttr('disabled');
        $('.selectors_left_bottom').removeClass('deactiveSelector');

        //$('#datepicker').trigger("click");
        setTimeout(function() {
            if (triggerIt) {
                $('#datepicker').trigger('click');
            }
            // START CACHING
            if (selectAppointmentNavigation.restoreField('salonMitarbeiterField')) {
                $('#salonMitarbeiterField').trigger('change');
            }
            // END CACHING
        }, 200);
    }

    // Info Icon Dienstleistungen und Button
    servicePackageButton();

    $('#servicePackageField').live('change', function() {
        emptyList();
        servicePackageButton();
    });

    // Bei Änderung des Salonmitarbeiters
    $('#salonMitarbeiterField').live('change', function() {
        // Wenn kein Mitarbeiter gewählt
        if ($('#salonMitarbeiterField').val() != 'chooseMitarbeiter') {
            selectAppointmentNavigation.setDisabledStates(false);
            $('.dienstleistungTipBtn').css('visibility', 'hidden');
            $('.selectors_right_bottom').removeClass('deactiveSelector');
            $('#servicePackageField').attr('disabled', 'true');
        } else {
            $('.selectors_right_bottom').addClass('deactiveSelector');
            $('#servicePackageField').attr('disabled', 'true');
        }
        servicePackageButton();
    });

    /**
     * Beeinflussung der Dienstleistungsicons und des Termin finden Buttons
     * */
    function servicePackageButton() {
        if ($('#servicePackageField').val() == '' || $('#servicePackageField').attr('disabled') == 'disabled') {
            $('.dienstleistungTipBtn').css('visibility', 'hidden');

            $('.terminSendButton').css('display', 'none');
            $('.terminSendButtonInactive').css('display', 'inline-block');
        } else {
            $('.dienstleistungTipBtn').css('visibility', 'visible');

            $('.terminSendButton').css('display', 'inline-block');
            $('.terminSendButtonInactive').css('display', 'none');
        }
    }

    function emptyList() {
        $('.middle#list').empty();
    }

    /* Register event handler for the selection process*/

    function roundWidth(number, decimals) {
        //Rundet eine Zahl auf eine bestimmte Nachkommastelle
        return Math.round(number * Math.pow(10, decimals)) / Math.pow(10, decimals);
    }

    selectAppointmentNavigation = (function() {
        /* Navigation settings */
        var prevButton = $('#selectPrev');
        var nextButton = $('#selectNext');
        var position = 0;
        var stepElementIds = ['#select-salon', '#select-date', '#select-employee', '#select-service', '#select-time'];
        var step_salon = 0;
        var step_date = 1;
        var step_employee = 2;
        var step_service = 3;
        var step_time = 4;

        var _preset_next_step = false;

        var tip_button = $('.dienstleistungTipBtn');

        /* Data fields */
        var salon_container = $('#salonSelectField');
        var salon_field_id = 'salonField';
        var salon = null;

        var mitarbeiter_container = $('#mitarbeiterSelectField');
        var mitarbeiter_field_id = 'salonMitarbeiterField';
        var mitarbeiter = null;
        var mitarbeiter_wrapper_step_4 = $('.employer-wrapper');
        var mitarbeiter_step_4 = mitarbeiter_wrapper_step_4.find('.employer');

        var mitarbeiter_slider_id = 'mitarbeiterSlider';
        var mitarbeiter_slider = null;

        var mitarbeiter_all_container = $('#employers-all-slider');
        var mitarbeiter_all_slider_id = 'mitarbeiterAllSlider';
        var mitarbeiter_all_slider = null;

        var service_container = $('#servicepackageSelectField');
        var service_field_id = 'servicePackageField';
        var service = null;
        var service_tip = $('.dienstleistungsTip');
        var service_tip_button = $('.dienstleistungTipBtn');

        var datepicker_default = $('.datepickerDefault');
        var datepicker = $('#datepicker');
        //var datePickerMobileElt = $('#datepicker-phone'); PM (02.02.2018) bloom`s // Webseite // Smartphone-Optimierung :: it is now GLOBAL

        /*var restored_fields = [];*/

        /* Other properties */
        var hide_phone_class = 'hidden-small-phone';

        function init() {
            _setSalons();
            _initEvents();
        }

        function _getUrlVar(key) {
            var result = new RegExp(key + '=([^&]*)', 'i').exec(window.location.search);
            return (result && result[1]) || '';
        }

        var isFirst = true;
        if (_getUrlVar('returned') == 'true' && _getUrlVar('returned') !== '') {
            _setStep(4);
            nextButton.prop('disabled', true);
            $('#servicePackageField').live('change', function() {
                var selectedName = $('#salonMitarbeiterField option:selected').text();
                if (selectedName == 'Bitte Mitarbeiter auswählen') {
                    //_setStep(2);
                } else {
                    var selectedId = $('#salonMitarbeiterField option:selected').val();

                    //PM (20.03.2018) bloom`s // Terminbuchung // Erweiterung // Bestätigungscode :: bug fix :: deprecated
                    //var selectedImage = $('li[data-mitarbeiter-selector="' + selectedId + '"').addClass("active");

                    $('.employer-wrapper')
                        .find('.termin-datum')
                        .find('span')
                        .text($('#datepicker-phone').val());
                    //$(".employer-wrapper").find(".employer-img").attr("src", selectedImage);
                    $('.employer-wrapper')
                        .find('.employer-name')
                        .find('span')
                        .text(selectedName);
                    $('.employer-wrapper')
                        .find('.termin-datum')
                        .find('span')
                        .text($('#datepicker-phone').val());
                    if (isFirst)
                    //_getDayTimes();
                        isFirst = false;
                }
            });
        }

        /**
         * sets the value of the local storage
         * @param {string} field
         * @returns {Boolean}
         */
        function _restoreField(field) {
            var value = _getLocalStorageValue(field);
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

        function _emptyList() {
            $('.middle#list').empty();
        }

        function _set2x2Slider(slider) {
            slider.flexisel({
                visibleItems: 4,
                proRow: 2,
                rows: 2,
                itemsToScroll: 4,
                infinite: false,
                navigationTargetSelector: slider.find('.nbs-flexisel-wrapper .nbs-navigation-wrapper'),
                autoPlay: {
                    enable: false,
                    interval: 5000,
                    pauseOnHover: true
                },
                responsiveBreakpoints: {
                    portrait: {
                        changePoint: 480,
                        visibleItems: 4,
                        proRow: 2,
                        rows: 2,
                        itemsToScroll: 4
                    },
                    landscape: {
                        changePoint: 640,
                        visibleItems: 4,
                        proRow: 2,
                        rows: 2,
                        itemsToScroll: 4
                    }
                }
            });
            slider.find('[data-mitarbeiter-selector]').on('click', function(e) {
                var m_val = $(this).data('mitarbeiter-selector');
                _setActiveMitarbeiterInSlider(slider, m_val);
                _setActiveMitarbeiterInSlider(mitarbeiter_all_slider, m_val);
                mitarbeiter.val(m_val).trigger('change');
            });
        }

        function _set5x1Slider(slider, slider_wrapper) {
            slider.flexisel({
                visibleItems: 5,
                proRow: 5,
                rows: 1,
                itemsToScroll: 1,
                infinite: true,
                navigationTargetSelector: '#' + slider_wrapper.attr('id') + ' .nbs-flexisel-wrapper .nbs-navigation-wrapper',
                autoPlay: {
                    enable: false,
                    interval: 5000,
                    pauseOnHover: true
                },
                responsiveBreakpoints: {
                    portrait: {
                        changePoint: 480,
                        visibleItems: 5,
                        proRow: 5,
                        rows: 1,
                        itemsToScroll: 1
                    },
                    landscape: {
                        changePoint: 640,
                        visibleItems: 5,
                        proRow: 5,
                        rows: 1,
                        itemsToScroll: 1
                    },
                    tablet: {
                        changePoint: 768,
                        visibleItems: 5,
                        proRow: 5,
                        rows: 1,
                        itemsToScroll: 1
                    }
                }
            });
            slider.find('[data-mitarbeiter-selector]').on('click', function(e) {
                var m_val = $(this).data('mitarbeiter-selector');
                _setActiveMitarbeiterInSlider(slider, m_val);
                _setActiveMitarbeiterInSlider(mitarbeiter_slider, m_val);
                mitarbeiter.val(m_val).trigger('change');
                _getDayTimes();
                nextButton.prop('disabled', true);
            });
        }

        function _setActiveMitarbeiterInSlider(_slider, value) {
            if (null === mitarbeiter) return;
            _slider.find('[data-mitarbeiter-selector]').removeClass('active');
            if (typeof value === 'undefined') {
                var m_val = mitarbeiter.val();
            } else {
                var m_val = value;
            }
            var selected = _slider.find('[data-mitarbeiter-selector="' + m_val + '"]');
            selected.addClass('active');
            selected.find('.caption span').each(function(a, b) {
                var that = $(this);
                var p_w = that.parent().width();
                $('.employer-name')
                    .find('span')
                    .html($(this).html());
                //that.css('background-color', '#474436');
            });
        }

        function _onDatepickerClick(triggerIt) {
            datepicker.css('opacity', '1').removeAttr('disabled');
            datePickerMobileElt.css('opacity', '1').removeAttr('disabled');
            datepicker_default.css('display', 'none').addClass('notShown');
            mitarbeiter.removeAttr('disabled');
            $('.selectors_left_bottom').removeClass('deactiveSelector');
            setTimeout(function() {
                if (triggerIt) {
                    datepicker.trigger('click');
                }

                /* PM (21.01.2019) Bloom´s // Webseite // Bugfixes Salon+Terminbuchung :: bug fix @deprecated
        if (_restoreField(mitarbeiter_field_id)) {
          if(null != mitarbeiter) mitarbeiter.trigger('change');
        }
        */

                //PM (21.01.2019) Bloom´s // Webseite // Bugfixes Salon+Terminbuchung :: bug fix :: restore employe on desktop only
                if (PAGECON_WINDOW.width() > MAX_WIDTH_TABLET && _restoreField(mitarbeiter_field_id) && mitarbeiter !== null) mitarbeiter.trigger('change');
            }, 200);
        }

        function _setSalons() {
            var mode = 'getSalons';
            var salon_id_from_url = _getUrlVar('salonId');
            jQuery.ajax({
                type: 'POST',
                url: '/modules/mod_terminfinder/helper.php',
                data: { mode: mode, salonId: salon_id_from_url },
                dataType: 'html',
                error: function alarm() {},
                success: function(data) {
                    salon_container.empty().html(data);
                    salon = salon_container.find('#' + salon_field_id);
                    _setSalonEvents();
                    if (_restoreField(salon_field_id) || salon_id_from_url) {
                        salon.trigger('change');
                    }
                    tip_button.unbind('mouseover mouseout');
                }
            });
        }

        function _setSalonMitarbeiter() {
            var mode = 'getSalonMitarbeiter';
            jQuery.ajax({
                type: 'POST',
                url: '/modules/mod_terminfinder/helper.php',
                data: { mode: mode, salonId: salon.val() },
                dataType: 'json',
                error: function alarm() {},
                success: function(data) {
                    var data_html = '';
                    if (data.html_select) data_html += data.html_select;
                    if (data.html_slider) data_html += data.html_slider;
                    mitarbeiter_container.empty().html(data_html);
                    mitarbeiter = mitarbeiter_container.find('#' + mitarbeiter_field_id);
                    mitarbeiter_slider = mitarbeiter_container.find('#' + mitarbeiter_slider_id);

                    data_html = '';
                    if (data.html_all_slider) data_html += data.html_all_slider;
                    mitarbeiter_all_container.empty().html(data_html);
                    mitarbeiter_all_slider = mitarbeiter_all_container.find('#' + mitarbeiter_all_slider_id);

                    _onDatepickerClick(false);
                    _setMitarbeiterEvents();
                    _set2x2Slider(mitarbeiter_slider);
                    _set5x1Slider(mitarbeiter_all_slider, mitarbeiter_all_container);
                    tip_button.unbind('mouseover mouseout');
                    if (!datepicker_default.hasClass('notShown')) {
                        mitarbeiter.attr('disabled', 'true');
                    }
                    _setDisabledStates();
                    //_setSalonServicePackage(); //PM (27.03.2019) remove
                }
            });
        }

        function _setMitarbeiterServicePackage() {
            var mode = 'getMitarbeiterServicepackage';
            jQuery.ajax({
                type: 'POST',
                url: '/modules/mod_terminfinder/helper.php',
                data: { mode: mode, salonId: salon.val(), mitarbeiterId: mitarbeiter.val() },
                dataType: 'html',
                error: function(data) {
                    var content = '<select id="' + service_field_id + '" class="selector_field_input" disabled><option value="">Bitte Dienstleistung auswählen</option></select>';
                    service_container.empty().html(content);
                    service = service_container.find('#' + service_field_id);
                    service.trigger('change');
                },
                success: function(data) {
                    service_container.empty().html(data);
                    service = service_container.find('#' + service_field_id);
                    _setServiceEvents();
                    tip_button.unbind('mouseover mouseout');
                    // START CHACHING
                    _restoreField(service_field_id);
                    // END CHACHING
                    service.trigger('change');
                }
            });
        }

        function _setSalonServicePackage() {
            var mode = 'getSalonServicepackage';
            jQuery.ajax({
                type: 'POST',
                url: '/modules/mod_terminfinder/helper.php',
                data: { mode: mode, salonId: salon.val() },
                dataType: 'html',
                error: function alarm() {
                    var content = '<select id="' + service_field_id + '" class="selector_field_input" disabled><option value="">Bitte Dienstleistung auswählen</option></select>';
                    service_container.empty().html(content);
                    service = service_container.find('#' + service_field_id);
                    service.trigger('change');
                },
                success: function(data) {
                    service_container.empty().html(data);
                    service = service_container.find('#' + service_field_id);
                    _setServiceEvents();
                    tip_button.unbind('mouseover mouseout');
                    // START CHACHING
                    _restoreField(service_field_id);
                    service.trigger('change');
                    // END CHACHING
                }
            });
        }

        function _setSalonEvents() {
            salon.change(function(e) {
                if (step_salon === position) {
                    nextButton.prop('disabled', !salon.val());
                }
                _setLocalStorageValue(salon);
                _emptyList();

                if (salon && salon.val() && salon.val() != 0) {
                    _setSalonMitarbeiter();
                } else {
                    _setDisabledStates(true);
                    mitarbeiter_container.empty().html('<select class="selector_field_input" disabled><option>Bitte zuerst Salon auswählen</option></select>');
                    mitarbeiter = null;
                }
            });
        }

        function _setMitarbeiterEvents() {
            mitarbeiter.on('change', function() {
                if (step_employee === position) {
                    if (mitarbeiter.val() === 'chooseMitarbeiter') {
                        nextButton.prop('disabled', true);
                    } else {
                        nextButton.prop('disabled', false);
                    }
                }
                _setLocalStorageValue(mitarbeiter);
                _emptyList();

                if (mitarbeiter.val() == 'chooseMitarbeiter') {
                    $('.selectors_right_bottom').addClass('deactiveSelector');
                    if (service) {
                        service.attr('disabled', true);
                    }
                    return; // Abrechen der Funktion!
                }

                $('.selectors_right_bottom').removeClass('deactiveSelector');

                if (mitarbeiter_slider) {
                    var selected = mitarbeiter_slider.find('.active');
                    //console.log(selected);
                    var selectedImage = selected.find('img').attr('src');
                    var selectedName = selected
                        .find('.caption')
                        .find('span')
                        .text();
                    mitarbeiter_wrapper_step_4.find('.employer-img').attr('src', selectedImage);
                    mitarbeiter_wrapper_step_4
                        .find('.employer-name')
                        .find('span')
                        .text(selectedName);
                    mitarbeiter_wrapper_step_4
                        .find('.termin-datum')
                        .find('span')
                        .text($('#datepicker-phone').val());
                }

                if (mitarbeiter.val()) {
                    _setMitarbeiterServicePackage();
                } else {
                    _setSalonServicePackage();
                }
            });
        }

        function _setServiceEvents() {
            service.change(function() {
                if (step_service === position) {
                    nextButton.prop('disabled', !service.val());
                }
                var mode = 'getServicePackageDescription';
                jQuery.ajax({
                    type: 'POST',
                    url: '/modules/mod_terminfinder/helper.php',
                    data: { mode: mode, dienstleistungsId: _getDienstleistungId() },
                    dataType: 'html',
                    error: function alarm() {},
                    success: function(data) {
                        service_tip.empty().html(data);
                        if (service.val() != '') {
                            service_tip_button
                                .on('mouseover', function() {
                                    service_tip.fadeIn('slow');
                                })
                                .on('mouseout', function() {
                                    service_tip.fadeOut('slow');
                                });
                        } else {
                            service_tip_button.off('mouseover mouseout');
                        }
                    }
                });
                /* PM (07.02.2018) impossible to debug with so many logs
        console.log('------------------');
        console.log(service);
        console.log(service.val());
        console.log('------------------');
        */
                _setLocalStorageValue(service, null, false);
                _emptyList();
            });
        }

        function _initEvents() {
            /* Change resolution / orientation events */
            $('body').bind('orientationchange', function(e) {});
            $(window).on('resize', function() {});

            /* Navigation buttons events */
            prevButton.click(function() {
                _setStep(true);
                nextButton.unbind('click');
                nextButton.click(function() {
                    _setStep(_preset_next_step);
                });
            });

            nextButton.click(function() {
                _setStep(_preset_next_step);
            });
        }

        function _getDienstleistungId() {
            var dienstleistungId = service_container.find('#' + service_field_id + ' option:selected').data('childid');
            if (!dienstleistungId || _getLocalStorageValue('hairlength') !== 'true') {
                dienstleistungId = service_container.find('#' + service_field_id).val();
            }
            //console.log('dienstleistungId 1 - ', dienstleistungId);
            if (!dienstleistungId) {
                dienstleistungId = _getLocalStorageValue(service_field_id);
            }
            //console.log('dienstleistungId 2 - ', dienstleistungId);
            return dienstleistungId;
        }

        function _checkOrientation() {
            if (typeof window.orientation == 'undefined') {
                return true; //not a mobile
            }
            if (Math.abs(window.orientation) != 90) {
                return false; //portrait mode
            } else {
                return true; //landscape mode
            }
        }

        function _loadTerminePlan() {
            if (mitarbeiter && mitarbeiter.val()) {
                mitarbeiter_all_container.hide();
                mitarbeiter_step_4.show();
            } else {
                mitarbeiter_all_container.show();
                mitarbeiter_step_4.hide();
            }

            _getDayTimes();
        }

        function _getDayTimes() {
            //PM (25.01.2018) moved to the top to globalise them
            const show_more_button = '.mitarbeiter-day-times #show-more',
                moreBtn = $(show_more_button);

            if (!mitarbeiter || !mitarbeiter.val()) {
                $('#select-time .date-table').html('<div id="no-data-available"><p>Wählen Sie bitte Mitarbeiter/in.</p></div>');

                /**
                 * PM (25.01.2018) bloom`s // Webseite // Smartphone-Optimierung :: implicitly click the more button & other stuff
                 * this is the first of 2 interfaces to the optimised version
                 * @see ./terminfinder_optimisation
                 */
                try {
                    APPOINTMENT_MAKER.timeSlotsLoad({
                        //try/catch in case the function can't found (backward compatibility)
                        employees_container_id: mitarbeiter_slider_id,
                        load: _loadTerminePlan,
                        button_more: moreBtn,
                        button_prev: prevButton,
                        button_next: nextButton //PM (21.01.2019) Bloom´s // Webseite // Bugfixes Salon+Terminbuchung :: bug fix
                    });
                    APPOINTMENT_MAKER.buttonsLoad({
                        employees_container_id: mitarbeiter_slider_id,
                        date_picker: datePickerMobile,
                        date_picker_elt: datePickerMobileElt,
                        date_picker_millis: nextMonth.getTime()
                    });
                } catch (e) {}

                return;
            }

            $('#select-time .date-table').load(
                '/modules/mod_terminfinder/helper.php', {
                    mode: 'getDayTimes',
                    salonId: salon.val(),
                    mitarbeiterId: mitarbeiter.val(),
                    dienstleistungId: _getDienstleistungId(),
                    date: _getLocalStorageValue('datepicker') //localStorage.getItem('datepicker'),
                },
                function() {
                    var max_count = 15;

                    var hide_show_more_button = true;
                    var mitarbeiter_list = $('.mitarbeiter-day-times .timetable li');

                    mitarbeiter_list.each(function(a, b) {
                        if (max_count - 1 < a) {
                            hide_show_more_button = false;
                            return false;
                        }
                        if ($(this).hasClass('notAvailable')) {
                            max_count++;
                        } else {
                            $(b).show('slow');
                        }
                    });
                    if (hide_show_more_button) {
                        $(show_more_button).hide();

                        APPOINTMENT_MAKER.buttonsLoad({
                            employees_container_id: mitarbeiter_slider_id,
                            date_picker: datePickerMobile,
                            date_picker_elt: datePickerMobileElt,
                            date_picker_millis: nextMonth.getTime()
                        });
                    } else {
                        moreBtn.on('click', function() {
                            $(this).hide('slow');
                            mitarbeiter_list.each(function(a, b) {
                                $(b).show('slow', function() {});
                            });
                        });

                        /**
                         * PM (25.01.2018) bloom`s // Webseite // Smartphone-Optimierung :: implicitly click the more button & other stuff
                         * this is the first of 2 interfaces to the optimised version
                         * @see ./terminfinder_optimisation
                         */
                        try {
                            APPOINTMENT_MAKER.timeSlotsLoad({
                                employees_container_id: mitarbeiter_slider_id,
                                load: _loadTerminePlan,
                                button_more: moreBtn,
                                employees: mitarbeiter_list
                            });
                            APPOINTMENT_MAKER.buttonsLoad({
                                employees_container_id: mitarbeiter_slider_id,
                                date_picker: datePickerMobile,
                                date_picker_elt: datePickerMobileElt,
                                date_picker_millis: nextMonth.getTime()
                            });
                        } catch (e) {} //try/catch in case the function can't found (backward compatibility)
                    }
                }
            );
        }

        //Set Current Step
        function _setStep(inverse, next_step) {
            if (typeof next_step == 'undefined') next_step = false;
            _preset_next_step = next_step;
            if (inverse) {
                position--;
            } else {
                position++;
            }
            if (typeof inverse !== 'boolean') {
                //if fixed step was defined
                position = parseInt(inverse);
            }
            position = Math.max(position, 0);
            position = Math.min(position, stepElementIds.length - 1);

            prevButton.prop('disabled', 0 == position);
            nextButton.prop('disabled', 3 == position || 4 == position);

            for (var i = 0; i < stepElementIds.length; i++) {
                if (i == position) {
                    try {
                        APPOINTMENT_MAKER.hideScroller();
                    } catch (e) {}

                    $(stepElementIds[i]).removeClass(hide_phone_class);
                } else {
                    $(stepElementIds[i]).addClass(hide_phone_class);
                }
            }

            switch (position) {
                case 0: // Select Salon
                    prevButton.prop('disabled', true);
                    break;
                case 1: // Select Date
                    if (datePickerMobileElt.val() === '') {
                        nextButton.prop('disabled', true);
                    }
                    break;
                case 2: // Select Employee
                    $(window).trigger('resize');
                    $('div#mitarbeiterSelectField, div#mitarbeiterSelectField > div, ul#mitarbeiterSlider, ul#mitarbeiterSlider > li.nbs-flexisel-item.active').css('display', 'block');
                    //$(stepElementIds[position]).css("display", "block")
                    /*
              windowWidth = $(window).width();
              if (windowWidth <= MAX_WIDTH_PHONE) $('.submit-panel').hide();
              $(stepElementIds[position]).removeClass(hide_phone_class +" deactiveSelector")
              */
                    if (null != mitarbeiter && mitarbeiter.val() === 'chooseMitarbeiter') {
                        nextButton.prop('disabled', true);
                    } else {
                        nextButton.prop('disabled', false);
                    }
                    break;
                case 3: // Select Service
                    if (service && service.val()) {
                        nextButton.prop('disabled', false);
                    } else {
                        nextButton.prop('disabled', true);
                    }
                    break;
                case 4: // Select Time
                    $(window).trigger('resize');

                    _loadTerminePlan();
                    nextButton.prop('disabled', true);
                    break;
            }

            /**
             * PM (15.03.2018) bloom`s // Webseite // Terminbuchung // Smartphone-Optimierung
             * @see ./terminfinder_optimisation
             */
            try {
                APPOINTMENT_MAKER.setFooter({ employees_container_id: mitarbeiter_slider_id, position: position, button_next: nextButton });
            } catch (e) {
                console.log(e);
            } //try/catch in case the function can't found (backward compatibility)
        }

        function _setDisabledStates(forceDisable) {
            if (forceDisable) {
                nextButton.prop('disabled', true);
            } else {
                nextButton.prop('disabled', false);
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
                            localStorage.setItem(key, value);

                            //localStorage.setItem(field, value);
                        }
                    } else {
                        if (set_empty || field.val() != '') {
                            //PM (03.04.2018)
                            key = field.attr('id');
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

        init();

        /**
         * PM (02.02.2018) bloom`s // Webseite // Smartphone-Optimierung
         * this is the last of 2 interfaces to the optimised version
         * @see ./terminfinder_optimisation
         */
        try {
            APPOINTMENT_MAKER.initOptimisation({
                date_picker: datePickerMobile,
                date_picker_elt: datePickerMobileElt,
                date_picker_millis: nextMonth.getTime(),
                date_picker_gen: getDatePicker,
                date_picker_select: selectDatePicker,
                employees_container_id: mitarbeiter_slider_id,
                load: function(employerId) {
                    //PM (29.03.2019)
                    if (employerId) mitarbeiter.val(employerId);
                    //DO NOT SWITCH
                    _loadTerminePlan();
                },
                button_prev: prevButton
            });
        } catch (e) {}

        /* public functions */
        return {
            nextStep: function() {
                _setStep(false);
            },
            prevStep: function() {
                _setStep(true);
            },
            setStep: function(step, nest_step) {
                _setStep(step, nest_step);
            },
            loadTerminePlan: function() {
                return _loadTerminePlan();
            },
            checkOrientation: function() {
                return _checkOrientation();
            },
            getDienstleistungId: function() {
                return _getDienstleistungId();
            },
            getDayTimes: function() {
                _getDayTimes();
            },
            setDisabledStates: function(forceDisable) {
                _setDisabledStates(forceDisable);
            },
            setLocalStorageValue: function(field, value) {
                _setLocalStorageValue(field, value);
            },
            clearLocalStorageValue: function(field) {
                _setLocalStorageValue(field, '');
            },
            getLocalStorageValue: function(field) {
                return _getLocalStorageValue(field);
            },
            restoreField: function(field) {
                return _restoreField(field);
            }
        };
    })();
});