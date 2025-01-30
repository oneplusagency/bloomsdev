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
    }
});


// Helper function for sending a message to the player
function post(action, value, player) {
    if (!player || 0 == player.length)
        return;
    var data = {
        method: action
    };

    if (value)
        data.value = value;

    var message = JSON.stringify(data);
    url = window.location.protocol + player.attr('src').split('?'),
        player[0].contentWindow.postMessage(message, url);
}

var $_provider = 'youtube',
    $_mute_volume = true,
    $_pause_on_slide = true;

if ('youtube' == $_provider) {
    var player;

    var $slider_wrapper = $('#slider-images');
    //important : the function onYouTubeIframeAPIReady has to be added to the window object
    //when wrapped in another closure
    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('youtube-video', {
            events: {
                'onReady': onPlayerReady
                    //'onStateChange': onPlayerStateChange
            }
        });
    }

    //Play and Mute volume when player is ready
    function onPlayerReady() {
        player.playVideo();
        // player.seekTo(0, true);
        if (true == $_mute_volume)
            player.mute();
        else
            player.unMute();
    }

    // Call the API on 'slid'
    $slider_wrapper.on('slid', function() {
        if (true !== $_pause_on_slide)
            return;
        var $activeSlide = $slider_wrapper.find('.carousel-item.active'),
            $player = $('iframe[src*=youtube]', $activeSlide);

        //if current slide has no player. Pause all other players, else play this player
        if (!$player.length) {
            $slider_wrapper.find('iframe[src*=youtube]').each(function() {
                player.pauseVideo();
            });
        } else {
            player.playVideo();
        }

    }); //end on slid

    //load the youtube API script asynchronously
    var tag = document.createElement('script');
    tag.src = "http://www.youtube.com/player_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}