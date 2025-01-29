<?php

class UploadBlooms
{
   /**
    * @var mixed
    */
   private $url;

   /**
    * @var mixed
    */
   public $upload_patch;

   /**
    * @var String The path to the directory images.
    */
   private $folder;
   /**
    * @var String Path to the directory images with the prefix root (global variable containing the subdomain address)
    */
   private $folder_with_root;

   /**
    * @var mixed
    */
   private static $instance;

   /**
    * @var array Les différentes extensions d'images autorisées.
    */

   private $img_extensions = ['jpg', 'jpeg', 'png'];

   const MAX_SIZE          = 2000000;
   const BANNER_UPLOAD_DIR = BANNER_PARENT_ABS_DIR;

   public function __construct()
   {
      $this->f3  = \Base::instance();
      $this->web = \Web::instance();
      //  $base . '/assets/';
      // $assets             = $this->f3->get('ASSETS');
      // $this->upload_patch = ONEPLUS_DIR_PATH . '/upload/baners-blooms';
      $this->upload_patch = rtrim(BANNER_PARENT_ABS_DIR, '/');

      if (!isset(self::$instance)) {
         self::$instance = $this;
      }
   }

   public static function getInstance()
   {
      if (!isset(self::$instance)) {
         self::$instance = new self();
      }
      return self::$instance;
   }

   /** delete VIA AJAX
    * Deleting an image via ajax
    * @access public
    * @return void
    */
   public function imageDelete($path = 'banner', $name = null)
   {
      if ($name === null) {
         $name = $this->f3->get('POST.image_id');
      }

      $full_patch =
         rtrim(BANNER_PARENT_ABS_DIR, '/') . '/' . $path . '/' . $name;
      $filename = pathinfo($full_patch, PATHINFO_BASENAME);
      if (is_file($full_patch) && $this->estUneImage($filename)) {
         unlink($full_patch);
      }
   }

