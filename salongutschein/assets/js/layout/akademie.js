(function ($) {
    // $('#seminare-data').hide();

    //$('#clip-data').hide();
    $('#seminare-data').hide();
    $('#bilder-data').hide();
    $('#page-preise').hide();


})(jQuery);



function showData(selection, div) {
    let buttons = jQuery('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    //jQuery('#clip-data').fadeOut("slow");
    jQuery('#termine-section').fadeOut("slow");
    jQuery('#bilder-data').fadeOut("slow");
    jQuery('#seminare-data').fadeOut("slow");
    jQuery('#page-preise').fadeOut("slow");

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

    jQuery("#option_salon option[value='31']").prop('selected', true);
}


jQuery('#slider-images').carousel({
    interval: 50
})