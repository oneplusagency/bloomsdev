// var first_tab, first_tab_activ, second_tab, second_tab_activ;


$.totalStorage.deleteItem('persDataConfirm');
// ** validator setDefaults
jQuery.validator.setDefaults({
    ignore: ':hidden',
    errorClass: 'validation-error-label',
    validClass: 'has-success',
    // debug: true,
    lang: 'de', // or whatever language option you have.
    highlight: function (element, errorClass, validClass) {
        $(element)
            .addClass(errorClass)
            .removeClass(validClass);
        $(element.form)
            .closest('.tt-form-group')
            .find('label[for=' + element.id + ']')
            .addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element)
            .removeClass(errorClass)
            .addClass(validClass);
        $(element.form)
            .closest('.tt-form-group')
            .find('label[for=' + element.id + ']')
            .removeClass(errorClass);
    }
});

$(document).ready(function () {
    $('form#gutschein #amount').on('focusout', function () {
        var inputVal = $(this).val();
        if (inputVal.length > 0 ) {
            if((inputVal.indexOf('.') === -1 && inputVal.indexOf(',') > 0 )){
            }else if((inputVal.indexOf(',') === -1 && inputVal.indexOf('.') > 0 )){
            }else{
                $(this).val(inputVal + '.00');
            }
        }
    });
    $('form#gutschein #amount').on('mouseout', function () {
        var inputVal = $(this).val();

        if (inputVal.length > 0 ) {

            if((inputVal.indexOf('.') === -1 && inputVal.indexOf    (',') > 0 )){
            }else if((inputVal.indexOf(',') === -1 && inputVal.indexOf('.') > 0 )){
            }else{
                $(this).val(inputVal + '.00');
            }

        }
    });
});

