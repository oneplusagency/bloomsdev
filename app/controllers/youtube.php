<?php

add_filter('tc_slide_background' , 'set_video_slider', 10, 4);
function set_video_slider($_original_bg , $link, $id, $s_name ) {
  //////////////// Parameters :  ////////////////
  $my_slider_name = 'slider';//set the name you have choosen when creating your slider
  $slide_position = 1; //<= this will replace the first slide
  $_video         = 'https://www.youtube.com/watch?v=oUlLeDKPCYA';//<=the url of your vimeo or youtube video
  $_autoplay      = true;//<= true = autoplay
  $_mute_volume   = true;// true = volume set to 0
  $_unmute_on_hover = true;//true = the video will unmute on mouse hover
  $_pause_on_slide = true;//true = the video is paused on slide change
  $_related_videos  = false;// true = display related videos . Works just with youtube videos, vimeo doesn't allow this for non premium users.
  $_yt_loop       = true;
  //////////////////////////////////////////////
  //uncomment this video array to run the random video
  //$_video         = array( 'https://www.youtube.com/watch?v=oUlLeDKPCYA' , 'http://vimeo.com/108792063', 'http://vimeo.com/73373514', 'http://vimeo.com/39312923', 'http://vimeo.com/108104171', 'http://vimeo.com/106535324' , 'http://vimeo.com/108138933', 'http://vimeo.com/107789364', 'http://vimeo.com/107580451' );

  $sliders        = tc__f('__get_option' , 'tc_sliders');

  //remove previous filter if set
  remove_filter('embed_oembed_html', '_add_an_id_for_youtube_player' , 10, 4);
  remove_filter('oembed_result' , 'enable_youtube_jsapi');
  remove_filter('oembed_result' , 'set_youtube_autoplay');
  remove_filter('oembed_fetch_url' , 'set_vimeo_url_query_args');

  if ( $my_slider_name != $s_name )
    return $_original_bg;

  $_slides = array();
  foreach ($sliders[$s_name] as $_sid) {
    $_slides[] = $_sid;
  }
  if ( $id != $_slides[$slide_position-1] )
    return $_original_bg;

  if ( ! is_array($_video) ) {
    $_return_video = $_video;
  } else {
    $rand_key       = array_rand($_video, 1);
    $_return_video = $_video[$rand_key];
  }

  //youtube or vimeo?
  $_provider = ( false !== strpos($_return_video, 'youtube') ) ? 'youtube' : false;
  $_provider = ( false !== strpos($_return_video, 'vimeo') ) ? 'vimeo' : $_provider;

  if ( ! $_provider )
    return $_original_bg;

  if ( 'youtube' == $_provider ) {
    add_filter('embed_oembed_html', '_add_an_id_for_youtube_player' , 10, 4);
    //Adding parameters to WordPress oEmbed https://foxland.fi/adding-parameters-to-wordpress-oembed/
    add_filter('oembed_result' , 'enable_youtube_jsapi');
    if ( false !== $_autoplay )
      add_filter('oembed_result' , 'set_youtube_autoplay');
    if ( false !== $_yt_loop )
      add_filter('oembed_result' , 'set_youtube_loop');
    if ( false === $_related_videos )
      add_filter('oembed_result' , 'set_youtube_no_related_videos');
  } elseif ( 'vimeo' == $_provider && false !== $_autoplay ) {
    add_filter('oembed_fetch_url' , 'set_vimeo_url_query_args');
  }

  //write some javascript : dynamic centering on resizing, responsiveness, Vimeo and YouTube API controls
  _write_video_slide_script($id, $_mute_volume, $_unmute_on_hover, $_pause_on_slide, $_provider);
  $_return_video =  add_query_arg( 'cached' , time(),  $_return_video );

  //( false !== strpos($_return_video, '?') ) ? '&cached=' : '?cached=';
  $_return_video = apply_filters('the_content', $_return_video );

  /* For someone autombed is not hooked to the content */
  if ( false === strpos($_return_video, '<iframe') ){
    global $wp_embed;
    $_return_video = $wp_embed -> autoembed( $_return_video );
  }

  return $_return_video;
}


function set_vimeo_url_query_args($provider) {
  $provider = add_query_arg( 'autoplay', 1 , $provider );
  $provider = add_query_arg( 'loop', 1 , $provider );
  return $provider;
}
function set_youtube_autoplay($html) {
  if ( strstr( $html,'youtube.com/embed/' ) )
     return str_replace( '?feature=oembed', '?feature=oembed&autoplay=1', $html );
  return $html;
}

function set_youtube_loop($html) {
  if ( strstr( $html,'youtube.com/embed/' ) )
     return preg_replace( '|(youtube.com/embed/)(.*?)(\?feature=oembed)|', '$0&loop=1&playlist=$2', $html );
  return $html;
}

function set_youtube_no_related_videos($html) {
  if ( strstr( $html,'youtube.com/embed/' ) )
     return str_replace( '?feature=oembed', '?feature=oembed&rel=0', $html );
  return $html;
}

function _add_an_id_for_youtube_player($html, $url, $attr, $post_ID ) {
  //@to do make the id unique
  return str_replace('<iframe', '<iframe id="youtube-video"', $html);
}

function enable_youtube_jsapi($html) {
  if ( strstr( $html,'youtube.com/embed/' ) )
    return str_replace( '?feature=oembed', '?feature=oembed&enablejsapi=1', $html );
  return $html;
}


