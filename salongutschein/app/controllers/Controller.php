<?php

class Controller extends \Prefab
{
    /**
     * @var mixed
     */
    protected $f3;
    /**
     * @var mixed
     */
    protected $db;

    protected $response;
    protected $page_host;
    protected $base;
    protected $controller;
    // protected $data = array('BODY' => '', 'RIGHT' => '', 'LEFT' => '');
    protected $home_url;

    private $routes, $aliases; //original backup

    /**
     * Initiate a action depending on ajax request
     * @return null
     */
    public function init()
    {
        $this->path = $this->f3->get('PATH');
        $parts = explode('/', trim($this->path, '/'));
        // http://localhost/f3-url-shortener/termine/salon/18/mitarbeiter/912
        // https://developservice.de/kunden/blooms/1plus/termine/salon/18/mitarbeiter/912
        // array (
        //     0 => 'termine',
        //     1 => 'salon',
        //     2 => '18',
        //     3 => 'mitarbeiter',
        //     4 => '912',
        //   )

        array_shift($parts);
        if (!empty($parts)) {
            $func = $parts[0];
            $func = preg_replace("/[^a-zA-Z0-9_]+/", "", $func);
            // 'salon'
            if ($func != '' && method_exists($this, $func)) {
                array_shift($parts);
                $this->{$func}($parts);
                exit();
            }
        }
        // $this->f3->error(500);
        echo 'Error!';
        exit();
    }


    public function beforeRoute()
    {
        // echo '<pre>';
        //     var_export($this->f3);
        // echo '</pre>';
        // exit;

        $this->config = $this->f3->get('CONFIG');

        $this->f3->set('current_path', $this->f3->get('PARAMS')[0]);

        // $ip = $this->f3->get('IP');
        // $this->f3->set('IP', $ip );
    }

    public function afterRoute()
    {
        // 'PARAMS' =>
        // array (
        //   0 => '/salons.html',
        //   'controller' => 'salons',
        // ),
        // 'PATH' => '/salons.html',
        // 'PATTERN' => '/@controller.html',
        // 'PLUGINS' => 'E:/openserver7/OpenServer/domains/localhost/f3-url-shortener/lib/',
        // 'PORT' => '80',
        // 'PREFIX' => NULL,
        // 'PREMAP' => '',
        // 'QUERY' => '',
        // 'QUIET' => false,
        // 'RAW' => false,
        // 'REALM' => 'http://localhost/f3-url-shortener/salons.html',
        // 'RESPONSE' => '',
        // 'UI' => 'app/views/',
        // 'ROOT' => 'E:\\openserver7\\OpenServer\\domains\\localhost',
        // 'URI' => '/f3-url-shortener/salons.html',
        // 'VERB' => 'GET',
        // 'UI' => 'E:\\openserver7\\OpenServer\\domains\\localhost\\f3-url-shortener/app/views/',
        // 'view' => 'salons.html',
        // e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\views\error\error-404.html

        // $f3 = \Base::instance();

        // https://stackoverflow.com/questions/16575687/get-information-about-the-route-executed-in-fat-free-v3
        // $hive = $this->f3->hive();
        // $tmp = explode('->',$hive['ROUTES'][$this->f3->get('PATTERN')][3][$hive['VERB']][0]);

        // $server_path_to_root = $this->f3->get('ROOT');
        //'E:\\openserver7\\OpenServer\\domains\\localhost' // ROOT
        // '/f3-url-shortener' BASE


        // array (
        //     0 => '/termine/salon/18/ww-2',
        //     'action' => 'salon',
        //     'cid' => '2',
        //     'controller' => 'termine',
        //     'id' => '18',
        //     'palazka' => 'ww',
        //   )

        $db = new \DB\Jig('db/', \DB\Jig::FORMAT_JSON);
        $this->db = $db;
        // $this->mapper = new \DB\Jig\Mapper($this->db, $this->f3->db_filename);

        // set mitarbeiter Id
        if ($this->f3->exists('PARAMS.empid')) {
            $mitarbeiterId = $this->f3->get('PARAMS.empid');
            $this->f3->set('GetmitarbeiterId', (int)$mitarbeiterId);
        } else {
            $this->f3->set('GetmitarbeiterId', false);
        }

        if (!$this->f3->get('AJAX') && $this->controller != 'json') {
            $view = $this->f3->get('view');
            if ($view) {
                $path_to_ui = $this->f3->get('UI');

                if (file_exists($path_to_ui . $view)) {
                    echo \Template::instance()->render('layout/page.html');
                } else {
                    $realm = $this->f3->get('REALM');
                    //https://fatfreeframework.com/3.6/framework-variables
                    // `ERROR.code` - the HTTP status error code (`404`, `500`, etc.)
                    // `ERROR.status` - a brief description of the HTTP status code. e.g. `'Not Found'`
                    // `ERROR.text` - error context
                    // `ERROR.trace` - stack trace stored in an `array()`
                    // `ERROR.level` - error reporting level (`E_WARNING`, `E_STRICT`, etc.)
                    $this->f3->set('ESCAPE', false);
                    // $c = "<p>The requested URL {$_SERVER['REDIRECT_URL']} was not found on this server.</p>";
                    // $c = "Die angeforderte URL {$view} wurde nicht auf diesem Server nicht gefunden";
                    $c = "Die angeforderte URL <span class=\"url-error\">{$realm}</span> wurde auf diesem Server nicht gefunden";
                    $error_title = constant('Base::HTTP_' . 404);
                    $this->f3->set('ERROR', array('code' => 404, 'text' => $c, 'status' => 'Not Found'));
                    $error = $this->f3->get('ERROR');
                    $this->f3->set('title', "{$error['code']} {$error_title}");

                    echo \Template::instance()->render('error/error-404.html');
                }
            } else {
                echo \Template::instance()->render('layout/page.html');
            }
        }

        $this->f3->clear('SESSION.error');
        $this->f3->clear('SESSION.success');
        $this->f3->clear('SESSION.info');
        $this->f3->clear('SESSION.warning');
    }