var ValidationUtilsBloom = new (function () {
    var self = this;
    /*
     * Utils for validation methods
     * Date 10.02.2020
     */
    /*
     * Custom validation method for the PLZ input in the firm form. Depending on the selecting Country (DE, AT, CH) , it
     * evaluates the correct PLZ.
     * @return true if the validation was successful, false otherwise
     */
    this.validatePLZ = function (value, element) {
        // var land = $('select#land_select').val();
        var land = 'DE';
        var valid = false;
        switch (land) {
            case 'DE':
                if (/(^\d{5}$)/i.test(value)) {
                    valid = true;
                }
                break;
            case 'CH':
                if (/(^\d{4}$)/i.test(value)) {
                    valid = true;
                }
                break;
            case 'AT':
                if (/(^\d{4}$)|(^\d{4}-\d{4}$)/i.test(value)) {
                    valid = true;
                }
                break;
        }
        return this.optional(element) || valid;
    };
    this.filterInt = function (input, defaultValue) {
        defaultValue = defaultValue || 0;
        var val = parseInt((input + '').replace(/[\D]/g, ''));
        return isNaN(val) ? defaultValue : val;
    };


    // Fill 4. Zusammenfassung  ValidationUtilsBloom.fillTabZusammenfassung
    this.fillTabZusammenfassung = function () {

        var counter = 0;
        let tab_gutschein = $('#Zusammenfassung'); // first id for TAB  4
        if (counter == 0) {




            // ****  SET DATA FOR  4. Zusammenfassung *****

            if (isAnyObject(persDataConfirm)) {

                var gutschein = $("form#gutschein").serializeObject();
                var persData = $("form#persDataConfirm").serializeObject();
                var gutscheindesign = $("form#gutschein-design").serializeObject();

                persDataConfirm = Object.assign(gutschein, persData, gutscheindesign);

                let rechnungsadresse_salutation = persDataConfirm.salutation;
                let rechnungsadresse_vorname = persDataConfirm.vorname;
                let rechnungsadresse_nachname = persDataConfirm.nachname;

                let rechnungsadresse_diffsalutation = persDataConfirm.diffsalutation;
                let rechnungsadresse_diffvorname = persDataConfirm.diffvorname;
                let rechnungsadresse_diffnachname = persDataConfirm.diffnachname;

                let rechnungsadresse_adresse = persDataConfirm.adresse;
                let rechnungsadresse_diffadresse = persDataConfirm.diffadresse;
                let rechnungsadresse_plz = persDataConfirm.plz;
                let rechnungsadresse_phone = persDataConfirm.phone;
                let rechnungsadresse_diffphone = persDataConfirm.diffphone;
                let rechnungsadresse_diffplz = persDataConfirm.diffplz;
                let rechnungsadresse_ort = persDataConfirm.ort;
                let rechnungsadresse_diffort = persDataConfirm.diffort;
                let rechnungsadresse_email = persDataConfirm.email;
                let rechnungsadresse_diffemail = persDataConfirm.diffEmail;

                $('#vname-nname-form', tab_gutschein).text(rechnungsadresse_salutation + ' ' + rechnungsadresse_vorname + ' ' + rechnungsadresse_nachname);
                $('#diffvname-nname-form', tab_gutschein).text(rechnungsadresse_diffsalutation + ' ' + rechnungsadresse_diffvorname + ' ' + rechnungsadresse_diffnachname);
                $('#email-form', tab_gutschein).text(rechnungsadresse_email);
                $('#diffemail-form', tab_gutschein).text(rechnungsadresse_diffemail);

                rechnungsadresse_adresse ? $('#adresse-form', tab_gutschein).text(rechnungsadresse_adresse) : $("form#Zusammenfassung #adresse-form").closest('div.row').hide();;
                $('#diffadresse-form', tab_gutschein).text(rechnungsadresse_diffadresse);

                rechnungsadresse_ort ? $('#ort-form', tab_gutschein).text(rechnungsadresse_ort) : $("form#Zusammenfassung #ort-form").closest('div.row').hide();;
                $('#diffort-form', tab_gutschein).text(rechnungsadresse_diffort);


                rechnungsadresse_ort ? $('#plz-form', tab_gutschein).text(rechnungsadresse_plz) : $("form#Zusammenfassung #plz-form").closest('div.row').hide();;
                // $('#plz-form', tab_gutschein).text(rechnungsadresse_plz);
                $('#diffplz-form', tab_gutschein).text(rechnungsadresse_diffplz);

                $("#diffAdressForm, #diffAdressForm .row.post, #diffAdressForm .row.email").css('display', 'none');


                if (persDataConfirm.diffAdress == 1) {
                    $("#diffAdressForm").css('display', 'block');
                    $("#diffAdressForm .row.email.post").css('display', 'flex');
                    if (persDataConfirm.shipment == 'per E-Mail') {
                        $("#diffAdressForm .row.email").css('display', 'flex');
                    }
                    if (persDataConfirm.shipment == 'per Post') {


                        $("#diffAdressForm .row.email").css('display', 'none');
                        $("#diffAdressForm .row.email.post").css('display', 'flex');
                        $("#diffAdressForm .row.post").css('display', 'flex');
                    }
                }
                $('#design-name-form > .design-numer', tab_gutschein).text(persDataConfirm.design);
                // versand-form
                $('#versand-form', tab_gutschein).text(persDataConfirm.shipment);
                $('#amount-form > .amount-sp', tab_gutschein).text(persDataConfirm.amount);
                ///f3-url-shortener/assets/images/email_layout_1.jpg
                let maket = BloombaseUrl + '/assets/images/email_layout_' + persDataConfirm.design + '.jpg';
                $('#design-img-form', tab_gutschein).prop('src', maket);
                $('#greetings-form', tab_gutschein).html(persDataConfirm.greetings);

                counter += 1;

            } else {
                $('#page-termine').bootstrapWizard('show', 2);
                // tab_gutschein.empty();
            }

        }
    }

})(jQuery);

