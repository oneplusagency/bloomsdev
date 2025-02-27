(function($) {


    $(".div_clickable").click(function() {
        var url = $(this).find("a.clickable").prop("href");
        window.location = url;
        return false;
    });

    // $('#bewerbung-contact-data').hide();
    $('#selection-tabs button:eq(0)').addClass('btn-active');
    $('#bewerbung-info-data').hide();
    $('#bewerbung-team-data').hide();
    $('#werte-view').hide();
})(jQuery);

function showData(selection, div) {
    let buttons = jQuery('#selection-tabs').find('button');
    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }
    jQuery('#bewerbung-contact-data').fadeOut('slow');
    jQuery('#bewerbung-info-data').fadeOut('slow');
    jQuery('#bewerbung-team-data').fadeOut('slow');
    jQuery('#werte-view').fadeOut('slow');
    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn('slow');

    // 12.01.2021
    // when clicking on tabs, the content should always be centered
    var tdiv = jQuery(div);
    if (tdiv.length) {
        var hh = tdiv.height(),
            chh = -250;
        // alert(hh)
        if (hh < 300) { chh = 0 }

        if (hh > 400) {
            chh = hh / 2;
            chh = -chh
        }
        jQuery('html, body').delay(200).animate({
           // scrollTop: hh + chh
           scrollTop: parseInt(jQuery(selection).offset().top)
        }, '600');
    }

}

// Kontakt form
(function($) {
    $('#bloom_kontakt_form').submit(function(event) {
        event.preventDefault();
        // var dats = JSON.stringify($(this).serializeArray());
        var value = $(this).serializeArray(),
            request = {
                'option': 'bloom_kontakt',
                'data': value,
                // 'format': 'jsonp'
            };
        $.ajax({
            url: BloombaseUrl + '/json/kontakt',
            type: 'POST',
            data: request,
            beforeSend: function() {
                $('#bloom_kontakt_submit').buttonSalo('loading');
            },
            success: function(response) {
                // alert(response)
                $('#bloom_kontakt_status').hide().html(response).fadeIn().delay(2000).fadeOut(800);
                $('#bloom_kontakt_submit').buttonSalo('reset');

                $('#bloom_kontakt_submit').removeAttr('disabled');
                $('#bloom_kontakt_message').val('');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                $('#bloom_kontakt_submit').buttonSalo('reset');
            }
        });
        return false;
    });
})(jQuery);