    // public function impressum()
    // {
    //     $this->f3->set('view', 'layout/page.html');
    // }

    public function __construct()
    {

        // !!! https://avenir.ro/fat-free-framework-tutorials/f3-routes-named-routes-aliases/
        // Fat-Free Framework – 5. Returning to routes and how to work with them

        $f3 = \Base::instance();
        $this->db = null;
        $this->f3 = $f3;
        // $db = new DB\SQL(
        //     $this->f3->get( 'db_dns' ).$this->f3->get( 'db_name' ),
        //     $this->f3->get( 'db_user' ),
        //     $this->f3->get( 'db_pass' ),
        //     array( \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION )
        // );

        // 'PARAMS' =>
        // array (
        //   0 => '/salons/details/25',
        //   'action' => 'details',
        //   'controller' => 'salons',
        //   'id' => '25',
        // ),
        // 'PATH' => '/salons/details/25',
        // 'URI' => '/f3-url-shortener/salons/details/25',

        // $cacheblooms = $this->f3->get('CacheBlooms');
        // $cacheblooms->eraseAll();

        //$this->aliases = $this->f3->get('ALIASES'); //backup
        //$this->routes = $this->f3->get('ROUTES'); //backup

        // TODO move to after ibo double load !!

        // set PAGE_HOST http://localhost/
        $this->page_host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        $this->home_url = $f3->get('SCHEME') . '://' . $f3->get('HOST') . $f3->get('BASE');

        $this->f3->set('PAGE_HOST', $this->page_host);
        // set PAGE_CONTROLLER  home / termine / json
        $this->controller = $controller = $this->f3->get('PARAMS')['controller'];
        $this->f3->set('PAGE_CONTROLLER', $this->controller);
        // set base '/f3-url-shortener'
        $this->base = $base = $this->f3->get('BASE');
        // 'PATH' => '/salons.html',
        $path = $this->f3->get('PATH');
        $uri = $this->f3->get('URI');


        // $dir = $this->f3->split($path);
        // '/f3-url-shortener/' - HOME
        //  f3-url-shortener/salons.html' - salons

        // https://stackoverflow.com/questions/30372739/how-to-set-active-menu-based-on-current-route
        // http://www.w3programmers.com/crud-with-fat-free-framework/

        // exit;
        // $view = $this->f3->get('view');
        // Salons
        // Termine
        // Preise
        // Stylebook
        // Gutscheine
        // Karriere
        // Akademie
        // Bloom's
        /** @FIX by oppo (webiprog.de), @Date: 2020-08-19 15:59:59
         * @Desc: - Последовательность: Разделите академию небольшой вертикальной линией и поместите ее в конец как "старую страницу".
         * Тогда новый порядок будет такой:
         * „Home, Salons, Termine, Preise, Stylebook, Gutscheine, Bewerbung, bloom´s, Kontakt“  и отдельно будет стоять „Akademie“
         */
        $links = array(
            $base => 'Home',
            $base . '/salons.html' => 'Salons',
            $base . '/termine.html' => 'Termine',
            $base . '/preise.html' => 'Preise',
            $base . '/stylebook.html' => 'Stylebook',
            $base . '/gutscheine.html' => 'Gutscheine',
            /* 19.08.2020 16:03 karriere to bewerbung fixed by oppo webiprog.de (oleg@webiprog.de)  */
            $base . '/bewerbung.html' => 'Bewerbung',
            $base . '/blooms.html' => 'bloom´s',
            /* 19.08.2020 16:03 fixed by oppo webiprog.de (oleg@webiprog.de)  */
            $base . '/kontakt.html' => 'Verwaltung',

            $base . '/akademie.html' => '<span class="left-polos">Akademie</span>',
            // $base . '/PATCH.html' => $uri,
            // $base . '/bASE.html' => $view,
        );

        $footer_links = array(
            'javascript:void(0)' => 'Verwaltung',
            $base . '/impressum.html' => 'Impressum',
            $base . '/datenschutz.html' => 'Datenschutz',
        );
        $controller = null;
        // $active = $base;
        $active = null;
        if (rtrim($uri, '/') === rtrim($base, '/')) {
            //array_shift($links);
            $active = null;
            $controller = $this->controller;
            if ($controller) {
                $pos = strpos($uri, $base . '/' . $controller);
                $active = substr($uri, 0, $pos + strlen($base . '/' . $controller));
            }
        } else {
            $controller = $this->controller;
            if ($controller) {
                $pos = strpos($uri, $base . '/' . $controller);
                $active = substr($uri, 0, $pos + strlen($base . '/' . $controller));
            }
        }
        // 'blooms''/kunden/blooms/1plus/blooms.html''/kunden/blooms/1plus'
        // 'blooms''/f3-url-shortener/blooms.html''/f3-url-shortener'

        // echo '<pre style = "color:#fff">';
        // var_export($controller).PHP_EOL;
        // var_export($uri).PHP_EOL;
        // var_export($base).PHP_EOL;
        // echo '</pre>';

        // /Salons/Details/25   /F3-Url-Shortener

        $this->f3->set('PATH', $path);
        $this->f3->set('ACTIVE', $active);
        $this->f3->set('LINKS', $links);
        $this->f3->set('FOOTER_LINKS', $footer_links);
    }