// save to Form Cache **Meine Daten****
jQuery(document).ready(function ($) {


    //eigene Methodem für die jeweiligen Eingaben. Dies ist die Methode "namen"
    jQuery.validator.addMethod(
        'namen',
        function (value, element) {
            return this.optional(element) || /^[a-zA-\_\-ZüÜäÄöÖéèàâç ]*$/.test(value);
        },
        'Ungültige Zeichen!'
    );
    jQuery.validator.classRuleSettings.namen = { namen: true };

    //PLZ Prüfer
    jQuery.validator.addMethod(
        'zipcode',
        function (value, element) {
            return this.optional(element) || /^[0-9]+$/.test(value);
        },
        'Ungültige PLZ Zeichen!'
    ); //pattern gibt an dass zahlen von 0-9 möglich sind.
    jQuery.validator.classRuleSettings.zipcode = { zipcode: true };

    //Datum - Geburtstagdatum prüfer.
    jQuery.validator.addMethod(
        'bdate',
        function (value, element) {
            return this.optional(element) || /^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{2}$/.test(value);
        },
        'Ungültiger Geburtstag!'
    );
    jQuery.validator.classRuleSettings.bdate = { bdate: true };


    jQuery.validator.addMethod("expirydate", function (value, element, params) {
        var today = new Date();
        var startDate = new Date(today.getFullYear(), today.getMonth(), 1, 0, 0, 0, 0);
        var expDate;
        if (typeof params === 'string' && $(params).length > 0) {
            expDate = new Date($(params).val(), value, 0);
        } else {
            expDate = new Date(value.split("/")[1], value.split("/")[0], 0);
        }
        return Date.parse(startDate) <= Date.parse(expDate);
    }, "Das Ablaufdatum Ihrer Kreditkarte ist ungültig");


    jQuery.validator.addMethod('CCExpirationDate', function (value, element, params) {
        var currentDate = new Date(),
            currentMonth = currentDate.getMonth() + 1, // Zero based index
            currentYear = currentDate.getFullYear(),
            expirationMonth = Number(value.substr(0, 2)),
            expirationYear = Number(value.substr(3, value.length));


        if (expirationMonth < 1 || expirationMonth > 12) {
            //If month is not valid i.e not in range 1-12
            return false;
        }
        // Check Month for validity
        // https://github.com/gareththomasnz/jsCreditcard/blob/691484094b28ad494db88093f6129b4abc24dd21/js/scripts.js
        // Initialize End/Expiry date i.e. adding 10 years to expire
        // var futureLimitDate = new Date(today.getFullYear() + 10, today.getMonth(), 1, 0, 0, 0, 0);
        // Calculated new exp Date for ja
        // var today = new Date();
        // var startDate = new Date(today.getFullYear(), today.getMonth(), 1, 0, 0, 0, 0);
        // var expDate = value;
        // expDate = new Date(expirationYear, (expirationMonth - 1), 1, 0, 0, 0, 0);
        // if (Date.parse(startDate) <= Date.parse(expDate)) {
        //     if (Date.parse(expDate) <= Date.parse(futureLimitDate)) {
        //         // Date validated
        //         return true;
        //     } else {
        //         // Date exceeds future date
        //         return false;
        //     }
        // } else {
        //     // Date is earlier than todays date
        //     return false;
        // }


        // The expiration date must be atleast 1 month ahead of the current date.
        if ((expirationYear < currentYear) || (expirationYear == currentYear && expirationMonth <= currentMonth)) {
            return false;
        }
        return true;
    }, 'Das Ablaufdatum Ihrer Kreditkarte ist ungültig');
    jQuery.validator.classRuleSettings.CCExpirationDate = { CCExpirationDate: true };
    // https://github.com/alina2707/Tech-Elevator/blob/fdb5aa1d097d36cab9b9dd993ee35fc796bb707c/m4-02-java-history_geek_exercises/src/main/webapp/js/exercises/validation-exercises.js

    jQuery.validator.addMethod(
        'noSpace',
        function (value, element) {
            return value == '' || value.trim().length != 0;
        },
        "No space please and don't leave it empty"
    ),
        $.validator.addMethod(
            'RufnummerDE',
            function (value, element) {
                return checkDePhoneNumber(value);
            },
            'Bitte geben Sie eine deutsche Handynummer ein'
        );
});


var tval = $('textarea').val(),
    tlength = tval.length,
    set = 255,
    remain = parseInt(set - tlength);
$('#greetingscount').text("noch " + remain + " Zeichen von max. " + set);

$('#textarea').keypress(function (e) {
    var tval = $('textarea').val(),
        tlength = tval.length,
        set = 255,
        remain = parseInt(set - tlength);
    $('#greetingscount').text("noch " + remain + " Zeichen von max. " + set);
    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
        $('#textarea').val((tval).substring(0, tlength - 1));
        return false;
    }
})

let first_tab = $('#pills-gutscheinauswhal');
let first_tab_activ = first_tab.hasClass('active');
/*     var t_options = {
        maxCharacterSize: 255,
        originalStyle: 'originalTextareaInfo',
        warningStyle: 'warningTextareaInfo',
        warningNumber: 20,
        displayFormat: 'noch #left Zeichen von max. #max',
        maxLines: 3
    };

    $('#textarea').textareaCount(t_options); */
