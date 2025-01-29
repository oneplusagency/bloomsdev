<?php
/**
 * @file: karriere.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-blooms\app\controllers
 * @created:    Mon Jul 27 2020
 * @author:     oppo, webiprog.de
 * @version:    1.0.0  karriere-banner
 * @modified:   Tuesday January 21st 2020 6:01:48 pm
 * @copyright   (c) 2008-2020 Webiprog GmbH, UA. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */



class karriere extends Controller
{

    /**
     * @return mixed
     */
    private function banners()
    {

        $banners = [];

        $bannerAdmin = new bannerKarriereAdmin();
        $images      = (array) $bannerAdmin->loadBysort();

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi'    => 1,
            'iv_load_policy' => 3,
            'disablekb'      => 1,
            'autoplay'       => 1,
            'modestbranding' => 1,
            // Показывается меню плеера перед началом проигрования. Не нужно показывать какие-либо символы плеера. 25.05.2020 16:19
            'controls'       => 0,
            'showinfo'       => 0,
            'rel'            => 0,
            'loop'           => 0,
            'mute'           => 0,
            'wmode'          => 'transparent',
            'color'          => 'white',
            'theme'          => 'dark'
        );

        $yt = 0;

        /* @FIX by oppo , @Date: 2020-03-16 16:46:03
         * @Desc:  carousel_interval
         */
        // $interval = [25000,500,8500,500,3000,1500,2000,500,2000,500,2000,500];
        $def_carousel_interval = (int) $this->f3->get( 'carousel_interval' );

        foreach ( $images as $n => $img ) {

            if ( empty( $img['carousel_interval'] ) ) {
                $img['carousel_interval'] = $def_carousel_interval;
            }

            // false &&
            if ( !empty( $img['src'] ) ) {
                $banners[] = ['type' => 'img', 'src' => BANNER_PARENT_URL_DIR.'/karriere-banner/'.$img['src'], 'interval' => $img['carousel_interval']];
                $yt++;
            } elseif ( !empty( $img['video_url'] ) ) {

                if ( $yt > 0 ) {
                    // $query['autoplay'] = 0;
                }
                /* @FIX by oppo (webiprog.de), @Date: 2020-03-18 11:34:55
                 * @Desc: LOOP
                 * https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/#param13
                 */
                // 'playlist'        => $video_id,
                $video_id = str_replace( 'https://www.youtube.com/embed/', '', $img['video_url'] );
                if ( $video_id ) {
                    $query['playlist'] = $video_id;
                }

                $youtube_addon_url = '?'.http_build_query( $query, '&' );

                $youtube_url = rtrim( $img['video_url'], '?' );
                $youtube_url = $youtube_url.$youtube_addon_url;
                $banners[]   = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
    }
	public function index()
	{

		$this->f3->set('isHomePage',false);
		$this->f3->set('title', "Karriere");
		$this->f3->set('view', 'karriere.html');
		$this->f3->set('classfoot', 'karriere');
        // ADD JS
        $addscripts = 'js/layout/karriere.js';
		$this->f3->set('addscripts', array($addscripts));

        /* @FIX by oppo @Date: 27.07.2020 17:58
         * @Desc: add slider
         */
        $banners = $this->banners();
        $this->f3->set( 'BANNERS', $banners );

        $this->f3->set( 'ESCAPE', false );

	}
}