    public function error()
    {
        $this->f3->set('view', 'error.html');
    }

    /**
     * Write session data
     *
     * @return TRUE
     * @param $id string
     * @param $data string
     * @test v5_1-vaporware-master\app\controller\base.php
     *
     */
    // public function vaporware_write($id, $data)
    // {
    //     $fw = \Base::instance();
    //     $sent = headers_sent();
    //     $headers = $fw->get('HEADERS');
    //     if ($id != $this->__session_id) {
    //         $sessionData = static::collection()->findOne(array('session_id' => ($this->__session_id = $id)));
    //         if (isset($sessionData['_id'])) {
    //             $this->bind($sessionData);
    //         }
    //     }
    //     $csrf = $fw->hash($fw->get('ROOT') . $fw->get('BASE')) . '.' . $fw->hash(mt_rand());
    //     $this->set('session_id', $id);
    //     $this->set('data', $data);
    //     $this->set('csrf', $sent ? $this->csrf() : $csrf);
    //     $this->set('ip', $fw->get('IP'));
    //     $this->set('agent', isset($headers['User-Agent']) ? $headers['User-Agent'] : '');
    //     $this->set('timestamp', time());
    //     $this->store();
    //     if (!$sent) {
    //         if (isset($_COOKIE['_'])) {
    //             setcookie('_', '', strtotime('-1 year'));
    //         }
    //         call_user_func_array('setcookie', array('_', $csrf) + $fw->get('JAR'));
    //     }
    //     return true;
    // }