let second_tab = $('#pills-gutscheindesign');
let second_tab_activ = second_tab.hasClass('active');

(function ($) {
    'use strict';
    // active show 1. Gutscheinauswahl
    $('#versand', first_tab).on('change', function (e) {
        if ($(this).val() == 'per Post') {

            $('#hinweisVersand').fadeIn();
        } else {
            $('#hinweisVersand').fadeOut();

        }
    });
})(jQuery);

// $.totalStorage.deleteItem $.totalStorage.setItem $.totalStorage.getItem
// $.totalStorage.deleteItem('gutschein-design');
(function ($) {
    'use strict';
    // pills-gutscheindesign  2. Gutscheindesign
    if (true) {
        var fields = ['input[type=radio][name=design]'];
        $.each(fields, function (index, value) {
            $(value).on('change', saveDesignValue);
        });

        function saveDesignValue() {
            let valCheckedField = $(this).val();
            var versand = $('#versand option:selected').val();
            $('#diffdeliveremail').css('display', 'none');
            $('#diffdeliverpost').css('display', 'none');
            if (versand == 'per E-Mail' && diffShipment == '1') {
                $('#diffdeliveremail').css('display', 'flex');
            }
            if (versand == 'per Post' && diffShipment == '1') {
                $('#diffdeliverpost').css('display', 'flex');
            }
            $.totalStorage('gutschein-design', valCheckedField);
        }

        function restoreDesignField() {
            let value = $.totalStorage('gutschein-design');
            // console.log('design radio : ' + value);
            $('input[type=radio][name=design][value=' + value + ']', second_tab).prop('checked', true);
        }
        restoreDesignField();

        function restoreDesignTextarea() {
            let t_value = $.totalStorage('gutschein-design-textarea');
            // console.log('design-textarea: ' + t_value);
            $('#textarea', second_tab).val(t_value);
        }
        restoreDesignTextarea();
    }
})(jQuery);

// TAB 3 set Abweichende Versandadresse to  Nein if null
var diffShipment = 0;
let showVersandadresse = $.totalStorage('gutschein-design-diffadressbox');
showVersandadresse = ValidationUtilsBloom.filterInt(showVersandadresse, 'no');

jQuery(document).ready(function ($) {



    // alert(showVersandadresse)
    $('#defaultCheck2').prop('checked', true);
    $('#diffdeliveremail').css('display', 'none');
    $('#diffdeliverpost').css('display', 'none');

});

$('.diffAdress').change(function () {

    $('#diffdeliveremail').css('display', 'none');
    $('#diffdeliverpost').css('display', 'none');

    let value = $("input[name='diffAdress']:checked").val();
    let versand = $('select#versand option:selected').val();
    console.log(value);
    console.log(versand);

    if (value == '1') {
        $('.diffAdressBox').css('display', 'flex');
        if (versand == 'per E-Mail') {
            $('#diffdeliveremail').css('display', 'flex');
        }
        if (versand == 'per Post') {
            $('#diffdeliverpost').css('display', 'flex');
        }
        diffShipment = 1;
    } else {
        $('.diffAdressBox').css('display', 'none');
        $('#diffdeliverpost').css('display', 'none');
        $('#diffdeliveremail').css('display', 'none');
        diffShipment = 0;
    }


    // $.totalStorage('gutschein-design-diffemail', value);
    // $.totalStorage('diffShipment', diffShipment);

});


// Add a custom method to accept commas as decimal separators
$.validator.addMethod('numberWithComma', function(value, element) {
    // Use a regular expression to validate the input
    return this.optional(element) || /\d{1,3}[.,]\d{1,2}/.test(value);
    // return this.optional(element) || /^-?\d{1,3}(?:([,.])\d{3})*(?:\1\d{2})?$/.test(value);
}, 'Bitte geben Sie eine gültige Zahl ein.');

$.validator.addMethod('minimum1', function(value, element) {
    // Use a regular expression to validate the input
    return this.optional(element) || parseFloat(value.replace(',','.') ) >= 1 ;
    // return this.optional(element) || /^-?\d{1,3}(?:([,.])\d{3})*(?:\1\d{2})?$/.test(value);
}, 'Bitte geben Sie eine gültige Zahl ein.');