   /**
    * Upload a file in the server
    * @param $file array the file
    * @param $path string the directory for the files
    * @return array code status / path
    */
   public function save($file, $path = 'banner', $resize = false)
   {
      // array (
      //     'image' =>
      //     array (
      //       'name' => 'shufrich.jpg',
      //       'type' => 'image/jpeg',
      //       'tmp_name' => 'E:\\openserver7\\OpenServer\\userdata\\php_upload\\phpB789.tmp',
      //       'error' => 0,
      //       'size' => 58937,
      //     ),
      //   )

      // Config the path to put the file
      $this->url($path);

      // Get the type of the file
      $type = stristr($file['image']['type'], '/', true);
      // file_put_contents ( ONEPLUS_DIR_PATH."/type.txt" , $type , FILE_APPEND | LOCK_EX );
      // $type = $file['image']['type'];
      // Check if the file is a image or a video
      if (true) {
         // file_put_contents ( ONEPLUS_DIR_PATH."/file3.txt" , var_export( $file , true), FILE_APPEND | LOCK_EX );
         $overwrite = true;
         $slug      = true;
         //Upload the file in the repository
         $files = $this->web->receive(
            function ($file, $formFieldName) {
               // Check if the file isn't too heavy
               // return $this->checkSize( $file['type'], $file['size'] );
               // return true;

               // It looks different as possible errors
               if (!$this->estUneImage($file['name'])) {
                  // If upload return an error
                  return $this->response(
                     404,
                     'The uploaded file is not an image.'
                  );
               }
            },
            true,
            function ($fileBaseName, $formFieldName) {
               $salonId = $this->f3->get('POST.salonId');


               if (function_exists('base_convert')) {
                  # code...

                  if ($salonId) {
                     $uniqueid = $salonId . '_' . base_convert(time(), 10, 16);
                  } else {
                     // $uniqueid = md5(uniqid($fileBaseName.mt_rand(), true));
                     $uniqueid = base_convert(time(), 10, 16);
                  }
               } else {
                  if ($salonId) {
                     $uniqueid = $salonId . '_' . substr(time(), 0, 5);
                  } else {
                     // $uniqueid = md5(uniqid($fileBaseName.mt_rand(), true));
                     $uniqueid = substr(time(), 0, 5);
                  }
               }

               // file_put_contents(ONEPLUS_DIR_PATH . "/file3.txt", var_export([$fileBaseName, $formFieldName], true), FILE_APPEND | LOCK_EX);

               // echo $path_parts['extension'], "\n";
               // echo $path_parts['dirname'], "\n";
               // echo $path_parts['basename'], "\n";
               // echo $path_parts['filename'], "\n";

               // $explode   = explode('.', $fileBaseName);
               // $ext       = $explode[count($explode) - 1];
               // $file_name = preg_replace('/.' . $ext . '$/', '', $fileBaseName);

               $path_parts = pathinfo($fileBaseName);
               $ext        = $path_parts['extension'];
               $file_name  = $path_parts['filename'];

               //   $file_name = substr( $file_name, 0, 15 );

               $file_name = self::url_slug($file_name);

               $file_name = $uniqueid . '_' . $file_name . '.' . $ext;

               $this->f3->set('POST.fullfilename', $file_name);
               return $file_name;

               // $this->f3->set('POST.filename',$fileBaseName);
               // $this->f3->set('POST.fileuniqueid',$uniqueid);

               // $ext=explode('.',$fileBaseName);
               // $ext='.'.end($ext);
               // $name='offer_'.time().'_'.rand().$ext;
               // return $name;
            }
         );

         if (!$files[key($files)]) {
            // If the upload don't work, return error to the controller
            return $this->response(404, 'Die Datei wiegt mehr als 2 MB');
         }

         // Check the path of the file
         // If users, we resize and crop the file to avoid the deformation
         if ($resize === true) {
            // key($files) ===  'E:\\openserver7\\OpenServer\\domains\\localhost\\f3-url-shortener/assets/images/banner/20191126-164149.jpg'
            // $files == array (
            //     'E:\\openserver7\\OpenServer\\domains\\localhost\\f3-url-shortener/assets/images/banner/20191126-164149.jpg' => true,
            //   )
            $filename = key($files);
            $newimage = new GumletImageResize($filename);
            //Dimensions    1080 x 1080 px (scaled to 255 x 325 px)
            // 988 x 490 px (scaled to 1110 x 550 px)
            // $h = 550;
            // $w = 1110;
            $w = 960;
            $h = 490;

            // $newimage->resizeToWidth($w, true);
            // $newimage->resizeToBestFit($w, $h, true );
            $newimage->crop($w, $h, true, GumletImageResize::CROPCENTER);

            $newimage->save($filename);

            // $newimage->resize_and_crop($filename, $filename, $w, $h, $quality = 80);
            // $newimage->resizeToHeight($h, true)->save($filename);
            // $newimage->resizeToBestFit($w, $h, true )->save($filename);
         }

         // If upload is Ok
         return $this->response(200, key($files));
      } else {
         // If upload return an error
         return $this->response(404, 'Ein Problem trat beim Hochladen');
      }
   }


   /**
    * Create a web friendly URL slug from a string.
    *
    * Although supported, transliteration is discouraged because
    *     1) most web browsers support UTF-8 characters in URLs
    *     2) transliteration causes a loss of information
    *
    * @author Sean Murphy <sean@iamseanmurphy.com>
    * @copyright Copyright 2012 Sean Murphy. All rights reserved.
    * @license http://creativecommons.org/publicdomain/zero/1.0/
    * @test https://ourcodeworld.com/articles/read/253/creating-url-slugs-properly-in-php-including-transliteration-support-for-utf-8
    *
    * @param string $str
    * @param array $options
    * @return string
    */
   public static function url_slug($str, $options = [])
   {
      // Make sure string is in UTF-8 and strip invalid UTF-8 characters
      $str = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());

      $defaults = [
         'delimiter'     => '-',
         'limit'         => 20,
         'lowercase'     => true,
         'replacements'  => [],
         'transliterate' => true
      ];

      // Merge options
      $options = array_merge($defaults, $options);

