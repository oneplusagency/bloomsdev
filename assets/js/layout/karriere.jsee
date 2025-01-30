(function($) {
    $('#karriere-contact-data').show();
    $('#karriere-info-data').hide();
    $('#karriere-team-data').hide();
})(jQuery);

function showData(selection, div) {
    let buttons = jQuery('#selection-tabs').find('button');
    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }
    jQuery('#karriere-contact-data').fadeOut('slow');
    jQuery('#karriere-info-data').fadeOut('slow');
    jQuery('#karriere-team-data').fadeOut('slow');
    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn('slow');
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