$.validator.addMethod('max999', function(value, element) {
    // Use a regular expression to validate the input
    return this.optional(element) || parseFloat(value.replace(',','.') ) <= 999 ;
    // return this.optional(element) || /^-?\d{1,3}(?:([,.])\d{3})*(?:\1\d{2})?$/.test(value);
}, 'Bitte geben Sie eine gültige Zahl ein.');


let $valid_gutschein = $('#gutschein').validate({
    rules: {
        amount: {
            required: true,
            // numberDE: true,
            // number: true,
            numberWithComma : true,
            minimum1 : true,
            max999 : true,
            // step: 1,
            // min: 1,
            // max: 999,
        },
    },
    
    messages: {
        amount: {
            required: 'Bitte wählen Sie Ihren Wunschbetrag aus.',
            // numberDE: 'Bitte geben Sie eine gültige Zahl ein.', // Update the message accordingly
            // number: 'Bitte geben Sie eine gültige Zahl ein.',
            numberWithComma: 'Bitte geben Sie eine gültige Zahl ein.',
            step: 'Bitte geben Sie eine ganzzahlige Zahl ein.',
            minimum1: 'Bitte geben Sie einen Betrag größer als 0 ein.',
            max999: 'Bitte geben Sie einen Betrag kleiner als oder gleich 999 ein.',
        }
    },

});

let $valid_radio_design = $('#gutschein-design').validate({
    errorPlacement: function (error, element) {
        if (element.attr('type') == 'radio') {
            if (element.hasClass('radio-btn-design')) {
                element = element.closest('.radio-group');
            }
            error.insertBefore(element);
        } else {
            error.insertAfter(element);
        }
    },
    rules: {
        design: {
            required: true
        }
    },
    messages: {
        design: {
            required: 'Bitte wählen Sie Ihren Wunschdesign aus.'
        }
    }
});

let $valid_persdataconfirm = $('#persDataConfirm').validate({
    ignore: ":not(:visible)",
    //#persDataConfirm steht für die formular ID im HTML.
    rules: {
        //Regeln welche jeweils mit den Methoden oben ausgeführt werden.
        salutation: {
            //vname steht für die ID vorname,
            required: true
        },
        vorname: {
            //vname steht für die ID vorname
            minlength: 2,
            required: true,
            namen: true
        },
        diffvorname: {
            //vname steht für die ID vorname
            minlength: 2,
            required: true,
            namen: true
        },
        nachname: {
            minlength: 2,
            required: true,
            namen: true
        },
        plz: {
            required: true,
            zipcode: true,
            minlength: 4,
            maxlength: 5
        },
        phone: {
            required: true
        },
        diffplz: {
            required: true,
            zipcode: true,
            minlength: 4,
            maxlength: 5
        },
        ort: {
            minlength: 2,
            required: true,
            namen: true
        },
        diffort: {
            minlength: 2,
            required: true,
            namen: true
        },
        adresse: {
            minlength: 3,
            required: true
            // namen: true
        },
        email: {
            required: true,
            email: true
        },
        emailConfirm: {
            email: true,
            equalTo: '#email'
        },
        diffEmailConfirm: {
            required: "#diffdeliveremail:visible",
            email: true,
            equalTo: '#diffEmail'
        },
        diffEmail: {
            required: "#diffdeliveremail:visible",
            email: true,
            equalTo: '#diffEmailConfirm'
        },
        phone: {
            RufnummerDE: true,
            required: {
                depends: function () {
                    $(this).val($.trim($(this).val()));
                    return true;
                }
            }
        }
    },

    success: function (element) {
        element
            .closest('.tt-form-group')
            .removeClass('error')
            .addClass('success');
    },
    //eigene Ausgabenachrichten eintragen
    messages: {
        diffsalutation: {
            required: 'Bitte einen Anrede angeben'
        },
        diffvorname: {
            required: 'Bitte einen diffvorname angeben'
        },
        diffnachname: {
            required: 'Bitte einen Nachname angeben'
        },
        diffadresse: {
            required: 'Bitte einen Straße und Hausnummer  angeben'
        },
        nachname: {
            required: 'Bitte einen Nachnamen angeben'
        },
        plz: {
            required: 'Bitte eine PLZ angeben'
        },
        ort: {
            required: 'Bitte eine ORT angeben'
        },
        diffplz: {
            required: 'Bitte eine PLZ angeben'
        },
        diffort: {
            required: 'Bitte eine ORT angeben'
        },
        email: {
            required: 'Bitte Email eingeben'
        },
        phone: {
            required: 'Bitte Handynummer eingeben'
        },
        emailConfirm: {
            required: 'Die Eingabe Ihrer E-Mail-Adresse stimmt nicht überein'
        },
        diffEmail: {
            required: 'Bitte Email eingeben'
        },
        diffEmailConfirm: {
            required: 'Die Eingabe Ihrer E-Mail-Adresse stimmt nicht überein'
        },
        phone: {
            required: 'Bitte geben Sie eine deutsche Handynummer ein',
            RufnummerDE: 'Bitte geben Sie eine deutsche Handynummer ein'
            // minlength: "Handynummer sollte eine 10-stellige Zahl sein",
            // maxlength: "Handynummer sollte eine 10-stellige Zahl sein",
            // digits: "Mobil sollten nur Zahlen enthalten"
        }
    }
});

