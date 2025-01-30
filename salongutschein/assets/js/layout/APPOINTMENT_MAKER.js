/**
 * Company: PageCon GmbH
 * User: patrickmeppe
 * PM (25.01.2018) bloom`s // Webseite // Smartphone-Optimierung
 * @see ./terminfinder.js:914
 * show_more_button is simulately clicked right after it's loaded
 * this approach reduces the adaptation effort to the minimum
 */
const APPOINTMENT_MAKER = new(function() {
    //scrollContainer = '#page-termine',
    const storage = {},
        millisInDay = 24 * 60 * 60 * 1000; //PM (26.03.2019)
    let employees;

    //PM (10.04.2018) old browser flag
    try {
        localStorage.setItem('dummy', 1);
        localStorage.getItem('dummy');
    } catch (e) {
        $(document.body || 'body').addClass('pagecon-browser-deprecated'); //PM (03.04.2018) bloom`s // Webseite // Terminbuchung // Smartphone-Optimierung :: bug fix: iOS7-iPhone4
    }
    //@see https://stackoverflow.com/questions/12049620/how-to-get-get-variables-value-in-javascript

    /**
     * PM (12.03.2018)
     * this function is used to (re)send the code
     */
    this.sendCode = function() {
        var appointmentId = $('#form_appointment_id > input[type=hidden][name=appointment_id]').val();
        let appointmentIdSS = $.totalStorage('appointmentId', appointmentId);
        console.log('apr-Id-SS:' + appointmentIdSS);
        console.log('apr-Id:' + appointmentId);
        if (appointmentId == '') $('#getCode').click();
        //send the code for the first time
        else {
            //resend the code
            $('#mobilenumberSecond').val($('#mobilenumber').val());
            $('#h_appointmentId').val(appointmentId);
            //DO NOT SWITCH
            $('#getCodeSecond').click();
        }
    };

    this.beenden = function() {
        window.location.href = BloombaseUrl + '/termine.html';
    };

    this.initTriggerWizard = function(elt, index) {
        // $('#page-termine').bootstrapWizard('show', index);
        if (index == 5) {
            let tab_id = 'pills-fertig';
        }
        let id = 'pills-fertig';
        $('.nav-pills li.nav-item > a[href="#' + id + '"]').tab('show');
        elt.dialog('close');
    };

    /**
     * PM (12.03.2018)
     * this function is used to set the confirmation code popup
     * @param query {string}
     * @param error {int}
     */
    this.setConfirmationCodePopup = function(query, error) {
        var elt = $(query);
        // console.log('CodePopup:: ' + error)
        if (error == 0) {
            elt.dialog({
                dialogClass: 'bloom-dialog dunkel-Geben',
                title: 'Geben Sie den Code ein',
                buttons: [{

                        class: 'btn btn-dark position-relative',
                        //left
                        text: 'Termin buchen',
                        /**
                         * PM (13.03.2018)
                         * this function is used to validate the code
                         * it is based on the function $("#confirmCode").click in terminbuchung.php::921
                         */
                        click: function() {
                            var codeElt = $('input.inputCodeTxt', elt);
                            var code = codeElt.val();
                            code = code.trim();
                            var inform = function(info) {
                                elt.addClass('pagecon-feedback');
                                $('p:last-child', elt).html(info);
                            };

                            if (code.length == 4) {
                                $('div#inputCode input#code').val(code);
                                codeElt.prop('disabled', true); //disable the input field
                                inform('In Bearbeitung...');
                                var buttons = $('bodytermine.html-body div.ui-dialog-buttonset > button').prop('disabled', true); //disable the buttons

                                console.log('send code: ' + code);

                                $.ajax({
                                    // timeout: 8000,
                                    type: 'POST',
                                    url: BloombaseUrl + '/appontment/confirmAppontmentCode',
                                    data: { code: code },
                                    // dataType: 'html',
                                    dataType: 'json',
                                    error: function(jqXHR, exception) {

                                        inform('Der Code konnte nicht übermittelt werden. Bitte versuchen Sie es erneut.');
                                        codeElt.prop('disabled', false); //enable the input field
                                        buttons.prop('disabled', false);

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
                                        console.log('ERROR send code: ' + msg);
                                    },


                                    success: function(res_json) {
                                        if (res_json instanceof Object) {
                                            var json = res_json;
                                            console.log(JSON.stringify(res_json));
                                        } else {
                                            var json = jQuery.parseJSON(res_json);
                                        }
                                        // return;

                                        let response = json.returncodevalue;
                                        var feedback;

                                        var returncodevalue = parseInt(response.split(':')[0]);

                                        switch (
                                            returncodevalue //response.replace(/:?.*$/g, "") @alternative
                                        ) {
                                            case 1:
                                            case 4: // The appointment has already been confirmed

                                                //TRUE if the confirmation was successful
                                                // $('#h_mobil').val($('#mobilenumber').val());
                                                // $('#formCodeConfirm').submit();
                                                // $('#page-termine').bootstrapWizard('show', 5);

                                                if (returncodevalue == 1) {
                                                    APPOINTMENT_MAKER.initTriggerWizard(elt, 5);
                                                    return; //end of the line
                                                } else {

                                                    feedback = 'Ihr Termin wurde erfolgreich bestätigt.';
                                                    inform(feedback);
                                                    setTimeout(function() {
                                                        APPOINTMENT_MAKER.initTriggerWizard(elt, 5);
                                                        return; //end of the line
                                                    }, 1300);
                                                }

                                                break;
                                            case 2:
                                                feedback = 'Der Bestätigungscode ist ungültig.';
                                                break;
                                                // case 4: disabled 17.06.2020  the system should lead me to the page Fertig
                                            case 4444444:
                                                // feedback = 'Der Termin wurde bereits bestätigt.'; 05.03.2020 12:06
                                                feedback = 'Ihr Termin wurde erfolgreich bestätigt.';
                                                break;
                                            case 8:
                                                feedback = 'Beim Speichern des Termins trat ein Fehler auf, der Termin wurde <strong>nicht</strong> bestätigt.';
                                                break;
                                            case 16:
                                                feedback = 'Allgemeiner Fehler.';
                                                break;
                                            case 32:
                                                feedback = 'Der Bestätigungscode hat ein ungültiges Format.';
                                                break;
                                            default:
                                                feedback = 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.';
                                        }
                                        inform(feedback);

                                        codeElt.prop('disabled', false); //enable the input field
                                        buttons.prop('disabled', false);
                                    }
                                });
                            } else inform('Der Code ist ungültig.');
                        }
                    },
                    {

                        class: 'btn btn-dark position-relative',
                        //right
                        text: 'Code erneut anfordern',
                        /**
                         * PM (13.03.2018)
                         * this function is used to go back to the page 3
                         * it is based on the function backBtn in terminbuchung.php::1678
                         */
                        click: function() {
                            var form = '#form_appointment_id';
                            $(form + ' > input[name=resend_code]').val(1);
                            // $(form).submit();
                            $('#getCodeSecond').click();
                            // $('#getCodeBtn').click();
                        }
                    }
                ],
                draggable: false,
                modal: true
            });
            $('> div', elt).html(
                '<div id="pagecon_dialog_code">\
                <p>Sie haben eine SMS erhalten.<br>' +
                $('#mobilenumber').val() +
                '</p>\
                <input style="min-width: 150px;" class="inputCodeTxt" size="6" minlength="4" maxlength="4" type="text" placeholder="Code eingeben"/>\
                <p></p>\
            </div>'
            );
            //DO NOT SWITCH

            $('body')
                .on('focus', 'div#pagecon_dialog_code > input', function() {
                    this.value = '';
                }) //empty the input field
                .on('keyup', 'div#pagecon_dialog_code > input', function() {
                    $('body.termine.html-body div.ui-dialog-buttonset > button:first-child').prop('disabled', this.value.length != 4);
                });
            $('div#pagecon_dialog_code > input')
                .keyup()
                .focus();
        } else {
            let message;
            switch (error) {
                case 8:
                    message = 'Der Termin ist bereits reserviert. Das tut uns leid, bitte wählen Sie einen anderen Termin aus.';
                    break;
                case 32:
                    message = 'Fehler beim senden der SMS, bitte versuchen Sie es erneut.';
                    break;
                case 64:
                    message = 'Es konnte keine verbindung zum gewünschten Salon hergestellt werden, bitte versuchen Sie es erneut.';
                    break;
                case 16:
                    message = 'Appointment Already Confirmed';
                    //Appointment Already Confirmed
                case '-2':
                    message = 'Es konnte keine verbindung zum Remotedienst herstellen, versuchen Sie es erneut.';
                    break;
                default:
                    message = 'Es konnte keine verbindung zum Remotedienst herstellen, versuchen Sie es erneut.';
            }

            elt.dialog({
                dialogClass: 'bloom-dialog dunkel-hinweis',
                title: 'Hinweis',
                buttons: [{
                    text: 'OK',
                    click: function() {
                        $(this).dialog('close');
                    }
                }],
                draggable: false,
                modal: true
            });
            $('> div', elt).html(message);
        }
    };

    /**
     * PM (26.03.2019)
     * @param config
     */
    this.buttonsLoad = function(config) {
        const root = 'li#select-time';

        let date = config.date_picker.getDate();
        $('#datepicker-phone')
            .val()
            .replace(/(\d{2})\. ([\wä]+) (\d{4})/i, function($, d, m, y) {
                const months = { januar: '01', februar: '02', maerz: '03', märz: '03', april: '04', mai: '05', juni: '06', juli: '07', august: '08', september: '09', oktober: '10', november: '11', dezember: '12' };
                date = new Date(y + '-' + months[m.toLowerCase()] + '-' + d);

                return $;
            });

        //const date = config.date_picker.getDate()
        const millis = date.getTime(),
            today = new Date();
        today.setHours(0, 0, 0, 0); //today at 00:00:00.000 - 1h
        //console.log(date, millis - today.getTime(), millis - today.getTime() < millisInDay, config.date_picker_millis - millis, config.date_picker_millis - millis < millisInDay, millisInDay)
        $(root + ' button[res=day][dir=left]').prop('disabled', millis - today.getTime() < millisInDay);
        $(root + ' button[res=day][dir=right]').prop('disabled', config.date_picker_millis - millis < millisInDay);
        /*
        if(millis - today.getTime() < millisInDay) $(root +" button[res=day][dir=left]").prop("disabled", true)
        else if(config.date_picker_millis - millis < millisInDay) $(root +" button[res=day][dir=right]").prop("disabled", true)
        */

        const employeeA = $('ul#' + config.employees_container_id + ' > li.nbs-flexisel-item.active'); //the currently active/chosen employee
        const employeeAId = employeeA.attr('data-mitarbeiter-selector'); //unique for each employee
        for (let i in employees)
            if (employeeAId === $(employees[i]).attr('data-mitarbeiter-selector')) {
                //TRUE if the currently chosen employee has been found
                if (i <= 2) $(root + ' button[res=employee][dir=left]').prop('disabled', true);
                else if (i >= employees.length - 2) $(root + ' button[res=employee][dir=right]').prop('disabled', true);

                break;
            }
    };

    /**
     * PM (19.03.2019) Bloom´s // Webseite // Terminbuchung // Einführung Kundenkonto/ReconnectID >> Produktion
     * this function is used to remove the value related to the user from the cache
     * @param key string
     */
    this.handleReconnectId = function(key) {
        try {
            if (!localStorage.getItem(key)) {
                const names = ['vorname', 'nachname', 'mobilenumber', 'email', 'emailConfirm'];
                try {
                    for (let i = 0; i < names.length; i++) localStorage.removeItem(names[i]);
                } catch (e) {} //error thrown if "localStorage" isn't supported
            }
        } catch (e) {
            if (!storage[key]) {
                const names = ['vorname', 'nachname', 'mobilenumber', 'email', 'emailConfirm'];
                for (let i = 0; i < names.length; i++) delete storage[names[i]];
            }
        }
    };

    /**
     * PM (03.04.2018) bloom`s // Webseite // Smartphone-Optimierung :: bug fix iOS7-iPhone4
     * cache
     * @param key
     * @param value
     */
    this.set = function(key, value) {
        storage[key] = value;
    };
    this.get = function(key) {
        return storage[key];
    };
})(jQuery);