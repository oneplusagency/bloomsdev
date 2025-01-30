<?php

/**
 * @file: kontaktformular.php
 * @package:    d:\OSPanel\domains\localhost\f3-blooms\app\controllers
 * @created:    Sun Jul 26 2020
 * @author:     oppo, webiprog.de
 * @version:    1.0.0
 * @modified:   Sunday July 26th 2020 9:53:11 am
 * @copyright   (c) 2008-2020 Webiprog GmbH, UA. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

class fachpersonakontakt extends Controller
{
    /**
     * @return mixed
     */

    private $uploaddir;
    /**
     * @var mixed
     */
    private $url_uploaddir;
    /**
     * @var mixed
     */
    private $directory;

    const ALLOWED_MIME = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png'];

    // const MAX_FILE_SIZE = 0.07; // mb
    const MAX_FILE_SIZE = 3; // mb

    /**
     * @return mixed
     */
    public function upload()
    {

        $web = \Web::instance();
        $this->f3->set('UPLOADS', $this->getUploaddir());
        $errorMSG = [];
        $files    = $web->receive(
            function ($file, $formFieldName) use ($errorMSG) {
                $allowed       = self::ALLOWED_MIME;
                $max_file_size = self::MAX_FILE_SIZE * 1024 * 1024;
                $mime          = \Web::instance()->mime($file['tmp_name'], true);
                if (!in_array($mime, $allowed)) {

                    $errorMSG = 'Falsches Dateiformat: ' . self::sanitize_file_name(basename($file['name']));
                    // $this->f3->set( 'SESSION.error', ($errorMSG) );
                    \Flash::instance()->addMessage($errorMSG, 'danger');
                    return false;
                }
                if ($file['size'] > $max_file_size) {
                    // if bigger than 20 MB
                    $errorMSG = sprintf('File ' . self::sanitize_file_name(basename($file['name'])) . ' exceeds size limit. Maximum file size: %sMB', self::MAX_FILE_SIZE);
                    \Flash::instance()->addMessage($errorMSG, 'danger');
                    return false;
                }
                return $file['name'];
            },
            true, // Overwrite file use ($file_name)
            function ($fileBaseName, $formFieldName) {
                $path_parts         = pathinfo($fileBaseName);
                $ext                = $path_parts['extension'];
                $one_file_name      = $path_parts['filename'];
                $basename_file_name = $path_parts['basename'];
                $new_file_name      = self::sanitize($one_file_name);

                if (!$new_file_name) {
                    $randKey       = md5(microtime() . rand());
                    $randKey       = substr($randKey . '', 0, 5);
                    $new_file_name = $randKey;
                }
                $file_name = $new_file_name . '.' . $ext;
                return $file_name;
            }
        );
        $res_files     = [];
        $url_uploaddir = $this->home_url . '/upload/kontakt/';
        foreach ($files as $file => $valid) {
            if (!$valid) {
                continue;
            }
            $file_url = $url_uploaddir . basename($file);
            // $res_files[$file] = $file_url;
            $res_files[$file_url] = $file;
        }
        return $res_files;
    }

    /**
     * Get the value of url_uploaddir
     */
    public function getUrlUploaddir()
    {
        $this->url_uploaddir = $this->home_url . '/upload/kontakt/';
        return $this->url_uploaddir;
    }

    /**
     * Get the value of uploaddir
     */
    public function getUploaddir()
    {
        $this->uploaddir = ONEPLUS_DIR_PATH . '/upload/kontakt/';
        return $this->uploaddir;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Fachpersonal Kontakt');
        $this->f3->set('view', 'fachpersonal-kontakt.html');
        $this->f3->set('classfoot', 'kontaktformular');
        // ADD JS
        $addscripts = [
            'js/layout/validator.min.js',
            'js/layout/fachpersonal-kontakt.js'
        ];
        $this->f3->set('addscripts', $addscripts);

        // putenv('TMPDIR='.ONEPLUS_DIR_PATH . '/tmp/');
        // echo sys_get_temp_dir(); // Outputs: /foo/bar
        // exit;
        // $this->tempFileName = tempnam(sys_get_temp_dir(), '');
        // echo '<pre>';
        //     var_export($this->tempFileName);
        // echo '</pre>';
        // exit;

        $this->f3->set('ESCAPE', false);
    }

    public function sendinfo()
    {

        // $directory = $this->bzdDirectory();
        // if ( !$directory ) {
        //     echo '<div class="alert alert-danger rounded-0 mt-2 mb-2" role="alert">Sys directory does not exist.</div>';
        //     exit;
        // }

        error_reporting(0);

        $errorMSG  = '';
        $name      = '';
        $alter      = '';
        $phone     = '';
        $email     = '';
        $Wahtsapp   = '';
        $uploaddir = ONEPLUS_DIR_PATH . '/upload/kontakt/';
        $from_name = '';
        /* @FIX by oppo (webiprog.de), @Date: 2021-09-07 06:47:23
         * @Desc:
         */
        $from_mail = 'kontakt@bloom-s.de';

        $max_file_size = 3; // mb

        //$from_mail='info@developservice.de';
        $replyto = '';
        /* @FIX by oppo (webiprog.de), @Date: 2021-09-06 14:38:18
         * @Desc:
         */
        // $url_uploaddir = str_replace(ONEPLUS_DIR_PATH , $this->home_url ,ONEPLUS_DIR_PATH . '/upload/kontakt/');
        $url_uploaddir = $this->home_url . '/upload/kontakt/';

        // NAME
        if (empty($_POST['name'])) {
            $errorMSG = 'Name is required ';
        } else {
            $name      = $_POST['name'];
            $from_name = $name;
        }

        if (empty($_POST['alter'])) {
            $errorMSG = 'Alter is required ';
        } else {
            $alter      = $_POST['alter'];
            $from_alter = $alter;
        }

        // EMAIL
        if (empty($_POST['email'])) {
            $errorMSG .= 'Email is required ';
        } elseif (self::validateEmail($_POST['email']) == false) {
            $errorMSG .= 'Invalid email address ';
        } else {
            $email     = $_POST['email'];
            $from_mail = $email;
        }

        $phone = '';
        if (empty($_POST['phone'])) {
            $errorMSG .= 'Phone is required ';
        } else {
            $phone = $_POST['phone'];
        }

        $Wahtsapp = '';
        if (empty($_POST['Wahtsapp'])) {
            $errorMSG .= 'WahtsAPP is required ';
        } else {
            $Wahtsapp = $_POST['Wahtsapp'];
        }



        $mail_img_mess = [];

        $ff    = $this->upload();
        $flash = \Flash::instance();
        // get error
        $error_arr = (array) $flash->getMessages();
        // clear error
        $flash->clearMessages();
        $error_files = bloomArrayHelper::getColumn($error_arr, 'text');
        // file_put_contents(ONEPLUS_DIR_PATH . "/ff_FILE.txt", var_export([$ff, $error_files], true), LOCK_EX);

        if (empty($ff) && !empty($error_files)) {
            $errorMSG .= implode("\n", $error_files);
        }

        if ($errorMSG != '') {
            echo '<div class="alert alert-danger rounded-0" role="alert">' .
                $errorMSG .
                '</div>';
        } else {
            // prepare email body text
            $Body = '<p>';
            $Body .= 'Name: ';
            $Body .= $name;
            $Body .= '</p>';
            $Body .= '<p>';
            $Body .= 'Alter: ';
            $Body .= $alter;
            $Body .= '</p>';
            $Body .= '<p>Mail: ';
            $Body .= $email;
            $Body .= '</p>';
            $Body .= '<p>Telefon: ';
            $Body .= $phone;
            $Body .= '</p>';
            $Body .= '<p>WhatsAPP: ';
            $Body .= $Wahtsapp;
            $Body .= '</p>';
            /* @FIX by oppo (webiprog.de), @Date: 2021-09-06 15:04:26
             * @Desc: add to mail
             */
            // if ($mail_img_mess && is_array($mail_img_mess)) {
            //     $Body .= '<hr />';
            //     $mail_img_mess = array_unique($mail_img_mess);
            //     $img_mess = '<p>' . implode('</div><div>', $mail_img_mess) . '</p>';
            //     $Body .= 'Hochgeladene Dateien: ' . $img_mess;
            // }

            $Subject = 'Bewerbung als Fachkraft';
            $EmailTo = 'bewerbung@bloom-s.de';
            $test    = 0;
            if ($test == 2) {
                $EmailTo = 'svizina@gmail.com';
            }

            $replyto = self::noreply_email($this->home_url, 'noreply');
            if (self::validateEmail($replyto) == false) {
                $replyto = 'no-reply@developservice.de';
            }

            if (!empty($ff)) {
                $fullPath = $ff;
            }
            $error_msg_files = '';
            if (!empty($ff) && !empty($error_files)) {
                $error_msg_files = "" . '<div class="alert-danger p-3"><strong>Warning</strong><br>' . implode("<br>", $error_files) . '</div>';
            }

            $status = $this->sendEmail(
                $EmailTo,
                'Bloom-s.de',
                $Subject,
                $from_mail,
                $from_name,
                $replyto,
                $Body,
                $fullPath,
                $test
            );
            
            // file_put_contents ( ONEPLUS_DIR_PATH."/mail_status.txt" , var_export( [$status,$from_mail, $from_name] , true), FILE_APPEND | LOCK_EX );
            //exit;$status
            // redirect to success page
            if ($status) {
                echo '<div class="alert alert-success rounded-0 mt-2 mb-2" role="alert">Die Anfrage wurde versendet.' . $error_msg_files . '</div>';
            } else {
                echo '<div class="alert alert-danger rounded-0 mt-2 mb-2" role="alert">Something went wrong. Please try after some time.</div>';
            }
        }
        die();
    }

    /**
     * @param $mailto
     * @param $subject
     * @param $from_mail
     * @param $from_name
     * @param $replyto
     * @param $body
     * @param $attachment
     * @return int
     */
    public function sendEmail(
        $mail_to = 'bewerbung@bloom-s.de',
        $name_to = 'bloom-s.de',
        $subject = 'Nachricht von Webseite',
        $from_mail = '',
        $from_name = '',
        $replyto = 'no-reply@developservice.de',
        $body = '',
        $attachment = '',
        $test = false
    ) {
       
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/Exception.php';
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/PHPMailer.php';
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/SMTP.php';
       
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->CharSet = 'UTF-8';
            
            $mail->isHTML(true);

            $mail->ClearReplyTos();

            $mail->addAddress($mail_to, $name_to); // Add a recipient
            $mail->Subject = $subject;

            $mail->setFrom($from_mail, $from_name); // from user
            // $mail->setFrom('kontakt@bloom-s.de', $from_name);
           
            $mail->AddReplyTo($replyto, '');
            if ($test) {
                $mail->addBCC('svizina@gmail.com');
            }
            // $mail->Subject = "Nachricht von Webseite";
            $mail->Body = $body;
            
            if (!empty($attachment)) {
                if (is_array($attachment)) {
                    foreach ($attachment as $key => $att) {
                        $mail->addAttachment($att);
                    }
                } else {
                    $mail->addAttachment($attachment);
                }
            }
            return $mail->send();
        } catch (Exception $e) {
            
            return 0;
            $logger = new \Log(
                $this->f3->get('LOGS') . date('d.m.Y') . 'mail_error.log'
            );
            $logger->write('Mail error: ' . $mail->ErrorInfo, 'r');
        }
    }

    /**
     *  Validate e-mail
     *
     * @param [type] $email
     *
     * @return void
     * @todo
     */
    private static function validateEmail($email)
    {
        $email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return $email;
        }
        return false;
    }

    /**
     * @param $home_url
     * @param $username
     */
    private static function noreply_email($home_url = '', $username = 'noreply')
    {
        if (!$home_url) {
            $home_url =
                (isset($_SERVER['HTTPS']) ? 'https' : 'http') .
                "://$_SERVER[HTTP_HOST]";
        }
        $domain = ltrim($home_url, '^(http|https)://') . '';
        $domain = trim(str_replace(['localhost', '/'], '', $domain));
        //$domain = 'noreply@' . preg_replace( '^(https?:\/\/)(www\.)?', '', get_home_url() );
        return $username . '@' . $domain;
    }

    /**
     * Обрезает строку $text по длине $length. Далее ищет ближайшую справа точку и обрезает по ней.
     * Если точка в строке не найдена, ищет пробел либо таб, и обрезает по ним.
     * Суть в том, чтобы длинные тексты обрезать ровно по окончаниям предложений.
     * @param string  $text Строка
     * @param integer $length Длина
     * @param sting $ending Символы, которые будут добавлены к концу обрезаной строки
     * @param integer $minTextLength Минимальная длина текста, при которой ищутся символы справа
     * @return string Обрезаная строка
     */
    public static function smartCrop($text, $length = 500, $ending = '...', $minTextLength = 10)
    {

        if (!function_exists('mb_strlen')) {
            return substr($text, 0, $length);
        }

        $len = mb_strlen($text);
        if ($len <= $length) {
            return $text;
        }

        $s = mb_substr($text, 0, $length);
        if (mb_strrpos($s, '.') > $minTextLength) {
            $s = mb_substr($s, 0, mb_strrpos($s, '.')) . '.';
            return $s;
        } elseif (mb_strrpos($s, ' ') > $minTextLength) {
            $s = mb_substr($s, 0, mb_strrpos($s, ' '));
        } elseif (mb_strrpos($s, ' ') > $minTextLength) {
            $s = mb_substr($s, 0, mb_strrpos($s, ' '));
        }
        return $s . $ending;
    }

    /**
     * Sanitize file name function
     *
     * @link https://stackoverflow.com/questions/3594627/php-mb-ereg-replace-not-replacing-while-preg-replace-works-as-intended
     * @param [type] $filename
     * @param string $replace
     *
     * @return void
     * @todo
     */
    public static function sanitize_file_name($filename='', $replace = '-')
    {
        mb_regex_encoding("UTF-8");
        $filename = mb_ereg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $filename);
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

        //convert whitespaces and underscore to $replace
        $filename = preg_replace("/[\s_]/", $replace, $filename);
        return $filename;
    }

    /**
     * Function: sanitize
     * Returns a sanitized string, typically for URLs.
     *
     * Parameters:
     *     $string - The string to sanitize.
     *     $force_lowercase - Force the string to lowercase?
     *     $anal - If set to *true*, will remove all non-alphanumeric characters.
     */
    public static function sanitize(
        $string,
        $force_lowercase = true,
        $anal = false
    ) {
        $strip = [
            '~',
            '`',
            '!',
            '@',
            '#',
            "$",
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            // '_',
            '=',
            '+',
            '[',
            '{',
            ']',
            '}',
            '\\',
            '|',
            ';',
            ':',
            "\"",
            "'",
            '&#8216;',
            '&#8217;',
            '&#8220;',
            '&#8221;',
            '&#8211;',
            '&#8212;',
            'â€”',
            'â€“',
            ',',
            '<',
            '.',
            '>',
            '/',
            '?'
        ];
        $clean = trim(str_replace($strip, '', strip_tags($string)));
        $clean = preg_replace('/\s+/', '_', $clean);
        $clean = $anal ? preg_replace('/[^a-zA-Z0-9]/', '', $clean) : $clean;

        $clean = preg_replace(
            [
                // "file--.--.-.--name.zip" becomes "file.name.zip"
                '/-*\.-*/',
                // "file...name..zip" becomes "file.name.zip"
                '/\.{2,}/'
            ],
            '.',
            $clean
        );

        return $force_lowercase
            ? (function_exists('mb_strtolower')
                ? mb_strtolower($clean, 'UTF-8')
                : strtolower($clean))
            : $clean;
    }

    /**
     * @param $directory
     * @return mixed
     */
    public function bzdDirectory($directory = null)
    {

        if (!$directory) {
            $directory = sys_get_temp_dir() . '/tmp';
        }
        if (!file_exists($dir = $directory . '/.')) {
            helperblooms::op_mkdir($directory);
            @mkdir($directory, 0777, true);
        }
        if (false === ($dir = realpath($dir))) {
            throw new InvalidArgumentException(sprintf('Sys directory does not exist (%s)', $directory));
            return false;
        }
        if (!is_writable($dir .= DIRECTORY_SEPARATOR)) {
            throw new InvalidArgumentException(sprintf('Sys directory is not writable (%s)', $directory));
            return false;
        }
        $this->directory = $dir;
        return $this->directory;
    }
}