// ****** CREDIT-CARD *******
// $('#bezahlungform').FormCache();
// https://stackoverflow.com/questions/26823718/jquery-validate-credit-card-expiry-date-in-rules-addmethod-limit-20-years-in-fut
// https://stackoverflow.com/questions/3231201/how-to-validate-credit-card-exp-date-with-jquery
// https://stackoverflow.com/questions/10705995/jquery-validate-js-expiration-date-how-to-use-this-validator-add-method
// https://forum.jquery.com/topic/credit-card-expiration-check
let $bezahlung_card = $('#bezahlungform').validate({
    rules: {

        cc_cardholder: {
            required: true
        },
        cc_number: {
            required: true,
            minlength: 13,
            maxlength: 16,
            creditcard: true
        },
        cc_date_combined: {
            CCExpirationDate: true
        },
        cc_cvc: {
            required: true,
            minlength: 3,
            maxlength: 4
        },
    },
    messages: {
        cc_cardholder: {
            required: "Bitte geben Sie Inhaber der Kreditkarte ein"
        },
        cc_number: {
            required: "Bitte geben Sie die Kreditkartennummer ein",
            minlength: "Die Kreditkartennummer muss zwischen 13 und 16 Ziffern liegen",
            maxlength: "Die Kreditkartennummer ist mehr als 16-stellig",
            creditcard: "Die Kreditkartennummer ist ungültig"
        },
        cc_cvc: {
            required: "Bitte geben Sie den Sicherheitscode der Kreditkarte ein",
            minlength: "Der Sicherheitscode der Kreditkarte muss mindestens dreistellig sein",
            maxlength: "Der Sicherheitscode der Kreditkarte darf maximal 4-stellig sein"
        },
    }

});



jQuery(document).ready(function ($) {

    // combine exp date fields and validate new field
    // Define the element we wish to bind to. bezahlung
    var timerInterval = 300,
        timer,
        bind_to = '#bezahlungform input.card-expiry';
    let ccInputs = $(bind_to);


    $("#ccExpiresCombined").val($("#cc-exp-month").val() + '/' + $("#cc-exp-year").val());

    function finishTyping(id, value) {
        var validationValue = value.replace(/ /g, '');
        //cc-exp-year cc-exp-month
        $("#ccExpiresCombined").val($("#cc-exp-month").val() + '/' + $("#cc-exp-year").val());
    }

    // Prevent double-binding.
    ccInputs.keyup(function (e) {
        if (e.keyCode != '9' && e.keyCode != '16') {
            clearTimeout(timer);
            timer = setTimeout(finishTyping, timerInterval, $(this).attr("id"), $(this).val());
        }
    });

    ccInputs.keydown(function () {
        clearTimeout(timer);
    });

    ccInputs.focus(function () {
        $("#ccExpiresCombined").change();
    });

});