    public function mailman(string $subject, string $mailText, string $rcpt_mail, string $rcpt_name = null): bool
    {
        if ($this->config['smtp_server'] != '') {
            $smtp = new \SMTP(
                $this->config['smtp_server'],
                $this->config['smtp_scheme'],
                $this->config['smtp_port'] == '' ? ($this->config['smtp_scheme'] == 'ssl' ? 465 : 587) : $this->config['smtp_port'],
                $this->config['smtp_username'],
                $this->config['smtp_password']
            );
            $smtp->set('From', '"' . $this->config['page_title'] . '" <' . $this->config['page_mail'] . '>');
            $smtp->set('To', '"' . $rcpt_name . '" <' . $rcpt_mail . '>');
            $smtp->set('Subject', $subject);
            $smtp->set('content_type', 'text/html; charset="utf-8"');

            return $smtp->send($mailText, true);
        } else {
            $headers = array();
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/html; charset=utf-8';
            $headers[] = "From: {$this->config['page_title']} <{$this->config['page_mail']}>";
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            return mail(
                "{$rcpt_name} <{$rcpt_mail}>", // recipient
                $subject, // subject
                $mailText, // content
                implode("\r\n", $headers) // headers
            );
        }
    }

    // save new
    public function cr(\Base $f3)
    {
        $mapper = new \DB\SQL\Mapper($f3->get('DB'), $f3->get('db_table'));

        if ($f3->get('AJAX')) {
            header('Content-Type: application/json');
        }

        // good things take a while
        // sleep(1);

        if (!$f3->get('ENABLE_SAVE')) {
            $f3->error('400', 'Saving new pastes is currently disabled');
        }

        if (!$f3->devoid('POST.cryptdown')) {
            // set expiration
            $lifetime = $f3->get('POST.lifetime');
            if (!in_array($lifetime, array('1h', '1d', '1w', '1m', '1y'))) {
                $lifetime = '1w';
            }
            if ($lifetime == '1h') {
                $lifetime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            } elseif ($lifetime == '1d') {
                $lifetime = date('Y-m-d H:i:s', strtotime('+1 day'));
            } elseif ($lifetime == '1w') {
                $lifetime = date('Y-m-d H:i:s', strtotime('+1 week'));
            } elseif ($lifetime == '1m') {
                $lifetime = date('Y-m-d H:i:s', strtotime('+1 month'));
            } elseif ($lifetime == '1y') {
                $lifetime = date('Y-m-d H:i:s', strtotime('+1 year'));
            }

            // check max size
            $size = strlen($f3->get('POST.cryptdown'));
            $max_size = $f3->get('max_paste_size') * 1000;
            if ($size > $max_size) {
                $f3->error(400, 'Your document (' . $size / 1000 . 'kb) exceeds the maximum size of ' . $max_size / 1000 . 'kb');
            }

            $mapper->data = $f3->get('POST.cryptdown');
            $mapper->crdate = date('Y-m-d H:i:s');
            $mapper->lifetime = $lifetime;
            $mapper->uuid = $f3->hash($f3->SALT . time());
            $mapper->save();

            $path = $f3->LEGACY_ROUTING ? '?r=view/' . $mapper->uuid : '/view/' . $mapper->uuid;

            if ($f3->get('AJAX')) {
                $f3->status(200);

                echo json_encode(array(
                    'pasteURI' => $f3->SCHEME . '://' . $f3->HOST . $f3->BASE . $path,
                    'pasteID' => $mapper->uuid
                ));
                exit();
            }
            $f3->reroute($path);
        } else {
            if ($f3->get('AJAX')) {
                $f3->error(400, 'No message body send');
            } else {
                $f3->reroute('/');
            }
        }
    }
}
