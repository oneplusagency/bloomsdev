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

        var youtubeStartInterval = setInterval(function(){
            if($('#slider-images .carousel-item.active').hasClass('carousel-item--youtube')){
                if($('#slider-images .carousel-item.carousel-item--youtube').find('iframe').length > 0){

                }
                else{
                    $('#slider-images .carousel-item.carousel-item--youtube > div').html(iframe);
                }
            }
            else{
                $('#slider-images .carousel-item.carousel-item--youtube > div').html('');
            }
        }, 50)
    }
});