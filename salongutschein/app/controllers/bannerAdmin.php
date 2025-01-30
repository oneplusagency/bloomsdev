<?php

// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\PHPMailer;

class bannerAdmin extends Controller
{
    /**
     * @file: bannerAdmin.php
     * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\controllers
     * @created:    Wed Mar 04 2020
     * @author:     oppo
     * @version:    1.0.0
     * @modified:   Wednesday March 4th 2020 12:56:24 pm
     * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     */

    protected $configuration;
    /**
     * @var mixed
     */
    protected $banners;
    /**
     * @var mixed
     */
    protected $db;
    // protected $f3;
    // \Base $f3

    const SORT_BY_SORT = ['order' => 'sort SORT_ASC'];
    //title
    const SORT_BY_ID = ['order' => 'id SORT_DESC'];

    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;

        $this->db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        // $this->configuration = new \DB\Jig\Mapper($this->db, 'sysconfig.json');
        $this->banners = new DB\Jig\Mapper($this->db, 'front-banner.json');
    }

    /**
     * @return mixed
     */
    public function loadBysort()
    {
        $banners = $this->banners;
        // $banners->find();
        $out = array();
        // $banners->fields(array('sort')); // all fields, but not these
        $banners->load();

        if ($banners->dry()) {
            // Nothing found, redirect to main page

        } else {

            $records = $banners->find(null, self::SORT_BY_SORT);
            $fields  = $banners->fields();
            foreach ($records as $key => $value) {
                $temp       = array();
                $temp['id'] = $value['_id'];
                foreach ($fields as $field) {
                    if (isset($value[$field]) && $field != 'password') {
                        $temp[$field] = $value[$field];
                    }
                }
                $out[$temp['id']] = $temp;
            }
        }
        return $out;
    }

    /**
     * @return mixed
     */
    public function maxId()
    {

        // $gallery = $this->banners;
        // $gallery->reset();
        // $gallery->load()->find(null, ['order' => 'sort SORT_DESC']);
        // if ($gallery->dry()) {
        //     return 0;
        // } else {
        //     return $gallery['sort'];
        // }
        $sort = 0;
        $gal  = $this->loadBysort();
        if (!empty($gal) && count($gal)) {
            $sort = end($gal);
            $sort = $sort['sort'];
        }
    }

    public function interval()
    {

        /** @FIX by oppo , @Date: 2020-03-16 16:46:03
         * @Desc:  carousel_interval
         */

        if (isset($_POST["id"]) && isset($_POST["value"])) {
            // array (
            //     'field' => 'carouselinterval',
            //     'value' => '20000',
            //     'id' => '5e6105b80d3fb9.04593350',
            //   )
            $n = bloomArrayHelper::getValueJoom($_POST, 'id', null, 'STRING');
            $value = bloomArrayHelper::getValueJoom($_POST, 'value', null, 'int');
            $slider = $this->banners;
            $slider->load(array('@_id=?', $n));
            if (!$slider->dry()) {
                $slider->carousel_interval = $value;
                $slider->update();
            }

            echo 1;
            // file_put_contents(ONEPLUS_DIR_PATH . "/interval.txt", var_export($_POST, true),  LOCK_EX);
            //exit;
        } else {
            echo 0;
        }
        exit;
    }

    public function banner()
    {

        //action.php
        if (isset($_POST["action"])) {

            $action = bloomArrayHelper::getValueJoom($_POST, 'action', null, 'STRING');
            $type   = bloomArrayHelper::getValueJoom($_POST, 'type', null, 'STRING');

            //json_admin.php

            if ($action == "fetch") {

                // READ FILES FROM THE GALLERY FOLDER
                // $dir = BANNER_ABS_DIR;
                // $images = glob($dir . "*.{jpg,jpeg,gif,png}", GLOB_BRACE);

                // https://github.com/onlymaker/zodiac/blob/81ee913da559014b4f47654dbd4a705868e7d653/src/db/JigMapper.php
                // https://github.com/onlymaker/zodiac/blob/7464ac9321390e585472e85a24b17bc14c941f02/src/helper/Sort.php

                // $results = $this->db->read('front-banner.json');
                // bloomArrayHelper::multisort($results, 'sort');

                $results = $this->loadBysort();

                // file_put_contents ( ONEPLUS_DIR_PATH."/ss-banner.txt" , var_export( $results , true),  LOCK_EX );
                //exit;

                $output = '
                <table id="banner_field" class="table table-bordered table-striped biluy">
                <thead><tr>
                    <th width="5%">Sort</th>
                    <th width="60%">Bild</th>
                    <th width="15%">Sliding-Dauer <i style="color: #ffc107" class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Intervall ist in Millisekunden. 1000 = 1 Sekunde"></i></th>
                    <th width="10%">Entfernen</th>
                    </tr>
                    </thead>
                    <tbody>
                ';

                $s = 1;
                foreach ($results as $n => $img) {

                    if (empty($img['carousel_interval'])) {

                        /** @FIX by oppo , @Date: 2020-03-16 16:46:03
                         * @Desc:  carousel_interval
                         */
                        $slider = $this->banners;
                        $slider->load(array('@_id=?', $n));
                        if (!$slider->dry()) {
                            $slider->carousel_interval = 2000;
                            $slider->update();
                        }
                    }
                    // ++$n;

                    // $image =  $img['src'];
                    // $slider_arr = array(
                    //     'where'         => 'banner',
                    //     'sort'          => $s,
                    //     'title'         => $image,
                    //     'desc'          => '',
                    //     'text'          => '',
                    //     'src'       => $image,
                    //     'url'           => '',
                    //     'video_url'     => '',
                    //     'video_aspect'  => 'embed-responsive-4by3',
                    //     'url_openblank' => false,
                    //     'link_text'     => '',
                    //     'date'     => time()
                    // );
                    // $s++;
                    // $sliderDD = $slider = new DB\Jig\Mapper($this->db, 'slider-front.json');
                    // $sliderDD->copyFrom((array)$slider_arr);
                    // $sliderDD->save();
                    if ($img['src']) {
                        $output .= '
                        <tr  data-tr="' . $n . '" class="moksha align-items-center">
                        <td style="vertical-align: middle;" class="align-items-center jumbotron-icon"><i class="cursor-kaz-kranty fa fa-arrows fa-2" aria-hidden="true"></i> </td>
                        <td style="vertical-align: middle;">
                        <img src="' . BANNER_DIR . $img['src'] . '" height="150" class="img" />
                        </td>

                        <td style="vertical-align: middle;"><div style="cursor: pointer;padding: 0.2rem 0.4rem;font-size: 90.5%;color: #fff;
                        background-color: #212529; border-radius: 0.2rem;" class="edititem" data-id="carouselinterval_' . $n . '">' . $img['carousel_interval'] . '</div>
                        <input style="display: none; height: 30px;width: 69%;" type="text" class="txtedit" value="' . $img['carousel_interval'] . '" data-id="carouselinterval_' . $n . '"/> <a href="javascript:void(0);" class="updatebutton mr-3" data-update="' . $img['carousel_interval'] . '" style="display: none;">
                        <i class="fa fas fa-check text-success"></i></a></td>

                        <td style="vertical-align: middle;" class="align-items-center">' . $img['src'] . '<div><br /><button type="button" name="delete" class="btn btn-danger bt-xs delete" id="' . $n . '">Entfernen</button></div></td>
                        </tr>
                    ';
                    } elseif ($img['video_url']) {

                        $zylya = '';
                        // $zylya = $this->parser($img['video_url']);
                        // https://stackoverflow.com/questions/36337086/my-youtube-video-wont-show-in-iframe
                        // width="350"  frameborder="0" allowfullscreen

                        // https://stackoverflow.com/questions/51424578/embed-youtube-code-is-not-working-in-html/55661292
                        // <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLsyvDWwjkTqtOmqAiTzzfHspTAztB-udL" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                        //     <iframe width="560" height="315" src="https://www.youtube.com/embed/JfJYHfrOGgQ" frameborder="0" gesture="media" allow="autoplay; encrypted-media" allowfullscreen></iframe>

                        $output .= '
                        <tr  data-tr="' . $n . '" class="moksha align-items-center">
                        <td style="vertical-align: middle;" class="align-items-center jumbotron-icon"><i class="cursor-kaz-kranty fa fa-arrows fa-2" aria-hidden="true"></i> </td>
                        <td style="vertical-align: middle;">' . $zylya . '

                        <div class="embed-responsive embed-responsive-21by9">
                        <iframe class="embed-responsive-item" src="' . $img['video_url'] . '" allowfullscreen="true" scrolling="no" height="278"></iframe>
                        </div>

                        </td>

                        <td style="vertical-align: middle;"><div style="cursor: pointer;padding: 0.2rem 0.4rem;font-size: 90.5%;color: #fff;
                        background-color: #212529; border-radius: 0.2rem;" class="edititem" data-id="carouselinterval_' . $n . '">' . $img['carousel_interval'] . '</div>
                        <input style="display: none; height: 30px;width: 69%;" type="text" class="txtedit" value="' . $img['carousel_interval'] . '" data-id="carouselinterval_' . $n . '"/> <a href="javascript:void(0);" class="updatebutton mr-3" data-update="' . $img['carousel_interval'] . '" style="display: none;">
                        <i class="fa fas fa-check text-success"></i></a></td>

                        <td style="vertical-align: middle;" class="align-items-center"><div><br /><button type="button" name="delete" class="btn btn-danger bt-xs delete" id="' . $n . '">Entfernen</button></div></td>
                        </tr>
                    ';
                    }
                }
                $output .= '</tbody></table>';
                echo $output;
            }

            if ($action == "insert") {

                $upload = new UploadBlooms();
                $file   = array();
                $files  = $this->f3->get('FILES');
                $file   = ($files);
                $where  = 'banner';
                $res    = $upload->save($file, $where, true);
                // array (
                //     'code' => 200,
                //     'data' => 'E:\\openserver7\\OpenServer\\domains\\localhost\\f3-url-shortener/assets/images/banner/pizhama-vorotnik.jpg',

                $error_code = bloomArrayHelper::getValueJoom($res, 'code', null, 'STRING');
                $error_data = bloomArrayHelper::getValueJoom($res, 'data', null, 'STRING');

                /** @FIX by oppo , @Date: 2020-03-16 16:46:03
                 * @Desc:  carousel_interval
                 */
                $carousel_interval = bloomArrayHelper::getValueJoom($_POST, 'carousel_interval', 2000, 'STRING');

                if ($error_code == 200) {

                    // $db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
                    // $slider = new DB\Jig\Mapper($db, 'slider-front.json');
                    $image       = basename($error_data);
                    $db          = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
                    $banners     = new DB\Jig\Mapper($db, 'front-banner.json');
                    $slider_save = $banners;

                    // $sort          = $this->maxId();
                    $sort = 0;
                    // $banners->first()->sort ;
                    // $banners->load();
                    // $sort = (int) $banners->last()->sort ;

                    $slider_array = array(
                        'where'         => $type,
                        'sort'          => ($sort),
                        'title'         => $image,
                        'desc'          => '',
                        'text'          => '',
                        'src'           => $image,
                        'url'           => '',
                        'video_url'     => '',
                        'video_aspect'  => 'embed-responsive-4by3',
                        'url_openblank' => false,
                        'link_text'     => '',
                        'date'          => time(),
                        'carousel_interval' => $carousel_interval
                    );

                    $slider_save->copyFrom((array) $slider_array);
                    // $slider->save();
                    $slider_save->insert();

                    echo 'Bild erfolgreicg eingefügt';

                    // $this->f3->set('data', $this->banners);
                    // $this->f3->get('data')->copyFrom((array)$slider_array);
                    // $result = $this->f3->get('data')->save();

                } elseif ($error_data) {
                    echo $error_data;
                } else {
                    echo 'Ein Problem trat beim Hochladen auf';
                }
            }

            if ($action == "delete") {

                $key = $this->f3->get('POST.image_id');
                // array (
                //     'image_id' => '5e5e79eca892a6.73661345',
                //     'action' => 'delete',
                //   )
                $slider = $this->banners;
                $slider->load(array('@_id=?', $key));
                // $res =  $slider->load(array('@image=?', 'neuer-banner-beispiel.jpg'));
                // $nonDefaultMessages = $message->find(array('@message!=?',$defaultMessage));
                if ($slider->dry()) {
                    // Nothing found, redirect to main page
                } else {
                    $name = $slider->src;
                    $slider->erase();
                    if ($name) {
                        $upload = new UploadBlooms();
                        $upload->imageDelete('banner', $name);
                    }

                    // $slider->clear($key);
                }
                echo 'Bild erfolgreich gelöscht';
            }

            if ($action == "youtube") {

                $text        = bloomArrayHelper::getValueJoom($_POST, 'text', null, 'STRING');
                $youtube_url = bloomArrayHelper::getValueJoom($_POST, 'youtube_url', null, 'STRING');
                /** @FIX by oppo , @Date: 2020-03-16 16:46:03
                 * @Desc:  carousel_interval
                 */
                $carousel_interval = bloomArrayHelper::getValueJoom($_POST, 'carousel_interval', 2000, 'STRING');

                if ($youtube_url) {
                    $youtube_url = $this->youtubeParseLink($youtube_url);
                }

                $db           = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
                $banners      = new DB\Jig\Mapper($db, 'front-banner.json');
                $youtube_save = $banners;

                // $sort          = $this->maxId();
                $sort = 0;
                // $banners->first()->sort ;
                // $banners->load();
                // $sort = (int) $banners->last()->sort ;

                $youtube_array = array(
                    'where'         => $type,
                    'sort'          => ($sort),
                    'title'         => 'youtube',
                    'desc'          => '',
                    'text'          => $text,
                    'src'           => '',
                    'url'           => '',
                    'video_url'     => $youtube_url,
                    'video_aspect'  => 'embed-responsive-4by3',
                    'url_openblank' => false,
                    'link_text'     => '',
                    'date'          => time(),
                    'carousel_interval' => $carousel_interval
                );
                $youtube_save->copyFrom((array) $youtube_array);
                $youtube_save->save();

                echo 'Youtube erfolgreicg eingefügt';
            }
        }
        exit;
    }

    public function setPosition()
    {
        // $test = (int) helperblooms::inGet('test', 0);
        // $salonId = helperblooms::inPOST('option_salon');
        $newOrder = bloomArrayHelper::getValueJoom($_POST, 'ids', null, 'array');

        // file_put_contents ( ONEPLUS_DIR_PATH."/ids.txt" , var_export( $newOrder , true),  LOCK_EX );

        // $newOrder = [
        //     0 => '5e5fe6744f55b0.86080316',
        //     1 => '5e5fa7e8ae2dc7.47222164',
        //     2 => '5e5fa7e8ac2d78.92519642',
        //     3 => '5e5fa7e8b0ea23.67879680',
        //     4 => '5e5fa7e8af7d47.55953208',
        // ];

        if (!empty($newOrder) && is_array($newOrder)) {
            // $results = $this->db->read('front-banner.json');
            $db     = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
            $slider = $this->banners;

            foreach ($newOrder as $s => $key_id) {

                ++$s;

                $slider->load(array('@_id=?', $key_id));
                if (!$slider->dry()) {
                    $slider->sort = $s;
                    $slider->update();
                }
            }
        }
        var_export($newOrder);
        exit;
    }

    public function setPositionDrop()
    {

        $newOrder = bloomArrayHelper::getValueJoom($_POST, 'ids', null, 'array');

        // $newOrder = [
        //     0 => '5e5fe6744f55b0.86080316',
        //     1 => '5e5fa7e8ae2dc7.47222164',
        //     2 => '5e5fa7e8ac2d78.92519642',
        //     3 => '5e5fa7e8b0ea23.67879680',
        //     4 => '5e5fa7e8af7d47.55953208',
        // ];
        if (!empty($newOrder) && is_array($newOrder)) {
            $gal    = $this->loadBysort();
            $newArr = [];
            foreach ($newOrder as $s => $key_id) {

                ++$s;

                if (isset($gal[$key_id])) {

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
                    $gal[$key_id]['sort'] = $s;
                    $newArr[$key_id]      = $gal[$key_id];
                }
            }

            $db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
            $db->drop();
            $slider = new DB\Jig\Mapper($db, 'front-banner.json');
            $slider->copyfrom($newArr);
            $slider->insert();
        }

        var_export($newOrder);
        exit;
    }

    /**
     * @param $youtube
     * @return mixed
     */
    private function youtubeParseLink($youtube = '')
    {
        // $str = 'https://youtu.be/wm90WfE9zvM';
        // Youtube.
        $youtube = preg_replace_callback(
            '~(?<=[\s>\.(;\'"]|^)(?:http|https):\/\/[\w\-_%@:|]?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/watch\?.+&v=))([\w-]{11})(?:[^\s|\<|\[]+)?(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>|<\/a> ))[?=&+%\w.-]*[\/\w\-_\~%@\?;=#}\\\\]?~ix',
            function ($matches) {
                if (!empty($matches) && !empty($matches[1])) {

                    // $params = urlencode(json_encode(['video_id' => $matches[1], 'title' => '']));

                    return 'https://www.youtube.com/embed/' . $matches[1];
                }
            },
            $youtube
        );
        return $youtube;
    }

    /**
     * @return mixed
     */
    public function test()
    {

        $banners = $this->banners;
        // $banners->load();
        // echo $banners->first()->sort.PHP_EOL; // 1
        // exit;
        // $banners->find();
        $out = array();
        // $banners->fields(array('sort')); // all fields, but not these
        $records = $banners->load(null, self::SORT_BY_SORT)->find();
        $fields  = $banners->fields();
        foreach ($records as $key => $value) {
            $temp       = array();
            $temp['id'] = $value['_id'];
            foreach ($fields as $field) {
                if (isset($value[$field]) && $field != 'password') {
                    $temp[$field] = $value[$field];
                }
            }
            $out[] = $temp;
        }

        $count = $banners->loaded();

        echo 'count^^ ' . $count;
        echo '<pre>';
        echo $banners->_id . PHP_EOL; // 1
        echo '</pre>';
        $banners->last();

        echo '<pre>last:: ';
        echo $banners->_id . PHP_EOL; // 3
        echo '</pre>';
        echo '<pre>prev:: ';
        echo $banners->prev()->_id . PHP_EOL; // 2
        echo '</pre>';
        echo '<pre>first::';
        echo $banners->first()->_id . PHP_EOL; // 1
        echo '</pre>';
        echo '<pre>skip(2):: ';
        echo $banners->skip(2)->_id . PHP_EOL; // 3
        echo '</pre>';

        $results = $banners->cast();
        echo '<pre>';
        var_export($results);
        echo '</pre>';
        exit;
    }
}