function _write_video_slide_script($id, $_mute_volume, $_unmute_on_hover, $_pause_on_slide, $_provider) {
  ?>
    <script type="text/javascript">
      jQuery(function ($) {
        var $_slide_wrap    = $('.slide-'+<?php echo $id ?>),
            $vid_iframe     = $('iframe' , $_slide_wrap ),
            vid_height      = $vid_iframe.attr('height'),
            vid_width       = $vid_iframe.attr('width'),
            wind_width      = $(window).width(),
            is_active       = false,
            $slider_wrapper = $_slide_wrap.closest('div[id*=customizr-slider]'),
            $_pause_on_slide = <?php echo (true != $_pause_on_slide) ? 'false' : 'true'; ?>,
            $_mute_volume   = <?php echo (true != $_mute_volume) ? 'false' : 'true'; ?>,
            $_unmute_on_hover = <?php echo (true != $_unmute_on_hover) ? 'false' : 'true'; ?>,
            $_provider       = '<?php echo $_provider; ?>';

        //$('.carousel-caption' , $_slide_wrap ).remove();
        $('.carousel-image', $_slide_wrap ).css('text-align' , 'center');

        //Beautify the video
        $('iframe' , $_slide_wrap )
          .attr('width' , '').attr('height' , '')
          .css('width', '100%').css('max-width', '100%').css('position','relative')
        _re_center();
        $(window).resize( function() {
          setTimeout(function() { _re_center();}, 200)
        });

        function _re_center() {
          var new_height    = (wind_width * vid_width) / vid_height,
              _height       = $slider_wrapper.height(),
              push_up       = (new_height - _height )/2;
          $('iframe' , $_slide_wrap ).css('height' , new_height ).css('bottom' , push_up );
        }

        //VIMEO PLAYER API
        //http://developer.vimeo.com/player/js-api
        // Listen for messages from the player
        if (window.addEventListener){
            window.addEventListener('message', onMessageReceived, false);
        }
        else {
            window.attachEvent('onmessage', onMessageReceived, false);
        }

        // Helper function for sending a message to the player
        function post(action, value, player) {
            if ( ! player || 0 == player.length )
              return;
            var data = {
              method: action
            };

            if (value)
              data.value = value;

            var message   = JSON.stringify(data);
                url       = player.attr('src').split('?'),
            player[0].contentWindow.postMessage(message, url);
        }


        //Mute volume when player is ready
        function onMessageReceived(e) {
            var data          = JSON.parse(e.data),
                $activeSlide  = $slider_wrapper.find('.czr-item.active'),
                $player       = $('iframe[src*=vimeo]', $activeSlide );
            switch (data.event) {
                case 'ready':
                    ( true == $_mute_volume) && post('setVolume', +0.00001 , $player );
                break;
            }
        }
        //EVENTS :
        //Unmute on hover
        $_mute_volume && $_unmute_on_hover && $slider_wrapper.hover( function() {
          post('setVolume', +0.8 , $('iframe[src*=vimeo]', $(this).find('.czr-item.active') ) );
        }, function() {
          post('setVolume', +0.000001 , $('iframe[src*=vimeo]', $(this).find('.czr-item.active') ) );
        });

        // Call the API on 'slid'
        $slider_wrapper.on('slid', function() {
          ( true == $_pause_on_slide) && _pause_inactive_slides();
        });

        function _pause_inactive_slides() {
          var $activeSlide  = $slider_wrapper.find('.czr-item.active'),
              $player       = $('iframe[src*=vimeo]', $activeSlide );

          //if current slide has no player. Pause all other players, else play this player
          if ( ! $player.length ) {
            $slider_wrapper.find('iframe[src*=vimeo]').each( function() {
              post( 'pause', false , $(this) );
            });
          }
          post( 'play', null, $player );
        }

        //YOUTUBE PLAYER API
        //https://developers.google.com/youtube/iframe_api_reference
        //https://developers.google.com/youtube/player_parameters
        //http://stackoverflow.com/questions/8869372/how-do-i-automatically-play-a-youtube-video-iframe-api-muted
        //http://css-tricks.com/play-button-youtube-and-vimeo-api/

        if ( 'youtube' == $_provider ) {
          var player;
          //important : the function onYouTubeIframeAPIReady has to be added to the window object
          //when wrapped in another closure
          window.onYouTubeIframeAPIReady = function () {
              player = new YT.Player('youtube-video', {
                events: {
                  'onReady': onPlayerReady
                  //'onStateChange': onPlayerStateChange
                }
              });
          }

          //Play and Mute volume when player is ready
          function onPlayerReady() {
            //player.playVideo();
            _bind_youtube_events();

            if ( true == $_mute_volume)
              player.mute();
            else
              player.unMute();
          }

          function _bind_youtube_events() {
            //Unmute on hover
            if ( $_mute_volume && $_unmute_on_hover ){
              $slider_wrapper.hover( function() {
              if ( 0 != $('iframe[src*=youtube]', $(this).find('.czr-item.active') ).length )
                player.unMute();
              }, function() {
                if ( 0 != $('iframe[src*=youtube]', $(this).find('.czr-item.active') ).length )
                  player.mute();
              });
            }

            // Call the API on 'slid'
            $slider_wrapper.on('slid', function() {
              if ( true !== $_pause_on_slide)
                return;
              var $activeSlide  = $slider_wrapper.find('.czr-item.active'),
                  $player       = $('iframe[src*=youtube]', $activeSlide );

              //if current slide has no player. Pause all other players, else play this player if it was in pause
              if ( ! $player.length ) {
                $slider_wrapper.find('iframe[src*=youtube]').each( function() {
                  player.pauseVideo();
                });
              } else if ( 2 == player.getPlayerState() ){
                player.playVideo();
              }

            });//end on slid
          };//end _bind_youtube_events

          //load the youtube API script asynchronously
          var tag = document.createElement('script');
          tag.src = "http://www.youtube.com/player_api";
          var firstScriptTag = document.getElementsByTagName('script')[0];
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }

      });
    </script>
  <?php
}