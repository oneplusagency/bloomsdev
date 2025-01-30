<?php

/**
 * @file: bannerSalonsAdmin.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-blooms\app\controllers
 * @created:    Fri Jul 24 2020
 * @author:     oppo, webiprog.de
 * @version:    1.0.0
 * @modified:   Friday July 24th 2020 11:45:15 am
 */

// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\PHPMailer;


class bannerSalonsAdmin extends Controller
{

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
        $this->banners = new DB\Jig\Mapper($this->db, 'salons-banner.json');
    }

    /**
     * @return mixed
     */
    public function loadBysort($salonId = null)
    {
        // $banners = $this->banners;
        $db          = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        $banners     = new DB\Jig\Mapper($db, 'salons-banner.json');
        // $banners->find();
        $out = array();
        if (!$salonId) {
            return $out;
        }
        // $banners->fields(array('sort')); // all fields, but not these
        // $banners->load();
        // $this->resource->load(array('_id = ?', $params['id']));
        // $user->load(array('@userID=? AND @password=?','cheetah','chimp'));
        // $user->load(['mail = ?','jacky@email.com']);
        $banners->load(['@salonId = ?', $salonId]);

        // $banners->load(array('@salonId = ?',$salonId));

        if ($banners->dry()) {
            // Nothing found, redirect to main page

        } else {

            $records = $banners->find(array('@salonId = ?', $salonId), self::SORT_BY_SORT);
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

            $n = bloomArrayHelper::getValueJoom($_POST, 'id', null, 'STRING');
            $value = bloomArrayHelper::getValueJoom($_POST, 'value', null, 'INT');
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

        $test = (int) helperblooms::inGet('test', 0);
        if ($test) {
            $_POST["action"] = "fetch";
            $_POST["salonId"] = "8";
            echo '<pre>';
            var_export($_POST);
            echo '</pre>';
            exit;
        }

        //action.php
        if (isset($_POST["action"]) && $this->f3->get('AJAX')) {

            $action = bloomArrayHelper::getValueJoom($_POST, 'action', null, 'STRING');
            $where   = bloomArrayHelper::getValueJoom($_POST, 'type', 'salons-banner', 'STRING');
            $salonId   = bloomArrayHelper::getValueJoom($_POST, 'salonId', null, 'INT');

            //json_admin.php

            if ($action == "fetch") {

                // READ FILES FROM THE GALLERY FOLDER
                // $dir = BANNER_ABS_DIR;
                // $images = glob($dir . "*.{jpg,jpeg,gif,png}", GLOB_BRACE);

                // https://github.com/onlymaker/zodiac/blob/81ee913da559014b4f47654dbd4a705868e7d653/src/db/JigMapper.php
                // https://github.com/onlymaker/zodiac/blob/7464ac9321390e585472e85a24b17bc14c941f02/src/helper/Sort.php

                // $results = $this->db->read('salons-banner.json');
                // bloomArrayHelper::multisort($results, 'sort');

                $results = $this->loadBysort($salonId);

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
                    // ++$n;

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

                    if ($img['src']) {
                        $output .= '
                        <tr  data-tr="' . $n . '" class="moksha align-items-center">
                        <td style="vertical-align: middle;" class="align-items-center jumbotron-icon"><i class="cursor-kaz-kranty fa fa-arrows fa-2" aria-hidden="true"></i> </td>
                        <td style="vertical-align: middle;" >
                        <img src="' . BANNER_PARENT_URL_DIR . '/salons-banner/' . $img['src'] . '" height="150" class="img" />
                        </td>

                        <td style="vertical-align: middle;"><div style="cursor: pointer;padding: 0.2rem 0.4rem;font-size: 90.5%;color: #fff;
                        background-color: #212529; border-radius: 0.2rem;" class="edititem" data-id="carouselinterval_' . $n . '">' . $img['carousel_interval'] . '</div>
                        <input style="display: none; height: 30px;width: 69%;" type="text" class="txtedit" value="' . $img['carousel_interval'] . '" data-id="carouselinterval_' . $n . '"/> <a href="javascript:void(0);" class="updatebutton mr-3" data-update="' . $img['carousel_interval'] . '" style="display: none;">
                        <i class="fa fas fa-check text-success"></i></a></td>


                        <td style="vertical-align: middle;" class="align-items-center"><div><button type="button" name="delete" class="btn btn-danger bt-xs delete" style="margin-top: 2.7em;margin-bottom: 10px;" id="' . $n . '">Entfernen</button></div><p>' . $img['src'] . '</p></td>
                        </tr>
                    ';
                    } elseif ($img['video_url']) {

                        $zylya = '';

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


                $salonId   = bloomArrayHelper::getValueJoom($_POST, 'salonId', null, 'INT');

                try {

                    $upload = new UploadBlooms();
                    $file   = array();
                    $files  = $this->f3->get('FILES');
                    $file   = ($files);
                    $where  = $where;
                    $res    = $upload->save($file, $where, true);
                    $error_code = bloomArrayHelper::getValueJoom($res, 'code', null, 'STRING');
                    $error_data = bloomArrayHelper::getValueJoom($res, 'data', null, 'STRING');

                } catch (Exception $e) {
                    //throw new Exception($e);
                    $error_code = 500;
                    $error_data =  $e->getMessage();
                }

                // array (
                //     'code' => 200,
                //     'data' => 'E:\\openserver7\\OpenServer\\domains\\localhost\\f3-url-shortener/assets/images/banner/pizhama-vorotnik.jpg',

                /** @FIX by oppo , @Date: 2020-03-16 16:46:03
                 * @Desc:  carousel_interval
                 */
                $carousel_interval = bloomArrayHelper::getValueJoom($_POST, 'carousel_interval', 2000, 'STRING');


                if ($error_code == 200) {

                    // $db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
                    // $slider = new DB\Jig\Mapper($db, 'slider-front.json');
                    $image       = basename($error_data);
                    $db          = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
                    $banners     = new DB\Jig\Mapper($db, 'salons-banner.json');
                    $slider_save = $banners;

                    // $sort          = $this->maxId();
                    $sort = 0;
                    // $banners->first()->sort ;
                    // $banners->load();
                    // $sort = (int) $banners->last()->sort ;

                    $slider_array = array(
                        'salonId'         => $salonId,
                        'where'         => $where,
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

                    if (!empty($slider->where)) {
                        $where = $slider->where;
                    }

                    $slider->erase();
                    if ($name) {
                        $upload = new UploadBlooms();
                        $upload->imageDelete($where, $name);
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
                $banners      = new DB\Jig\Mapper($db, 'salons-banner.json');
                $youtube_save = $banners;

                // $sort          = $this->maxId();
                $sort = 0;
                // $banners->first()->sort ;
                // $banners->load();
                // $sort = (int) $banners->last()->sort ;

                $youtube_array = array(
                    'where'         => $where,
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
            // $results = $this->db->read('salons-banner.json');
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
                    $gal[$key_id]['sort'] = $s;
                    $newArr[$key_id]      = $gal[$key_id];
                }
            }

            $db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
            $db->drop();
            $slider = new DB\Jig\Mapper($db, 'salons-banner.json');
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
