jQuery(document).bind('DOMNodeInserted', function(e) {

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

jQuery(document).ready(function($) {
    
    
    
    
    if ($('#slider-images').length > 0) {

        // var car_container = $('#slider-images');

        if (typeof CarouselInterval === 'undefined') {
            var carousel_interval = false;
        } else {
            var carousel_interval = parseFloat(CarouselInterval);
            if (carousel_interval < 1) {
                carousel_interval = false;
            }
        }

        var timebz = $("#slider-images .carousel-item").first().data("interval");

        timebz = parseFloat(timebz);
        if (timebz) {
            $('#slider-images').carousel({
                interval: timebz,
                wrap: true
            });
        } else {
            $('#slider-images').carousel({
                interval: carousel_interval,
                wrap: true
            });

        }
        // $(".carousel .carousel-item").first().addClass("active");

        $('#slider-images .carousel-item').hasClass('active', function() {
            var SlideInterval = $(this).attr('data-interval');
            if (!SlideInterval) {
                SlideInterval = carousel_interval;
            }
            $('#slider-images').carousel({ interval: SlideInterval, wrap: true });
        });

        var iframe = $('#slider-images .carousel-item.carousel-item--youtube iframe').clone();

        var youtubeStartInterval = setInterval(function() {
            if ($('#slider-images .carousel-item.active').hasClass('carousel-item--youtube')) {
                if ($('#slider-images .carousel-item.carousel-item--youtube').find('iframe').length > 0) {

                } else {
                    $('#slider-images .carousel-item.carousel-item--youtube > div').html(iframe);
                }
            } else {
                $('#slider-images .carousel-item.carousel-item--youtube > div').html('');
            }
        }, 50)
    }
});

/**
 * @Date: 2020-03-11 15:46:28
 * @Desc:  As soon as a salon has been selected, then two other fields should be shown (before that they should be hidden)
 */

$('#page-preise #option_salon').on('change', function() {
    $('#page-preise .cancelationDaysRow').toggle($(this).val() > 0);
}).trigger('change');



function isAnyObject(value) {
    return value != null && (typeof value === 'object' || typeof value === 'function');
}

(function($) {
    // https://learn.javascript.ru/let-const
    // const apple = 5;
    //{{@BASE}}/termine.html
    // id = termin-link
    let url = BloombaseUrl + '/termine.html';
    let option_salon = $('#option_salon');

    $(document).ready(function() {
        option_salon.change();
    });

    let selsalon = option_salon.children('option:selected').val();
    if (selsalon) {
        $('#termin-link').prop('href', BloombaseUrl + '/termine/salon/' + selsalon);
    }
    $("#service_category").next('.select2-container').css('display', 'none');
    option_salon.on('change', function() {
        // asp = $(this).val();
        console
        var $$ = $(this);
        let selsalon = $(this)
            .children('option:selected')
            .val();
        if (selsalon) {
            // set link
            $('#termin-link').prop('href', BloombaseUrl + '/termine/salon/' + selsalon);

            // UPDATE PRICE
            //  JSON.stringify(d

            var pp = 'salonId=' + selsalon + '&pipa=' + true
            $("#service_category").next('.select2-container').css('display', 'block');
            $.ajax({
                url: BloombaseUrl + '/preise/getPriceAjax',
                type: 'get',
                cache: false,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType+
                data: pp,
                dataType: "json",
                success: function(res) {
                    $$.removeAttr('disabled');
                    // console.log(JSON.stringify(res));
                    if (isAnyObject(res) && res.error == false) {
                        $('#price-table').html(res.html);
                        $('#salon-info').fadeIn('400');
                    }
                },
                error: function(jqxhr, textStatus, error) {
                    // add whatever debug you want here.
                    var err = textStatus + ', ' + error;
                    console.log('getPriceAjax Failed: ' + err);
                },
                beforeSend: function() {
                    $('#price-table').html('');
                    $$.prop('disabled', true);
                    $('#salon-info').before('<div class="mt-5" id="alsamulainloader">Laden...<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');
                    $('#salon-info').fadeOut('100');
                },
                complete: function() {
                    var bloomstooltip;
                    $('#alsamulainloader').remove();
                    $$.removeAttr('disabled');
                    $('#salon-info').fadeIn('400');

                    // $('.bloomstooltip').on({
                    //     "click": function() {

                    //         bloomstooltip = $(this).attr('id');

                    //         $('.bloomstip.' + bloomstooltip).css('display', 'block');
                    //         setTimeout(function() {
                    //             $('.bloomstip.' + bloomstooltip).css('display', 'none');
                    //         }, 4000);

                    //     },
                    //     "mouseout": function() {
                    //         //$('.bloomstip.'+bloomstooltip).css('display','none');
                    //     }
                    // });

                }
            });
        }
    });

    // $('.bloomstooltip').tooltip();

    $('.bloomstooltip').tooltip({
        // title: Servise_Description,
        animated: 'fade',
        // trigger: 'click',
        // container: "body",
        placement: "top",
        html: true,
        delay: { show: 240, hide: 60 }
    });
    
    $('html').on('click', function(e) {
        if (typeof $(e.target).data('original-title') == 'undefined' &&
           !$(e.target).parents().is('.popover.in')) {
          $('[data-original-title]').tooltip('hide');
        }
    });


    option_salon.chainSelect('#service_category', BloombaseUrl + '/preise/servicesShoAjax', {
        default: null,
        before: function(
            target //before request hide the target combobox and display the loading message
        ) {
            // var $$ = $(this);

            $(target)
                .append('<option value="">Laden...</option>')
                .ajaxStart(function() {
                    // $$.show();
                });
            $(target).attr('disabled', 'true');

        },
        after: function(
            target //after request show the target combobox and hide the loading message
        ) {
            $(target).removeAttr('disabled');

        }
    });

    $("#service_category").on('change', function() {
        // asp = $(this).val();

        let sel_frau = $(this)
            .children('option:selected')
            .val();
        if (sel_frau > 0) {
            //-tbprice
            // $('#price-table > .table-price').hide();
            $('#price-table > .table-price').fadeOut(500);
            // $('#' + sel_frau + '-tbprice').fadeOut(700).delay(1000).fadeIn();
            $('#' + sel_frau + '-tbprice').fadeIn(700);

        } else {
            $('#price-table > .table-price').fadeIn(500);
        }
    });

})(jQuery);