var tabindex = 0;
jQuery(document).ready(function ($) {

    function SaveSessionPaypal() {

        // SAVE TO SESSION DATA PREPEAR TO PAYPAL
        // e.preventDefault();
        $("#personaliche-daten-button").buttonSalo('loading');




        var gutschein = $("form#gutschein").serializeObject();
        var persData = $("form#persDataConfirm").serializeObject();
        var gutscheindesign = $("form#gutschein-design").serializeObject();

        let persDataConfirm = Object.assign(gutschein, persData, gutscheindesign);

        console.log(persDataConfirm);
        if (isAnyObject(persDataConfirm)) {


            // add loader 11.11.2021
            $("#loader_pplus").show();

            var url = BloombaseUrl + "/gutscheine/session";
            $.getJSON(url, {
                format: "JSON",
                data: persDataConfirm,
                amount: persDataConfirm.amount
            }).done(function (res) {

                $("#loader_pplus").hide();

                $("#personaliche-daten-button").buttonSalo('reset');


                if (isAnyObject(res) && res.error == false) {

                    $("#ScriptDiv").html(res.paypalPlusJsData);
                    $("#ppplus").show();
                    // $("#paylalOldOption").show();

                    $("#submitPostPaypal").removeAttr('disabled');
                    console.log(JSON.stringify(res));

                } else {
                    $("#submitPostPaypal").prop('disabled', true);
                    console.log('error sess');
                }

            }).fail(function (jqxhr, textStatus, error) {
                $("#personaliche-daten-button").buttonSalo('reset');
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
                $("#loader_pplus").hide();
            });
        } else {
            console.log('error sess 2 ');
            $("#submitPostPaypal").prop('disabled', true);
            $("#loader_pplus").hide();
        }
    }


    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $('#persDataConfirm').FormCache();

    // **** TEST ****
    // 3. Persönliche Daten
    // ActivTabGutscheine('pills-personaliche-daten');
    // 4. Zusammenfassung
    // ActivTabGutscheine('pills-zusammenfassung'); //
    // 5. Bezahlung
    // ActivTabGutscheine('pills-bezahlung');
    //storage = typeof storage === 'string' ? JSON.parse(storage) : null;


    function ActivTabGutscheine(id) {
        // $('.nav-pills li.nav-item > a[href="#' + id + '"]').trigger('click');
        $('.nav-pills li.nav-item > a[href="#' + id + '"]').tab('show');
    }

    // gutscheinauswhal-button

    // S// CLEAR OLD persData DATA
    $("#gutscheinauswhal-button").click(function (e) {

        // $.totalStorage.deleteItem('persDataConfirm');
        let YpersDataConfirm = $.totalStorage('persDataConfirm');
        YpersDataConfirm.email = '';
        $.totalStorage('persDataConfirm', YpersDataConfirm);

        $('#persDataConfirm').FormCache();
        //$.totalStorage('gutschein-versand', sel);
        // $.totalStorage('persDataConfirm', {});
        console.log("CLEAR OLD persData " + JSON.stringify(YpersDataConfirm));

        var versand = $('#versand option:selected').val();

        $('input#diffAddress2').prop('checked', true);
        $('input#diffAddress1').prop('checked', false);
        $('#diffdeliveremail').css('display', 'none');
        $('#diffdeliverpost').css('display', 'none');
        var diffShipment = $("input[name='diffAdress']:checked").val();
        if (versand == 'per E-Mail' && diffShipment == '1') {
            $('#diffdeliveremail').css('display', 'flex');
            $('#diffAddress1').prop('checked', true);

        }
        if (versand == 'per Post' && diffShipment == '1') {
            $('#diffdeliverpost').css('display', 'flex');
            $('#diffAddress1').prop('checked', true);
        }
    });

    // postGutscheine
    // Wizard With Form Validation
    var gutscheineWizard = $('#page-gutscheine').bootstrapWizard({

        // tabClass: 'nav nav-pills',
        nextSelector: '.gutscheine-skache .btn-next',
        previousSelector: '.gutscheine-skache .btn-previous',
        firstSelector: '.gutscheine-skache .button-first',
        lastSelector: '.button-last',

        onTabShow: function (tab, navigation, index) {


            // tab.nextAll().removeClass('done');
            // tab.removeClass('done');

            var $total = navigation.find('li').length;
            var $current = index + 1;
            /* 			 if(tabindex == 1){
            
                             $('#page-gutscheine').bootstrapWizard('disable', 1);
                                $("#pills-gutscheine").hide();
                             setTimeout(function(){
                                 $("button#gutscheindesign-button").trigger("click");
                                      $("#pills-gutscheine").show();
                             }, 200);
            
                         } */

            if ($current == 1) {
                let first_tab_activ = true;
                // $.totalStorage('persDataConfirm')

            }


            if ($current == 2) {
                let second_tab_activ = true;
            }

            if ($current == 3) {
                let persData = $.totalStorage('persDataConfirm');
                console.log('reload persDataConfirm: ' + JSON.stringify(persData));

            }

            if ($current == 4) {
                ValidationUtilsBloom.fillTabZusammenfassung();
            }
            // if ($("select#versand option:selected").val() == 'per Post' && current == 3) {
            // alert('x');
            // ValidationUtilsBloom.fillTabZusammenfassung();
            // }

            // console.log('onTabShow:: ' + $current);

            var $percent = ($current / $total) * 100;
            var progressBar = $('#progressWizard').find('.progress-bar');
            progressBar.css('width', $percent + '%');

        },
        onTabClick: function (tab, navigation, index) {
            if (tab > index) {
                return true;
            } else {
                return false;
            }
        },
        onNext: function (tab, navigation, index) {




            if (index == 1) {
                if ($("select#versand option:selected").val() === 'per Post') {

                    $('#pills-gutscheindesign #gutschein-design .text-md-left').next('p').css('display', 'none');
                    $('#pills-gutscheindesign #gutschein-design .no-padd-form').css('display', 'none');

                } else {

                    $('#pills-gutscheindesign #gutschein-design .text-md-left').next('p').css('display', 'block');
                    $('#pills-gutscheindesign #gutschein-design .no-padd-form').css('display', 'flex');

                }
                let $valid = $('#gutschein').valid();
                if (!$valid) {
                    $valid_gutschein.focusInvalid();
                    return false;
                }
            }

            if (index == 2) {
                let $valid = $('#gutschein-design').valid();
                if (!$valid) {
                    $valid_radio_design.focusInvalid();
                    return false;
                }
                var valtextareaField = $('#gutschein-design #textarea').val();
                valtextareaField = $.trim(valtextareaField);
                // alert(valtextareaField)
                $.totalStorage('gutschein-design-textarea', valtextareaField);


                const amount = parseInt($("form#gutschein #amount").val());
                const versand = $("form#gutschein #versand").val()

                setTimeout(function () {

                    // per E-Mail
                    // per Post
                    if (versand == 'per E-Mail') {

                        if (amount >= 250) {
                            $("form#persDataConfirm #adresse").show();
                            $("form#persDataConfirm #plz").show();
                            $("form#persDataConfirm #ort").show();

                        } else {
                            $("form#persDataConfirm #adresse").hide();
                            $("form#persDataConfirm #plz").hide();
                            $("form#persDataConfirm #ort").hide();


                        }

                    } else {
                        $("form#persDataConfirm #adresse").show();
                        $("form#persDataConfirm #plz").show();
                        $("form#persDataConfirm #ort").show();

                        $("#additional-address-container").show();

                    }

                }, 200);

            }


            if (index == 3) {
                let $valid = $('#persDataConfirm').valid();
                if (!$valid) {
                    $valid_persdataconfirm.focusInvalid();
                    return false;
                } else {
                    // save to session
                    SaveSessionPaypal();

                }
            }



        },
        // add oppo test
        onShow: function (tab, navigation, index) {

            // console.log('onShow');
        },
        onPrevious: function (tab, navigation, index) {

            /* if(index === 0){
                var versand = $('#versand').val();

                $('input#diffAddress2').prop('checked', false);
                $('input#diffAddress1').prop('checked', false);
                $('#diffdeliveremail').css('display','none');
                $('#diffdeliverpost').css('display','none');
                var diffShipment = $( "input[name='diffAdress']:checked" ).val();
                if(versand == 'per E-Mail'  && diffShipment == '1'){
                    $('#diffdeliveremail').css('display','flex');
                    $('#diffAddress1').prop('checked', true);

                }
                if(versand == 'per Post'  && diffShipment == '1'){
                    $('#diffdeliverpost').css('display','flex');
                    $('#diffAddress1').prop('checked', true);
                }

            } */
        },
        onLast: function (tab, navigation, index) {
            // console.log('onLast');
        }
    });
});
var $validator = $('#commentForm').validate({
    rules: {
        emailfield: {
            required: true,
            email: true,
            minlength: 3,
            normalizer: function (value) {
                return $.trim(value);
            }
        },
        namefield: {
            required: true,
            minlength: 3,
            normalizer: function (value) {
                return $.trim(value);
            }
        },
        urlfield: {
            required: true,
            minlength: 3,
            url: true
        }
    }
});