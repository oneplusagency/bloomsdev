(function ($) {

    $('#werte-view').hide();
    $('#facts-view').hide();
    $('#historie-view').hide();
    $('#verwaltung-view').hide();
})(jQuery);

function showData(selection, div) {
    let buttons = $('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    jQuery('#leitung-view').fadeOut("slow");
    jQuery('#werte-view').fadeOut("slow");
    jQuery('#facts-view').fadeOut("slow");
    jQuery('#historie-view').fadeOut("slow");
    jQuery('#verwaltung-view').fadeOut("slow");

    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn("slow");


    // 12.01.2021
    // when clicking on tabs, the content should always be centered
    var tdiv = jQuery(div);
    if (tdiv.length) {
        var hh = tdiv.height(),
            chh = -250;
        // alert(hh)
        if (hh < 300) { chh = 0 }

        if (hh > 400) {
            chh = hh / 4;
            chh = -chh
        }
        jQuery('html, body').delay(200).animate({
            //scrollTop: hh + chh
            scrollTop: parseInt(jQuery(selection).offset().top)
        }, '600');
    }

}


function showDataLong(selection, div) {
    let buttons = $('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    jQuery('#leitung-view').fadeOut("slow");
    jQuery('#werte-view').fadeOut("slow");
    jQuery('#facts-view').fadeOut("slow");
    jQuery('#historie-view').fadeOut("slow");
    jQuery('#verwaltung-view').fadeOut("slow");

    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn("slow");


    // 12.01.2021
    // when clicking on tabs, the content should always be centered
    var tdiv = jQuery(div);
    if (tdiv.length) {
        var hh = tdiv.height(),
            chh = -250;
        // alert(hh)
        if (hh < 300) { chh = 0 }

        if (hh > 400) {
            chh = hh / 1;
            chh = -chh
        }
        jQuery('html, body').delay(200).animate({
            scrollTop: hh + chh + 600
        }, '600');
    }

}

function showcarouselformAdmin(who) {

    $(who).hide();


    $('form#TerminstornierungAdmin').fadeIn("slow");
}


function savePagePdf() {

    var elementHTML = document.querySelector("body");
    var opt = {
        // margin: 0,
        // filename: 'blooms-storno.pdf',
        // image: { type: 'jpeg', quality: 1 },
        // html2canvas: { scale: 1, backgroundColor: '#c60001' },
        // jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait', precision: '12' }
        margin: 0,

        filename: 'blooms-storno.pdf',
        pagebreak: { mode: ['css', 'legacy'] },
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2, backgroundColor: '#000000' },
        jsPDF: { unit: 'px', format: [687, 700], orientation: 'p', precision: 'smart', hotfixes: ["px_scaling"] }

    };

    html2pdf().set(opt).from(elementHTML).save();
}

const dayDelta = 0;
jQuery(document).ready(function ($) {
    $('[data-toggle="tooltip"]').tooltip();

    let sevenday = new Date().getDay + dayDelta;
    let valueDate = $.totalStorage('datepicker');
    let defaultDate = new Date();

    if (!valueDate) {
        let d = Date.parse(sevenday);
        defaultDate = new Date(d);
    } else {

        let d = Date.parse(valueDate);
        defaultDate = new Date(d);
    }


    $.datepicker.setDefaults($.datepicker.regional['de']);



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

        }
    }).datepicker("setDate", defaultDate);

    qw.datepicker('setDate', new Date().getDay + dayDelta);

    $(".btn-hotel").click(function () {
        var $this = $(this);
        $this.hide();
        $this.next('.btn-hotel-after').show();
    });


    $('#TerminstornierungAdmin').submit(function (event) {
        event.preventDefault();
        // var dats = JSON.stringify($(this).serializeArray());
        $('#terminstornierung_feedback_status').html('Laden...');
        let data = new FormData(this);
        $.ajax({
            url: BloombaseUrl + '/abmeldung/sendinfo',
            type: 'post',
            data: data,
            dataType: 'text',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#terminstornierung_sendbtn').buttonSalo('loading');
            },
            success: function (response) {
                //console.log("Information "+response)
                // alert(response)

                $("#terminstornierung_form_container").css("display", "none");
                $('#terminstornierung_success_container').css("display", 'block');

                $("#terminstornierung_success_message").html(response);

                // $('#terminstornierung_sendbtn').removeAttr('disabled');
                // if (response != '<div class="alert alert-danger rounded-0" role="alert">Falsches Dateiformat</div>') {
                //     $('#terminstornierung_sendbtn').buttonSalo('reset');
                //     $("#TerminstornierungAdmin")[0].reset();
                // }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
            }
        });
        return false;
    });


});