<?php

class home extends Controller
{
    public function index()
    {
        $this->f3->set('isHomePage', true);
        $this->f3->set('title', "Willkommen");
        $this->f3->set('view', 'home.html');
        $this->f3->set('classfoot', 'home');

        // home.js
        $addscripts[] = 'js/layout/home.js';
        $this->f3->set('addscripts', $addscripts);

        // READ FILES FROM THE GALLERY FOLDER
        // '/assets/images/banner/'
        $banners = [];
        // $dir = BANNER_ABS_DIR;
        // $images = glob($dir . "*.{jpg,jpeg,gif,png}", GLOB_BRACE);
        // foreach ($images as $n => $i) {
        //     $banners[$n] = BANNER_DIR . basename($i);
        // }
        $bannerAdmin = new bannerAdmin();
        $images = $bannerAdmin->loadBysort();

        // '5e5fe6744f55b0.86080316' =>
        // array (
        //   'id' => '5e5fe6744f55b0.86080316',
        //   'where' => 'banner',
        //   'sort' => 1,
        //   'title' => 'youtube',
        //   'desc' => '',
        //   'text' => 'Vollständiges Video Anschauen',
        //   'src' => '',
        //   'url' => '',
        //   'video_url' => 'https://www.youtube.com/embed/YE7VzlLtp-4',
        //   'video_aspect' => 'embed-responsive-4by3',
        //   'url_openblank' => false,
        //   'link_text' => '',
        //   'date' => 1583343220,
        // )
        // https://wordpress.stackexchange.com/questions/284882/how-to-add-attribute-to-output-with-wp-video-shortcode-add-filter
        // https://stackoverflow.com/questions/18241569/bootstrap-carousel-pause-when-youtube-video-played
        // https://stackoverflow.com/questions/40685142/youtube-autoplay-not-working
        // https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/   !!! color=white;theme=light

        // <iframe width="728" height="410" src="http://www.youtube.com/embed/PJ_fFrdSKlg?showinfo=0&amp;iv_load_policy=3&amp;modestbranding=1&amp;nologo=1&amp;autoplay=0" frameborder="0" allowfullscreen="1">

        $query = array(
            // 'playlist'		=> $video_id,
            'enablejsapi'     => 1,
            'iv_load_policy'    => 3,
            'disablekb'        => 1,
            'autoplay'        => 1,
            // 'iv_load_policy'        => 3,
            // 'nologo'        => 1,
            'modestbranding'        => 1,
            // Показывается меню плеера перед началом проигрования. Не нужно показывать какие-либо символы плеера. 25.05.2020 16:19
            'controls'		=> 0,
            'showinfo'        => 0,
            'rel'            => 0,
            'fs'            => 0,
            'loop'            => 0,
            'mute'            => 1,
            'wmode'            => 'transparent',
            'color'            => 'white',
            'theme'            => 'dark',
        );

        $yt = 0;

        /** @FIX by oppo , @Date: 2020-03-16 16:46:03
         * @Desc:  carousel_interval
         */
        // $interval = [25000,500,8500,500,3000,1500,2000,500,2000,500,2000,500];
        $def_carousel_interval =  (int) $this->f3->get('carousel_interval');

        foreach ($images as $n => $img) {

            if (empty($img['carousel_interval'])) {
                $img['carousel_interval'] = $def_carousel_interval;
            }
            // false &&
            if (!empty($img['src'])) {
                $banners[] = ['type' => 'img', 'src' => BANNER_DIR . $img['src'], 'interval' => $img['carousel_interval']];
                $yt++;
            } elseif (!empty($img['video_url'])) {

                if ($yt > 0) {
                    // $query['autoplay'] = 0;
                }
                /** @FIX by oppo (webiprog.de), @Date: 2020-03-18 11:34:55
                 * @Desc: LOOP
                 * https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/#param13
                 */
                // 'playlist'		=> $video_id,
                $video_id = str_replace('https://www.youtube.com/embed/', '', $img['video_url']);
                if ($video_id) {
                    $query['playlist'] = $video_id;
                }

                $youtube_addon_url  = '?' . http_build_query($query, '&');

                $youtube_url = rtrim($img['video_url'], '?');
                $youtube_url = $youtube_url . $youtube_addon_url;
                $banners[] = ['type' => 'youtube', 'src' => $youtube_url, 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }


        $this->f3->set('BANNERS', $banners);
    }

    private function iframe_catcher($in) {
        if (preg_match_all('#<iframe src="https:\/\/www\.youtube\.com\/embed\/(.*)"(?:.*)><\/iframe>#Usm', $in, $matches, PREG_SET_ORDER)) {
          foreach ($matches as $match) {
            $in = str_replace($match[0], 'httpv://youtu.be/'.$match[1], $in);
          }
        }
        return $in;
      }

    private function renderElement($data = array(), $content = '')
    {
        $settings = !empty($data['settings']) ? $data['settings'] : array();
        if (!empty($settings['background_video'])) {
            $url = $settings['background_video'];
            // $url = 'https://youtu.be/2WRz96r9axM';
            // $url = 'https://www.youtube.com/watch?v=2WRz96r9axM';
            // validate youtube url
            preg_match('/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i', $url, $match);
            $video_id = !empty($match[2]) ? $match[2] : false;
            $settings['background_video'] = false;
            if ($video_id) {
                $query = array(
                    'playlist'        => $video_id,
                    'enablejsapi'     => 1,
                    'iv_load_policy'    => 3,
                    'disablekb'        => 1,
                    'autoplay'        => 1,
                    'controls'        => 0,
                    'showinfo'        => 0,
                    'rel'            => 0,
                    'loop'            => 1,
                    'mute'            => 1,
                    'wmode'            => 'transparent'
                );
                $settings['background_video'] = 'https://youtube.com/embed/' . $video_id . '?' . http_build_query($query);
            }
        }
    }
}