      $char_map = [
         // Latin
         'À' => 'A',
         'Á' => 'A',
         'Â' => 'A',
         'Ã' => 'A',
         'Ä' => 'A',
         'Å' => 'A',
         'Æ' => 'AE',
         'Ç' => 'C',
         'È' => 'E',
         'É' => 'E',
         'Ê' => 'E',
         'Ë' => 'E',
         'Ì' => 'I',
         'Í' => 'I',
         'Î' => 'I',
         'Ï' => 'I',
         'Ð' => 'D',
         'Ñ' => 'N',
         'Ò' => 'O',
         'Ó' => 'O',
         'Ô' => 'O',
         'Õ' => 'O',
         'Ö' => 'O',
         'Ő' => 'O',
         'Ø' => 'O',
         'Ù' => 'U',
         'Ú' => 'U',
         'Û' => 'U',
         'Ü' => 'U',
         'Ű' => 'U',
         'Ý' => 'Y',
         'Þ' => 'TH',
         'ß' => 'ss',
         'à' => 'a',
         'á' => 'a',
         'â' => 'a',
         'ã' => 'a',
         'ä' => 'a',
         'å' => 'a',
         'æ' => 'ae',
         'ç' => 'c',
         'è' => 'e',
         'é' => 'e',
         'ê' => 'e',
         'ë' => 'e',
         'ì' => 'i',
         'í' => 'i',
         'î' => 'i',
         'ï' => 'i',
         'ð' => 'd',
         'ñ' => 'n',
         'ò' => 'o',
         'ó' => 'o',
         'ô' => 'o',
         'õ' => 'o',
         'ö' => 'o',
         'ő' => 'o',
         'ø' => 'o',
         'ù' => 'u',
         'ú' => 'u',
         'û' => 'u',
         'ü' => 'u',
         'ű' => 'u',
         'ý' => 'y',
         'þ' => 'th',
         'ÿ' => 'y',

         // Latin symbols
         '©' => '(c)',

         // Greek
         'Α' => 'A',
         'Β' => 'B',
         'Γ' => 'G',
         'Δ' => 'D',
         'Ε' => 'E',
         'Ζ' => 'Z',
         'Η' => 'H',
         'Θ' => '8',
         'Ι' => 'I',
         'Κ' => 'K',
         'Λ' => 'L',
         'Μ' => 'M',
         'Ν' => 'N',
         'Ξ' => '3',
         'Ο' => 'O',
         'Π' => 'P',
         'Ρ' => 'R',
         'Σ' => 'S',
         'Τ' => 'T',
         'Υ' => 'Y',
         'Φ' => 'F',
         'Χ' => 'X',
         'Ψ' => 'PS',
         'Ω' => 'W',
         'Ά' => 'A',
         'Έ' => 'E',
         'Ί' => 'I',
         'Ό' => 'O',
         'Ύ' => 'Y',
         'Ή' => 'H',
         'Ώ' => 'W',
         'Ϊ' => 'I',
         'Ϋ' => 'Y',
         'α' => 'a',
         'β' => 'b',
         'γ' => 'g',
         'δ' => 'd',
         'ε' => 'e',
         'ζ' => 'z',
         'η' => 'h',
         'θ' => '8',
         'ι' => 'i',
         'κ' => 'k',
         'λ' => 'l',
         'μ' => 'm',
         'ν' => 'n',
         'ξ' => '3',
         'ο' => 'o',
         'π' => 'p',
         'ρ' => 'r',
         'σ' => 's',
         'τ' => 't',
         'υ' => 'y',
         'φ' => 'f',
         'χ' => 'x',
         'ψ' => 'ps',
         'ω' => 'w',
         'ά' => 'a',
         'έ' => 'e',
         'ί' => 'i',
         'ό' => 'o',
         'ύ' => 'y',
         'ή' => 'h',
         'ώ' => 'w',
         'ς' => 's',
         'ϊ' => 'i',
         'ΰ' => 'y',
         'ϋ' => 'y',
         'ΐ' => 'i',

         // Turkish
         'Ş' => 'S',
         'İ' => 'I',
         'Ç' => 'C',
         'Ü' => 'U',
         'Ö' => 'O',
         'Ğ' => 'G',
         'ş' => 's',
         'ı' => 'i',
         'ç' => 'c',
         'ü' => 'u',
         'ö' => 'o',
         'ğ' => 'g',

         // Russian
         'А' => 'A',
         'Б' => 'B',
         'В' => 'V',
         'Г' => 'G',
         'Д' => 'D',
         'Е' => 'E',
         'Ё' => 'Yo',
         'Ж' => 'Zh',
         'З' => 'Z',
         'И' => 'I',
         'Й' => 'J',
         'К' => 'K',
         'Л' => 'L',
         'М' => 'M',
         'Н' => 'N',
         'О' => 'O',
         'П' => 'P',
         'Р' => 'R',
         'С' => 'S',
         'Т' => 'T',
         'У' => 'U',
         'Ф' => 'F',
         'Х' => 'H',
         'Ц' => 'C',
         'Ч' => 'Ch',
         'Ш' => 'Sh',
         'Щ' => 'Sh',
         'Ъ' => '',
         'Ы' => 'Y',
         'Ь' => '',
         'Э' => 'E',
         'Ю' => 'Yu',
         'Я' => 'Ya',
         'а' => 'a',
         'б' => 'b',
         'в' => 'v',
         'г' => 'g',
         'д' => 'd',
         'е' => 'e',
         'ё' => 'yo',
         'ж' => 'zh',
         'з' => 'z',
         'и' => 'i',
         'й' => 'j',
         'к' => 'k',
         'л' => 'l',
         'м' => 'm',
         'н' => 'n',
         'о' => 'o',
         'п' => 'p',
         'р' => 'r',
         'с' => 's',
         'т' => 't',
         'у' => 'u',
         'ф' => 'f',
         'х' => 'h',
         'ц' => 'c',
         'ч' => 'ch',
         'ш' => 'sh',
         'щ' => 'sh',
         'ъ' => '',
         'ы' => 'y',
         'ь' => '',
         'э' => 'e',
         'ю' => 'yu',
         'я' => 'ya',

         // Ukrainian
         'Є' => 'Ye',
         'І' => 'I',
         'Ї' => 'Yi',
         'Ґ' => 'G',
         'є' => 'ye',
         'і' => 'i',
         'ї' => 'yi',
         'ґ' => 'g',

         // Czech
         'Č' => 'C',
         'Ď' => 'D',
         'Ě' => 'E',
         'Ň' => 'N',
         'Ř' => 'R',
         'Š' => 'S',
         'Ť' => 'T',
         'Ů' => 'U',
         'Ž' => 'Z',
         'č' => 'c',
         'ď' => 'd',
         'ě' => 'e',
         'ň' => 'n',
         'ř' => 'r',
         'š' => 's',
         'ť' => 't',
         'ů' => 'u',
         'ž' => 'z',

         // Polish
         'Ą' => 'A',
         'Ć' => 'C',
         'Ę' => 'e',
         'Ł' => 'L',
         'Ń' => 'N',
         'Ó' => 'o',
         'Ś' => 'S',
         'Ź' => 'Z',
         'Ż' => 'Z',
         'ą' => 'a',
         'ć' => 'c',
         'ę' => 'e',
         'ł' => 'l',
         'ń' => 'n',
         'ó' => 'o',
         'ś' => 's',
         'ź' => 'z',
         'ż' => 'z',

         // Latvian
         'Ā' => 'A',
         'Č' => 'C',
         'Ē' => 'E',
         'Ģ' => 'G',
         'Ī' => 'i',
         'Ķ' => 'k',
         'Ļ' => 'L',
         'Ņ' => 'N',
         'Š' => 'S',
         'Ū' => 'u',
         'Ž' => 'Z',
         'ā' => 'a',
         'č' => 'c',
         'ē' => 'e',
         'ģ' => 'g',
         'ī' => 'i',
         'ķ' => 'k',
         'ļ' => 'l',
         'ņ' => 'n',
         'š' => 's',
         'ū' => 'u',
         'ž' => 'z'
      ];

