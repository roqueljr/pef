<?php
namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT']. '/routes/routes.php';
route('dbconn','');

use config\dbh;

class monitor extends dbh {
    
    public static function onlineRecorders(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
    }
    
    public static function progress(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
    }
    
    public static function overview(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
    }
}
?>