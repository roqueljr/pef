<?php
namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');

use config\dbh; 

class sites extends dbh{
    
    public static function getLocation(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $sites  = [];
        
        $sql = "SELECT * FROM sites";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            
            foreach($result as $row) {
                
                $sites[] = [
                'name' => $row['name'], 
                'lat' => $row['latitude'], 
                'lon' => $row['longitude'],
                    ];
            }
            
             return json_encode($sites);
        } else {
            return json_encode(['message' => 'No records found.']);
        }
        
    }
    
}


?>