<?php
namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');

use config\dbh;

class community extends dbh {
    
    public static function getCommunityInfo(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(0);
        
        $community = [];
        
        $sql = "SELECT * FROM community";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute()) {
            $rows = $stmt->fetchAll();
            
             foreach ($rows as $row) {
                 
                 //echo $row['name'];
                 
                $communities = [ 
                    'name' =>  $row['name'],
                    'tribe' =>  $row['tribe'],
                    'location' =>  $row['location'],
                    'logo' => $row['logo'],
                 ];
                 
                 $community[] = $communities;
             }
            
            return $community;
        }
        
    }
   
   
}




?>