      // Make custom replacements
      $str = preg_replace(
         array_keys($options['replacements']),
         $options['replacements'],
         $str
      );

      // Transliterate characters to ASCII
      if ($options['transliterate']) {
         $str = str_replace(array_keys($char_map), $char_map, $str);
      }

      // Replace non-alphanumeric characters with our delimiter
      $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

      // Remove duplicate delimiters
      $str = preg_replace(
         '/(' . preg_quote($options['delimiter'], '/') . '){2,}/',
         '$1',
         $str
      );

      // Truncate slug to max. characters
      $str = mb_substr(
         $str,
         0,
         $options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8'),
         'UTF-8'
      );

      // Remove delimiter from ends
      $str = trim($str, $options['delimiter']);

      return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
   }

   // AJOUT DUNE OFFRE
   /**
    * @param $f3
    * @return mixed
    */
   public function offerAdd($f3)
   {
      if ($f3->get('VERB') == 'POST') {
         $id    = $this->model->offerAdd($f3->get('POST'), $f3->get('SESSION.id'));
         $id    = $id[0]['id'];
         $files = \Web::instance()->receive(
            function ($file, $formFieldName) {
               $type = explode('/', $file['type']);
               if ($file['size'] < 2 * 1024 * 1024 && $type[0] == 'image') {
                  return true;
               }
               return false;
            },
            true,
            function ($fileBaseName, $formFieldName) {
               $ext  = explode('.', $fileBaseName);
               $ext  = '.' . end($ext);
               $name = 'offer_' . time() . '_' . rand() . $ext;
               return $name;
            }
         );
         foreach ($files as $file => $isUpload) {
            if ($isUpload == 1) {
               $this->model->offerAddPhoto($file, $id);
            }
         }
         $f3->reroute('/account?view=offers');
      }
   }

   /**
    * Returns TRUE if it is an image, FALSE otherwise
    * @access public
    * @param nom_fichier le nom du fichier à évaluer
    * @return bool
    */

   public function estUneImage($nom_fichier)
   {
      $extension = pathinfo($nom_fichier, PATHINFO_EXTENSION);
      return in_array($extension, $this->img_extensions);
   }

   /**
    * Set in F3 the path
    * @param $path string the directory of the files
    */
   private function url($path)
   {
      //Choose the folder according to the path
      // $this->f3->set('UPLOADS', $this->upload_patch . '/' . $path . '/');

      $target_abs = rtrim(BANNER_PARENT_ABS_DIR, '/') . '/' . $path;
      helperblooms::op_mkdir($target_abs);
      $this->f3->set(
         'UPLOADS',
         $target_abs . '/'
      );
   }

   /**
    * Resize image
    * @param $file a file
    * @param $type integer the file's type
    * @return boolean
    */
   private function resize($file, $type)
   {
      // Dimensions    960 x 490 px (scaled to 1110 x 550 px)
      // Resize and crop profile picture to 200x200
      $img = new \Image($file, true);
      $img->resize(960, 490);
      $img->save();
      if (file_put_contents($file, $img->dump($type))) {
         return true;
      } else {
         return false;
      }
   }

   /**
    * Check if the size is ok (not to big)
    * @param $fileType string type of the file
    * @param $size integer size of the file
    * @return boolean
    */
   private function checkSize($fileType, $size)
   {
      $type = stristr($fileType, '/', true);

      // A video can't be over 60 mo and an image can't be over 2 mo
      if ($type === 'video' && $size <= 120 * 1024 * 1024) {
         return true;
      } elseif ($type === 'image' && $size <= 2 * 1024 * 1024) {
         return true;
      } else {
         return false;
      }
   }

   /**
    * Response to save function
    * @param $code status code
    * @param $data mixed data to send
    * @return array message to send with status code
    */
   private function response($code, $data)
   {
      // construct the error to send to the controller
      $message['code'] = $code;
      $message['data'] = $data;

      return $message;
   }
}
