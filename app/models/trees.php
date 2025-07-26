<?php
namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');

use config\dbh; 

class trees extends dbh {
    
       public static function getNativeTrees() {
        try {
            $dbInstance = new \config\dbh();
            $db = $dbInstance->connect(1);
    
            $nativeTrees = [];
            $treeInfo = [];
    
            $getNativeTreeInfo = "SELECT * FROM plant_list WHERE class = 'Phil. native tree' ";
            $stmt = $db->prepare($getNativeTreeInfo);
    
            if ($stmt->execute()) {
                $rows = $stmt->fetchAll(); // Use fetchAll() to get all rows
    
                foreach ($rows as $row) {
                    $treeInfo = [
                        'pI' => $row['id'], 
                        'cn' => $row['Common_name'],
                        'sn' => $row['Scientific_name'],
                        'fn' => $row['family_name'],
                        'elev' => $row['elev_range'],
                        'type' => $row['type'],
                        'recom' => $row['recommended'],
                        'class' => $row['class'],
                        'IUCN' => $row['iucn'],
                    ];
    
                    $getNativeTreePhoto = "SELECT * FROM photos WHERE plant_id = ? ";
                    $stmt = $db->prepare($getNativeTreePhoto);
                    $stmt->bindParam(1, $row['id'], \PDO::PARAM_INT);
    
                    if ($stmt->execute()) {
                        $photos = $stmt->fetchAll(); // Use fetchAll() to get all rows
    
                        foreach ($photos as $photo) {
                            $nativeTree = [
                                'filename' => $photo['filename'],
                                'id' => $photo['plant_id'],
                                'cn' => $photo['common_name'],
                                'sf' => $photo['scientific_name'],
                                'fn' => $photo['family_name'],
                                'date' => $photo['upload_date'],
                            ];
    
                            $nativeTrees[] = $nativeTree;
                        }
                    } else {
                        throw new \Exception("Error fetching photo data");
                    }
                }
    
                return [
                    'treeInfo' => $treeInfo,
                    'nativeTrees' => $nativeTrees,
                ];
                
            } else {
                throw new \Exception("Error executing query");
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public static function getSpecificId($id){
    try {
            $dbInstance = new \config\dbh();
            $db = $dbInstance->connect(1);
    
            $nativeTrees = [];
            $treeInfo = [];
    
            $getNativeTreeInfo = "SELECT * FROM plant_list WHERE id = ?";
            $stmt = $db->prepare($getNativeTreeInfo);
            $stmt->bindParam(1, $id, \PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                $row = $stmt->fetch(); // Use fetchAll() to get all rows
    
                    $treeInfo = [
                        'pI' => $row['id'], 
                        'cn' => $row['Common_name'],
                        'sn' => $row['Scientific_name'],
                        'fn' => $row['family_name'],
                        'elev' => $row['elev_range'],
                        'type' => $row['type'],
                        'recom' => $row['recommended'],
                        'class' => $row['class'],
                        'ref' => $row['ref'],
                        'IUCN' => $row['iucn'],
                    ];
    
                    $getNativeTreePhoto = "SELECT * FROM photos WHERE plant_id = ? ";
                    $stmt = $db->prepare($getNativeTreePhoto);
                    $stmt->bindParam(1, $row['id'], \PDO::PARAM_INT);
    
                    if ($stmt->execute()) {
                        $photos = $stmt->fetchAll(); // Use fetchAll() to get all rows
    
                        foreach ($photos as $photo) { 
                            $nativeTree = [
                                'filename' => $photo['filename'],
                                'id' => $photo['plant_id'],
                                'cn' => $photo['common_name'],
                                'sf' => $photo['scientific_name'],
                                'fn' => $photo['family_name'],
                                'date' => $photo['upload_date'],
                                'credit' => $photo['credit'],
                            ];
    
                            $nativeTrees[] = $nativeTree;
                        }
                    } else {
                        throw new \Exception("Error fetching photo data");
                    }
                
                return [
                    'treeInfo' => $treeInfo,
                    'nativeTrees' => $nativeTrees,
                ];
                
            } else {
                throw new \Exception("Error executing query");
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public static function searchSpecies($key){
    try {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $nativeTrees = [];
        $treeInfo = [];

        // Use the LIKE operator to search for partial matches in relevant columns
        $getNativeTreeInfo = "SELECT * FROM plant_list 
                      WHERE class = 'Phil. native tree' 
                      AND (
                        Common_name LIKE :key OR 
                        Scientific_name LIKE :key OR 
                        family_name LIKE :key OR 
                        elev_range LIKE :key OR 
                        type LIKE :key OR 
                        recommended LIKE :key OR 
                        class LIKE :key OR 
                        ref LIKE :key OR
                        status LIKE :key
                      )";

        $stmt = $db->prepare($getNativeTreeInfo);
        $searchTerm = '%' . $key . '%';
        $stmt->bindParam(':key', $searchTerm, \PDO::PARAM_STR);

            if ($stmt->execute()) {
                $rows = $stmt->fetchAll();
    
                foreach ($rows as $row) {
                    $treeInfo = [
                        'pI' => $row['id'], 
                        'cn' => $row['Common_name'],
                        'sn' => $row['Scientific_name'],
                        'fn' => $row['family_name'],
                        'elev' => $row['elev_range'],
                        'type' => $row['type'],
                        'recom' => $row['recommended'],
                        'class' => $row['class'],
                        'ref' => $row['ref'],
                        'IUCN' => $row['status'],
                        'DENR' => $row['s-denr'],
                        'geo' => $row['geographic_range'],
                    ];
    
                    $getNativeTreePhoto = "SELECT * FROM photos WHERE plant_id = ? ";
                    $stmt = $db->prepare($getNativeTreePhoto);
                    $stmt->bindParam(1, $row['id'], \PDO::PARAM_INT);
    
                    if ($stmt->execute()) {
                        $photos = $stmt->fetchAll();
    
                        foreach ($photos as $photo) { 
                            $nativeTree = [
                                'filename' => $photo['filename'],
                                'id' => $photo['plant_id'],
                                'cn' => $photo['common_name'],
                                'sf' => $photo['scientific_name'],
                                'fn' => $photo['family_name'],
                                'date' => $photo['upload_date'],
                                'credit' => $photo['credit'],
                            ];
    
                            $nativeTrees[] = $nativeTree;
                        }
                    } else {
                        throw new \Exception("Error fetching photo data");
                    }
                }
    
                return [
                    'treeInfo' => $treeInfo,
                    'nativeTrees' => $nativeTrees,
                ];
            } else {
                throw new \Exception("Error executing query");
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public static function getStatus($id){
        
        $dbInstance = new \config\dbh();
            $db = $dbInstance->connect(1);
    
            $getNativeTreeInfo = "SELECT * FROM plant_list WHERE id = ?";
            $stmt = $db->prepare($getNativeTreeInfo);
            $stmt->bindParam(1, $id, \PDO::PARAM_INT);
            $stmt->execute();
            $info = $stmt->fetch();
            $iucn = $info['iucn'];
            $sn = $info['Scientific_name'];
            $extr = explode(' ', $sn);
            $genus = $extr[0];
            $spp = $extr[1];
            
            $getDenrStatus = "SELECT * FROM dao_2017_11 WHERE spp LIKE :keyword AND genus LIKE :genus";
            $stmt = $db->prepare($getDenrStatus);
            $key = '%' .$spp. '%';
            $key2 = '%' .$genus. '%';
            $stmt->bindParam(':keyword', $key, \PDO::PARAM_STR);
            $stmt->bindParam(':genus', $key2, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if ($result !== false) {
                // Fetch was successful, proceed with accessing array elements
                $denrStat = $result['conservation_stat'];
                $resiStat = $result['residency_stat'];
                $status = ['iucn' => $iucn, 'denr' => $denrStat, 'geo' => $resiStat];
                return $status;
            } else {
                // No matching row found
                return ['iucn' => $iucn, 'denr' => null];
            }
    }
    
}

?>