<?php

namespace app\models;

require $_SERVER['DOCUMENT_ROOT'].'/config/dbconn.php';

use config\dbh;

class fetch {
    
    public static function adopters(){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $adoptor = [];
        
        $sql = "SELECT fullName, date FROM adoptor";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            
            foreach ($result as $row){
                $name = $row['fullName'];
                $date = $row['date'];
    
                $adoptor[] = [
                    'name' => $name,
                    'date' => $date,
                    ];
            }
            
            header('Content-Type: application/json');
            echo json_encode($adoptor);
        } else {
            echo json_encode([]);
        }
    }
    
    
    
    
}

?>