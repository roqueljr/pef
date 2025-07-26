<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');

use config\dbh; 

class location extends dbh{
    
    public static function find($siteName){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $sql = "SELECT latitude, longitude, name FROM sites WHERE name = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $siteName);
        $stmt->execute();
        
        
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            
            $locations = [];
            
            foreach ($result as $location){
                $locations[] = [
                    'lat' => $location['latitude'],
                    'lon' => $location['longitude'],
                    'name' => $location['name'],
                ];
            }
            
            return $locations;
            
        }else{
            return false;
        }
        
    }
    
    public static function get(): array{
	    $dbInstance = new \config\dbh();
	    $db = $dbInstance->connect(1);
	    $sql = "SELECT latitude, longitude, name FROM sites WHERE name = :site";
	    $stmt = $db->prepare($sql);
	    $stmt->execute();
		if($stmt->rowCount() > 0){
			return $stmt->fetchAll();
		}else{
			return [];
		}
    }
}








?>