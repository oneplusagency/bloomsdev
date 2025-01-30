<?php
/**
 * Created by PhpStorm.
 * User: Abouba
 * Date: 21/11/2016
 * Time: 01:06
 */

class Validator {

    private $errors = [] ;

     public  function __construct(){}

    /**
     * @return array
     */
    public  function  getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $attr
     * @param $value
     * @param $table
     * @param $db
     * @param $errorsMsg
     */
    public  function  isUniq($attr, $value, $table, $db , $errorsMsg)
    {
     
      $row = $db->myQuery("SELECT id FROM $table WHERE $attr =? ", [$this->filtrer($value)])->fetch();
        if($row)
        {
            $this->setError($value,$errorsMsg);
        }
        return ;
    }


    /**
     * @param $field
     * @param $errorsMsg
     */

    public  function  isEmail($field, $errorsMsg)
    {
        if(!preg_match('/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $field))
        {
            $this->setError($field, $errorsMsg);
        }
        return;
    }


    /**
     * @param $field
     * @param $errorsMsg
     */
    public  function  isAlpha($field, $errorsMsg)
    {
        if(!preg_match("/^[\w\d]+$/", $field))
        {
           $this->setError($field, $errorsMsg);
        }
        return ;

    }

    private function  checkUsernameLength($field){
        if(strlen($field) > 32 or strlen($field) < 2)
        {
            $this->setError($field, "username invalid! <br> username est composer au moins deux(2) caractère." );
        }
    }
    /**
     * @param $field
     */
    public function  userImputValidate($field)
    {
        $num =  func_num_args();
        $args =  func_get_args();
        switch($num){
            case 1 :
                $this->checkUsernameLength($args[0]) ;
                break;

        }
    }

    /** role : si le tableau est vide donc on retourne true sinon false
     * @return bool
     */
    public  function  isValid()
    {
      return (empty($this->errors));
    }

    /**
     * @param $field
     * @param $field1
     * @param $errorsMsg
     */
    private  function  isConfirmedPassword($field, $field1, $errorsMsg)
    {
        if( $field != $field1)
        {
            $this->setError($field, $errorsMsg );
        }
    }

    /**
     * @param $field
     * @param $errorsMsg
     */

    private  function chechLengthPasswword($field, $errorsMsg)
    {
        if(strlen($field) > 32 or strlen($field) < 6)
        {
            $this->setError($field, $errorsMsg );
        }
    }

    /**
     * @param array $files
     * @param $id
     * @return bool|string
     */

    public function uploadIMage($files, $id)
    {
        $ext = '';
        if(!empty($files['image']['name']))
        {
            $f = $files['image']['name'] ;
            $extensions = ['.jpg','.png', '.jpeg','', '.gif','.JPG', '.PNG', '.JPEG','.GIF'] ;
            $ext .= strrchr($f, '.');
            if(!in_array($ext, $extensions))
            {
               $this->setError("imageError", "cette image n'est pas autorisée");
                return false;
            }

            if($files['image']['size'] > 1000000){
                $this->setError('imageError', "votre image est trop volumineuse");
            }

            if(move_uploaded_file($files['image']['tmp_name'], 'images/posts/' . $id.$ext))
            {
                return $id . $ext;
            }
            else{
                $this->setError("imageError","votre image n'a pas été téléchargé");
                return false;
            }
        }
      else
          return false;
    }

    /**
     * @param $field
     */

    private function checkContentPasword($field)
    {
        if(!preg_match("/^[\w\d\W\D]+$/", $field))
        {
            $this->setError($field, "le mot de passe doit etre composer Majuscule et minisculet de chiffres ");
        }
    }


    /**
     * @param $field
     */
    public function  passwordValidate($field)
    {
        $num =  func_num_args();
        $args =  func_get_args();
        switch($num){
            case 1 :
                $this->checkContentPasword($args[0]) ;
                break;
            case 2 :
                $this->chechLengthPasswword($args[0],$args[1]) ;
                break;
            case 3 :
                $this->isConfirmedPassword($args[0], $args[1], $args[2]) ;
                break;
        }
    }


    /**
     * @param $field
     * @param $msgError
     */

    public  function  setError($field, $msgError){
        $this->errors[$field] =  $msgError;
    }

    /**
     * @param $field1
     * @param $field2
     * @param $errorMsg
     */
    private function checkFieldTree($field1, $field2, $errorMsg){
        if(empty($field1) && empty($field2)){
            $this->setError($field1,$errorMsg);
        }
    }


    /**
     * @param $field
     * @param $errorMsg
     */
    private  function  checkfieldTwo($field, $errorMsg){
        if(empty($field)){
            $this->setError($field,$errorMsg);
        }
    }

    /**
     * @param $field
     */
    private  function  checkfielOne($field){
        if(empty($field)){
           $this->setError($field,false);
        }
    }

    private function checkfieldFour($f1, $f2, $f3, $f4)
    {
        if (empty($f1) && empty($f2) && empty($f3) && empty($f4)) {

            $this->setError('empty',$f4);
            return true;
        }
        return false;

    }

    private function checkfielFive($f1, $f2, $f3, $f4, $f5)
    {
        if (empty($f1) && empty($f2) && empty($f3) && empty($f4)) {

            $this->setError('empty',$f5);
            return true;
        }
        return false;

    }
    private function checkfieldSix($f1, $f2, $f3, $f4, $f5,$f6)
    {
        if (empty($f1) && empty($f2) && empty($f3) && empty($f4) && empty($f5) ) {

            $this->setError('empty',$f6);
            return true;
        }
        return false;

    }

    /**
     * @param $field
     * @return bool
     */

    public function  check($field)
    {
        $num =  func_num_args();
        $args =  func_get_args();
        switch($num)
        {
            case 1 :
                $this->checkfielOne($args[0]) ;
                break;
            case 2 :
                $this->checkFieldTwo($args[0], $args[1]) ;
                break;
            case 3 :
                $this->checkFieldTree($args[0], $args[1], $args[2]) ;
                break;
            case 4 :
                return $this->checkfieldFour($args[0], $args[1], $args[2], $args[3]) ;
                break;
            case 5 :
                return $this->checkfielFive($args[0], $args[1], $args[2], $args[3], $args[4]) ;
               break;
            case 6 :
                return  $this->checkfieldSix($args[0], $args[1], $args[2], $args[3], $args[4],$args[5]) ;
                break;
        }
        return false;
    }

   public  function filtrer($field){
      return filter_var($field, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
   }

    public function checkMetaCaract($field = []){
        if ( preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$field[0])
            || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$field[1])
            || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$field[2])
         ) {
            $this->setError("","le caractere speciaux sont pas autorisé dans le nom");
        }
    }


    public  function anti_injection($login, $password)
    {
        # on regarde s'il n'y a pas de commandes SQL
        $banlist = array (
            "insert", "select", "update", "delete", "distinct", "having", "truncate",
            "replace", "handler", "like", "procedure", "limit", "order by", "group by"
        );
        if (is_string($login ))
        {
            $user = str_replace ($banlist, '', strtolower ($login ));
        } else {
            $user = null;
        }

        # on utilise strtolower() pour faire marcher str_ireplace()

        if (is_string( $password ))
        {
            $pass = str_replace($banlist, '', strtolower($password));
        } else {
            $pass = null;
        }

        # on fait un tableau
        # s'il y a des charactères illégaux, on arrête tout
        $array = array('user' => $user, 'pass' => $pass );

        if (in_array (null, $array))
        {
            return true;
        } else{
           return false;
        }

    }
}
