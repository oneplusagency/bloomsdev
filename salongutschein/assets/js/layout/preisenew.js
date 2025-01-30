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

jQuery(document).ready(function ($) {



});



function isAnyObject(value) {
    return value != null && (typeof value === 'object' || typeof value === 'function');
}

(function ($) {

    $('.bloomstooltip').tooltip({
        // title: Servise_Description,
        animated: 'fade',
        // trigger: 'click',
        // container: "body",
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


})(jQuery);