<?php

class preisenew extends Controller
{
    //preise.php

    /**
     * @var mixed
     */
    protected static $services = null;


    /**
     * @var mixed
     */
    protected $db;

    const FILE_NAME = 'price-new-banner.json';

    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;
        $this->db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
    }


    /**
     * @return mixed
     */
    private function banners()
    {

        $banners = [];

        $bannerAdmin = new bannerPriceAdmin();
        $images      = (array) $bannerAdmin->loadBysort();

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi'    => 1,
            'iv_load_policy' => 3,
            'disablekb'      => 1,
            'autoplay'       => 1,
            'modestbranding'        => 1,
            // Показывает�?�? меню плеера перед началом проигровани�?. �?е нужно показывать какие-либо �?имволы плеера. 25.05.2020 16:19
            'controls'        => 0,
            'showinfo'       => 0,
            'rel'            => 0,
            'loop'           => 0,
            'mute'           => 1,
            'wmode'          => 'transparent',
            'color'          => 'white',
            'theme'          => 'dark'
        );

        $yt = 0;

        /* @FIX by oppo , @Date: 2020-03-16 16:46:03
         * @Desc:  carousel_interval
         */
        // $interval = [25000,500,8500,500,3000,1500,2000,500,2000,500,2000,500];
        $def_carousel_interval = (int) $this->f3->get('carousel_interval');

        foreach ($images as $n => $img) {

            if (empty($img['carousel_interval'])) {
                $img['carousel_interval'] = $def_carousel_interval;
            }

            // false &&
            if (!empty($img['src'])) {
                $banners[] = ['type' => 'img', 'src' => PRICE_BANNER_DIR . $img['src'], 'interval' => $img['carousel_interval']];
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

                $youtube_addon_url = '?' . http_build_query($query, '&');

                $youtube_url = rtrim($img['video_url'], '?');
                $youtube_url = $youtube_url . $youtube_addon_url;
                $banners[]   = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
    }

    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Preise New');
        $this->f3->set('view', 'preisenew.html');
        $this->f3->set('classfoot', 'preise');

        $categories = $this->db->read(self::FILE_NAME);

        // $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/preisenew.js';
        $this->f3->set('addscripts', $addscripts);

        $this->f3->set(
            'createExtraInfoLabel',
            function ($item) {
                if (isset($item['extra_info']) && count($item['extra_info']) > 0) {

                    $line = array_map(function ($v) {
                        return '&bullet;' . $v;
                    }, $item['extra_info']);

                    return '<i data-toggle="tooltip" data-html="true"
                    data-placement="top"
                    class="fa fa-info-circle bloomstooltip"
                    id="bloomstip{{@KEY}}{{@KEY2}}" title=""
                    data-original-title="' . implode('<br/>', $line) . '">
                </i>';
                } else {
                    return '';
                }
            }
        );

        $this->f3->set('categories', $categories);

        /* @FIX by oppo , @Date: 2020-03-05 19:46:28
         * @Desc: add slider
         */
        // $banners = $this->banners();
        // $this->f3->set( 'BANNERS', $banners );
        /** @FIX by oppo (webiprog.de), @Date: 2021-01-12 16:47:30
         * @Desc:  disable banners
         */

        $this->f3->set('BANNERS', []);

        $this->f3->set('ESCAPE', false);
    }
}
