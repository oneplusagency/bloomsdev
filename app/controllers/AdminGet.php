<?php
class AdminGet extends Controller
{
    /**
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $f3 = $this->f3;
        $this->setAdminVar();
        // $f3->set(
        //     'varExists',
        //     function ($varName) use ($f3) {
        //         return $f3->exists($varName);
        //     }
        // );
        // $pass = $f3->get('security.adminPassword');
        $this->init();
    }

    public function index()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Hallo admin");
        $this->f3->set('classfoot', 'no-social-links admin');
        // ADD JS
        $addscripts[] = 'js/admin/login.js';
        // $this->f3->set('addscripts', $addscripts);
        // echo \Template::instance()->render('layout/page.html');
        $this->f3->set('view', 'admin/adminIndex.html');
    }

    /**
     * Banner Manager Kontakt function
     *
     * @return void
     */
    public function bannerKontakt()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Kontakttttt");
        $this->f3->set('classfoot', 'no-social-links banner-kontakt');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-kontakt.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-kontakt.html');
    }

    /**
     * Banner Manager blooms function
     *
     * @return void
     */
    public function bannerblooms()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager blooms");
        $this->f3->set('classfoot', 'no-social-links banner-blooms');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-blooms.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-blooms.html');
    }

    /**
     * Banner Manager bewerbung function
     *
     * @return void
     */
    public function bannerbewerbung()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager bewerbung");
        $this->f3->set('classfoot', 'no-social-links banner-bewerbung');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-bewerbung.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-bewerbung.html');
    }

    /**
     * Banner Manager bewerbung function
     *
     * @return void
     */
    public function bannerfachkraft()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Fachkraft");
        $this->f3->set('classfoot', 'no-social-links banner-fachkraft');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-fachkraft.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-fachkraft.html');
    }

    /**
     * Banner Manager akademie function
     *
     * @return void
     */
    public function bannerakademie()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Akademie");
        $this->f3->set('classfoot', 'no-social-links banner-akademie');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-akademie.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-akademie.html');
    }

    /**
     * Banner Manager Home function
     *
     * @return void
     */
    public function bannersalons()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Salons");
        $this->f3->set('classfoot', 'no-social-links banner-salons');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner-salons.js';
        $this->f3->set('addscripts', $addscripts);
        $this->f3->set('view', 'admin/banner-salons.html');

        $salons_ctrl  = new salons();
        $salons_array = (array) $salons_ctrl->getSalonsController();

        $salons_html = [];
        $html        = '';

        $salonId = $this->f3->get('PARAMS.id');

        if (isset($salons_array) && is_array($salons_array)) {
            // $salons = $salons_array; // 16

            // // $count = count($salons);
            // $base = $this->f3->get('BASE');
            // $rows = array_chunk($salons, ceil(count($salons) / 3), true); // 3 = column count;

            // foreach ($rows as $columns) {
            //     $salons_html[] = '<div class="layout-salon-col">';
            //     foreach ($columns as $sId => $salon) {
            //         $url           = $base . '/salons/details/' . $sId;
            //         $salons_html[] = '<p><a href="' . $url . '">' . trim($salon['DisplayName']) . '</a></p>';
            //     }
            //     $salons_html[] = '</div>';
            // }
            //$html = implode(PHP_EOL, $salons_html);

            $html = '<select name="salon_auswahlen" id="salon_auswahlen" class="form-control rounded-0">';
            $text = 'Bitte Salon auswahlen';
            // - Wählen Sie einen Salon aus -
            // <option value="Ludwigshafen Rhein-Galerie">Ludwigshafen Rhein-Galerie</option>
            $option_salon[] = '<option value="">' . $text . '</option>';
            foreach ($salons_array as $skey => $sv) {
                $selected = '';
                if ($salonId == $skey) {
                    $selected = 'selected="selected"';
                }
                $option_salon[] = '<option value="' . $skey . '" ' . $selected . '>' . trim($sv['DisplayName']) . '</option>';
            }
        }

        $html .= implode("\n", $option_salon);
        $html .= '</select>';

        $this->f3->set('SALONID', $salonId);
        $this->f3->set('SALONS', $html);
        $this->f3->set('ESCAPE', false);
    }

    /**
     * Banner Manager Home function
     *
     * @return void
     */
    public function banner()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Home");
        $this->f3->set('classfoot', 'no-social-links banner');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/banner.js';
        $this->f3->set('addscripts', $addscripts);
        // echo \Template::instance()->render('layout/page.html');
        $this->f3->set('view', 'admin/banner.html');
    }

    /**
     *  Banner Manager Preise function
     *
     * @return void
     * @test https://developservice.de/kunden/blooms/1plus/preise.html
     */
    public function bannerprice()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Preise");
        $this->f3->set('classfoot', 'no-social-links bannerprice');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/bannerprice.js';
        $this->f3->set('addscripts', $addscripts);
        // echo \Template::instance()->render('layout/page.html');
        $this->f3->set('view', 'admin/bannerprice.html');
    }

    /**
     *  Banner Manager Preise function
     *
     * @return void
     * @test https://developservice.de/kunden/blooms/1plus/preise.html
     */
    public function bannerpricenew()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Banner Manager Preise New");
        $this->f3->set('classfoot', 'no-social-links bannerprice');
        // ADD JS
        $addscripts[] = 'js/admin/jquery.dragsort.min.js';
        $addscripts[] = 'js/admin/bannerpricenew.js';
        $this->f3->set('addscripts', $addscripts);
        // echo \Template::instance()->render('layout/page.html');
        $this->f3->set('view', 'admin/bannerpricenew.html');
    }

    public function init()
    {
        $f3         = $this->f3;
        $this->path = $this->f3->get('PATH');
        $parts      = explode('/', trim($this->path, '/'));

        $this->f3->set('path_arr', $parts);

        // https://developservice.de/kunden/blooms/1plus/termine/salon/18/mitarbeiter/912
        // array (
        //     0 => 'termine',
        //     1 => 'salon',
        //     2 => '18',
        //     3 => 'mitarbeiter',
        //     4 => '912',
        //   )

        // echo '<pre>';
        //     var_export($parts);
        // echo '</pre>';
        // exit;

        // array (
        //     0 => 'admin',
        //     1 => 'settings',
        //     2 => 'config',
        //     3 => 'edit',
        //   )

        array_shift($parts);
        if (!empty($parts)) {

            $func = $parts[0];
            $func = preg_replace("/[^a-zA-Z0-9_]+/", "", $func);

            // array (
            //     0 => 'settings',
            //     1 => 'config',
            //   )

            // 'salon'
            if ($func != '' && method_exists($this, $func)) {
                // array_shift($parts);
                // $this->{$func}($parts);
                // exit();
            } else {
                $realm = $this->f3->get('REALM');
                $this->f3->set('SESSION.error', "Die angeforderte URL {$realm} wurde auf diesem Server nicht gefunden");
                $f3->reroute('/admin');
                // $this->index();
                exit();
            }
        }
        // $this->f3->error(500);
        // echo 'Error!';
        // exit();
    }

    public function settings()
    {

        $this->is_Admin();

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Settings Manager");
        $this->f3->set('classfoot', 'no-social-links settings');
        // ADD JS
        $addscripts[] = 'js/layout/jquery.validate.min.js';
        $addscripts[] = 'js/layout/additional-methods.min.js';
        $addscripts[] = 'js/admin/setting.js';
        $this->f3->set('addscripts', $addscripts);

        $path_arr = $this->f3->get('path_arr');

        array_shift($path_arr);
        if (!empty($path_arr[1])) {
            $component = $path_arr[1];
            $component = preg_replace("/[^a-zA-Z0-9_]+/", "", $component);
        } else {
            $component = 'config';
        }

        $this->f3->set('component', $component);

        $section = 'admin/' . $component . '.html';
        $this->f3->set('section', $section);

        $file = ONEPLUS_DIR_PATH . "/app/config/config.ini";
        $ini  = parse_ini_file($file, true, INI_SCANNER_NORMAL | INI_SCANNER_TYPED);

        // array (
        //     'globals' =>
        //     array (
        //       'site' => 'Bloom Frisure',
        //     ),
        //     'security' =>
        //     array (
        //       'adminPassword' => '1plus',
        //     ),
        //     'email' =>
        //     array (
        //       'mail_from_name' => 'Bloom 1plus (no-reply)',
        //       'mail_from_email' => 'gutschein@bloom-s.de',
        //       'noreply_emal' => 'noreply@bloom-s.de',
        //       'smtp_host' => '',
        //       'smtp_port' => 465,
        //       'smtp_secr' => 'ssl',
        //       'smtp_user' => '',
        //       'smtp_pass' => '',
        //     ),
        //     'database' =>
        //     array (
        //     ),
        //   )

        // $encmethods = openssl_get_cipher_methods( false );

        $this->f3->set('ini', $ini);

        // array (
        //     0 => 'admin',
        //     1 => 'settings',
        //     2 => 'config',
        //   )

        $this->f3->set('view', 'admin/settings.html');
    }

    public function parse()
    {
        if ($array = parse_ini_file($this->file, true)) {
            return is_null($this->key) ? $array : [$this->key => $array];
        }
        throw new Exception(sprintf('The file (%s) has syntax errors', $this->file));
    }

    /**
     * @param \Base $f3
     */
    public function config(\Base $f3)
    {

        $file = ONEPLUS_DIR_PATH . "/app/config/config.ini";

        if ($f3->get('VERB') == "POST") {

            $data    = $f3->get('POST');
            $content = "";

            // echo '<pre>';
            //     var_export($data);
            // echo '</pre>';
            // exit;

            // array (
            //     'globals' =>
            //     array (
            //       'site' => 'Bloom Frisure',
            //     ),
            //     'security' =>
            //     array (
            //       'adminPassword' => '1plus',
            //     ),
            //     'email' =>
            //     array (
            //       'mail_from_name' => 'Bloom 1plus (no-reply)',
            //       'mail_from_email' => 'gutschein@bloom-s.de',
            //       'noreply_emal' => 'noreply@bloom-s.de',
            //     ),
            //     'database' => 'database',
            //   )

            // $ini = parse_ini_file( $file, TRUE ,  INI_SCANNER_NORMAL | INI_SCANNER_TYPED );

            IniParser::writeFile($file, $data);

            // foreach ( $data as $section => $values ) {
            //     $content .= "[".$section."]\r\n";
            //     if ( !empty( $values ) ) {

            //         if ( is_array( $values ) ) {
            //             foreach ( $values as $key => $value ) {

            //                 $key= trim($key);

            //                 if (is_string($value)) {
            //                     $content .= $key."=\"".(string)$value."\"\r\n";
            //                 }
            //                 elseif (is_numeric($value)) {
            //                     $content .= $key."=".(int)$value."\r\n";
            //                 }

            //             }
            //         }
            //     }

            // }

            // if ( !$handle = fopen( $file, "w" ) ) {
            //     $f3->set( 'SESSION.error', 'Error while saving settings.' );
            //     return false;
            // }

            // $success = fwrite( $handle, $content );
            // fclose( $handle );

            $f3->set('SESSION.success', 'Successfully saved settings.');
            $f3->reroute('/admin/settings/config');
        }
    }

    // set string from array whit data of ini file type
    // return string ready to save as .ini file
    /**
     * @param $arr
     * @return mixed
     */
    private function setIniString($arr)
    {
        static $str;
        foreach ($arr as $section => $sett) {
            $str .= "[" . $section . "]\r\n";
            foreach ($sett as $key => $val) {
                $str .= "$key = $val\r\n";
            }
            $str .= "\r\n";
        }
        return $str;
    }

    public function beforeRoute()
    {
        $f3 = $this->f3;
        parent::beforeRoute();

        if (!$this->isLoggedIn()) {
            $f3->set('logged', false);
            $f3->set('flash', 'Please login to continue');
            $this->path = $this->f3->get('PATH');
            $parts      = explode('/', trim($this->path, '/'));
            array_shift($parts);
            if (!empty($parts)) {
                $func = $parts[0];
                if ($func != 'login' && $func != 'auth') {
                    $f3->reroute('/admin/login');
                }
            }
        }
        $f3->set('logged', true);
    }

    public function setAdminVar()
    {
        $f3 = $this->f3;

        if ($f3->exists('COOKIE.admin_secret') && $f3->get('COOKIE.admin_secret') === $this->getAdminHashedPassword()) {
            return true;
        } else {
            $f3->set('isAdmin', false);
        }
    }

    public function isLoggedIn()
    {
        $f3            = $this->f3;
        $adminPassword = $this->adminSettingPass();
        if ($f3->exists('COOKIE.admin_secret') && (password_verify($adminPassword, $f3->get('COOKIE.admin_secret')) == true)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $url
     */
    private function is_Admin($url = '/admin/login')
    {
        if (!$this->isLoggedIn()) {
            $this->f3->reroute($url);
        }
    }

    public function login()
    {
        $f3 = $this->f3;

        // 0 => 'admin',
        // 1 => 'login',
        // $f3->set('message', 'Ungültiges Passwort');
        if (!$this->isLoggedIn()) {

            $this->f3->set('isHomePage', false);
            $this->f3->set('title', "Bitte melden Sie sich an");
            $this->f3->set('classfoot', 'no-social-links login');
            // ADD JS
            $addscripts[] = 'js/admin/login.js';
            $this->f3->set('addscripts', $addscripts);
            // echo \Template::instance()->render('layout/page.html');
            $this->f3->set('view', 'admin/login.html');

            // Logger::Info( $f3, "AdminGet.login", "Logged in as admin" );

        } else {
            $f3->reroute('/admin');
        }
    }

    /**
     * @return mixed
     */
    public function getAdminHashedPassword()
    {
        // $f3            = $this->f3;
        $adminPassword   = $this->adminSettingPass();
        $hashed_password = password_hash($adminPassword, PASSWORD_DEFAULT);
        return $hashed_password;
        // return hash('sha256', 'mySaltySalt' . $adminPassword);
    }

    /**
     * @return mixed
     */
    private function adminSettingPass()
    {
        $f3            = $this->f3;
        $adminPassword = $f3->get('security.adminPassword') ? $f3->get('security.adminPassword') : '1plus';
        return $adminPassword;
    }

    //Process login form
    public function auth()
    {
        $f3 = $this->f3;
        $f3 = $this->f3;
        // $f3->set('message', null);
        // $user == 'admin' &&

        $password    = $f3->get('POST.password');
        $adminSecret = $this->getAdminHashedPassword();

        if (password_verify($password, $adminSecret) == true) {
            $f3->set('COOKIE.admin_secret', $adminSecret);
            $f3->set('isAdmin', true);
            $f3->reroute('/admin');
            return true;
        } else {
            // $f3->push('flash', (object) array(
            //     'lvl'    =>    'danger',
            //     'msg'    =>    'Invalid  password'
            // ));
            $f3->set('message', 'Ungültiges Passwort');
            $this->f3->set('SESSION.error', 'Ungültiges Passwort');
            $f3->reroute('/admin/login');
        }
        // $this->login();
    }

    public function logout()
    {
        $f3 = $this->f3;
        $f3->clear('COOKIE.admin_secret');
        // $_SERVER['PHP_AUTH_PW'] = 'xyz';
        // Logger::Info($f3, "AdminGet.logout", "Logged out");
        $f3->reroute('/admin/login');
        // $f3->reroute('/');
    }

    public function absences()
    {
        $f3 = $this->f3;
        echo Template::instance()->render('absences.htm');
    }
}
