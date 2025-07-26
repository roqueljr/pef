<?php
namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');

use config\dbh; 

class form extends dbh{
    
    public static function getQrId($id){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $sql = "SELECT id FROM qr_codes WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            $qrId = $row['id'];
        }else{
            $qrId = false;
        }
        
        return $qrId;
    }
    
    public static function validateQrId($id){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $sql = "SELECT qrcode_id, seedling_no FROM nursery_monitoring WHERE qrcode_id = :qrcode_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() > 0){
            
        } else {
            echo '<script>alert("QRcode number '.$id.' do not have data yet!");</script>';
            echo '<script>window.location="/nursery/scanner";</script>';
            //echo '<script>showCustomAlert("Data '.$id.' not created yet!", width = "50%", "/nursery/scanner");</script>';
        }
    }
    
    public static function getFormInfo($qrId){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        $sql = "SELECT * FROM nursery_monitoring WHERE qrcode_id = :qrcode_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode_id', $qrId, \PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            $seedlingInfo = [
                'id' => $row['id'],
                'qrcode_id' => $row['qrcode_id'],
                'cn' => $row['cn'],
                'sf' => $row['sf'],
                'fn' => $row['fn'],
                'health' => $row['health'],
                'seedling_no' => $row['seedling_no'],
                'method' => $row['method'],
                'block_no' => $row['block_no'], 
                'provenance' => $row['provenance'],
                'status' => $row['status'],
                'station' => $row['station'],
                'area' => $row['n_area'],
            ];
            
            return $seedlingInfo;
        }
        
        
    }
    
    public static function form($type, $data){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
        
        date_default_timezone_set('Asia/Manila');
        $dateTime = date('Y-m-d h:i:s A');
        
        if($type === 'new'){
            
            if(is_array($data)){
                $qrcode_id = $data['qr_id'];
                $seedling_no = $data['seedling_no'];
                $block_no = $data['block_no'];
                $species = $data['species'];
                $provenance = $data['provenance'];
                $method = $data['method'];
                $health = $data['health'];
                $status = $data['status'];
                $recorder = $data['recorder'];
                $area = $data['area'];
                $station = $data['station'];
            }else{
                echo '<script>alert("Error: Not an array!");</script>';
            }
            
            $sql = "SELECT qrcode_id FROM nursery_monitoring WHERE qrcode_id = :qrcode_id OR seedling_no= :seedling_no";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':qrcode_id', $qrcode_id, \PDO::PARAM_INT);
            $stmt->bindParam(':seedling_no', $seedling_no, \PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->rowCount() > 0){
                //echo '<script>alert("qrcode or seedling no. is already exist!");</script>';
                echo '<script>showCustomAlert("qrcode or seedling no. is already exist!", width = "50%", "");</script>';
            }else{
                $sql = "INSERT INTO nursery_monitoring(qrcode_id, cn, health, seedling_no, method, block_no, provenance, status, n_area, recorder, date, station) VALUES (:qrcode_id, :species, :health, :seedling_no, :method, :block_no, :provenance, :status, :area, :recorder, :date, :station)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':qrcode_id', $qrcode_id, \PDO::PARAM_INT);
                $stmt->bindParam(':species', $species, \PDO::PARAM_STR);
                $stmt->bindParam(':health', $health, \PDO::PARAM_STR);
                $stmt->bindParam(':seedling_no', $seedling_no, \PDO::PARAM_STR);
                $stmt->bindParam(':method', $method, \PDO::PARAM_STR);
                $stmt->bindParam(':block_no', $block_no, \PDO::PARAM_INT);
                $stmt->bindParam(':provenance', $provenance, \PDO::PARAM_STR);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->bindParam(':area', $area, \PDO::PARAM_STR);
                $stmt->bindParam(':recorder', $recorder, \PDO::PARAM_STR);
                $stmt->bindParam(':date', $dateTime, \PDO::PARAM_STR);
                $stmt->bindParam(':station', $station, \PDO::PARAM_STR);
                $stmt->execute();
                if($stmt->rowCount() > 0){
                    //echo '<script>alert("Data added successfully!");</script>';
                    //echo '<script>window.location="/nursery/scanner";</script>';
                    echo '<script>showCustomAlert("Data added successfully!", width = "50%", "/nursery/scanner");</script>';
                }else{
                    //echo '<script>alert("Try again!");</script>';
                    echo '<script>showCustomAlert("Try again!", width = "50%", "");</script>';
                }
            }
        }elseif($type === 'edit'){
            
            if(is_array($data)){
                $qrcode_id = $data['qr_id'];
                $seedling_no = $data['seedling_no'];
                $block_no = $data['block_no'];
                $species = $data['species'];
                $provenance = $data['provenance'];
                $method = $data['method'];
                $health = $data['health'];
                $status = $data['status'];
                $recorder = $data['recorder'];
                $area = $data['area'];
                $station = $data['station'];
            }else{
                echo '<script>alert("Error: Not an array!");</script>'; 
            }
             
            $sql = "SELECT qrcode_id FROM nursery_monitoring WHERE qrcode_id = :qrcode_id OR seedling_no= :seedling_no";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':qrcode_id', $qrcode_id, \PDO::PARAM_INT);
            $stmt->bindParam(':seedling_no', $seedling_no, \PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->rowCount() > 0){
                
                $sql = "UPDATE nursery_monitoring SET n_area= :area, station= :station, cn= :species, health= :health, seedling_no= :seedling_no, method= :method, block_no= :block_no, provenance= :provenance, status= :status, recorder= :recorder, date= :date WHERE qrcode_id= :qrcode_id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':area', $area, \PDO::PARAM_STR);
                $stmt->bindParam(':station', $station, \PDO::PARAM_INT);
                $stmt->bindParam(':species', $species, \PDO::PARAM_STR);
                $stmt->bindParam(':health', $health, \PDO::PARAM_STR);
                $stmt->bindParam(':seedling_no', $seedling_no, \PDO::PARAM_INT);
                $stmt->bindParam(':method', $method, \PDO::PARAM_STR);
                $stmt->bindParam(':block_no', $block_no, \PDO::PARAM_INT);
                $stmt->bindParam(':provenance', $provenance, \PDO::PARAM_STR);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->bindParam(':recorder', $recorder, \PDO::PARAM_STR);
                $stmt->bindParam(':date', $dateTime, \PDO::PARAM_STR);
                $stmt->bindParam(':qrcode_id', $qrcode_id, \PDO::PARAM_INT);
                if($stmt->execute()){
                    //echo '<script>alert("Data updated successfully!");</script>';
                   //echo '<script>window.location="/nursery/scanner";</script>';
                    echo '<script>showCustomAlert("Data updated successfully!", width = "50%", "/nursery/scanner");</script>';
                }else{
                    //echo '<script>alert("Try again!");</script>';
                    echo '<script>showCustomAlert("Try again!", width = "50%", "");</script>';
                }
            }else{
                //echo '<script>alert("qrcode or seedling no. not added!");</script>'; 
                echo '<script>showCustomAlert("qrcode or seedling no. not added!", "");</script>';
            }
            
        }elseif($type === 'delete'){
            $sql = "";
        }
        
    }
    
    public static function datalist($column, $table, $dataId){
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
    
        $sql = "SELECT $column FROM $table"; 
        $stmt = $db->prepare($sql);
    
        if ($stmt->execute()){
            $result = $stmt->fetchAll();
    
            echo '<datalist id="'.$dataId.'">';
            foreach ($result as $row){
                echo '<option value="'.$row[$column].'"></option>';
            }
            echo '</datalist>';
        }
    }
    
    public static function uploadPhoto($filename, $filePath, $qrId) {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);
    
        $sql = "INSERT INTO photo_docs (p_path, photo, qrcode_id) VALUES (:path, :photo, :qrcode_id)";
    
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':path', $filePath, \PDO::PARAM_STR);
    
        // Read the file content and bind it as a binary parameter
        $photoContent = file_get_contents($filePath);
        $stmt->bindParam(':photo', $photoContent, \PDO::PARAM_LOB);
    
        $stmt->bindParam(':qrcode_id', $qrId, \PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            echo '<script>alert("File uploaded and data inserted into the database successfully.");</script>';
        } else {
            echo '<script>alert("Failed to insert data into the database.");</script>';
        }
    }

    public static function edit($id, $table, $column, $value = '', $action = true, $indexDb = 1)
    {
        $sanitizeTable = locker::sanitize($table);
        $sanitizeColumn = locker::sanitize($column);
        $sanitizeValue = locker::sanitize($value);
        $sanitizeId = locker::sanitize($id);
        $dbIndex = locker::sanitize($indexDb);

        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect($dbIndex);

        if($action) {
            $sql  = "SELECT $sanitizeColumn FROM $sanitizeTable WHERE id = :id";
            $stmt = $db->prepare( $sql );
            $stmt->bindParam( ':id', $sanitizeId );
            $stmt->execute();
            if ( $stmt->rowCount() > 0 ) {
                $result = $stmt->fetch();
                return $result[ $sanitizeColumn ];
            } else {
                console('failed to fetching data!');
                return false;
            }
        }else{
            $sql ="UPDATE $sanitizeTable SET $sanitizeColumn = :value WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':value', $sanitizeValue);
            $stmt->bindParam(':id', $sanitizeId);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                console('Table: '.$sanitizeTable.' column: '.$sanitizeColumn.' 1 Data upload successfully!');
                return true;
            }else{
                console('Table: '.$sanitizeTable.' column: '.$sanitizeColumn.' 1 Data upload failed!');
                return false;
            }
        }
    }

    public static function base($table, $column, $value, $dbIndex = 1): int|bool{
        $sanitizeTable = locker::sanitize($table);
        $sanitizeColumn = locker::sanitize($column);
        $sanitizeValue = locker::sanitize($value);
        $sanitizeIndex = locker::sanitize($dbIndex);

        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect($sanitizeIndex);

        $sql = "INSERT INTO $sanitizeTable($sanitizeColumn) VALUES(:value)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':value', $sanitizeValue);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            console('Based is set!');
            return $db->lastInsertId();
        }else{
            console('Failed to set!');
            return false;
        }
    }

    public static function update($id, $table, $column, $value, $indexDb = 1): bool
    {
        $sanitizeId = locker::sanitize($id);
        $sanitizeTable = locker::sanitize($table);
        $sanitizeColumn = locker::sanitize($column);
        $sanitizeValue = locker::sanitize($value);
        $dbIndex = locker::sanitize($indexDb);

        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect($dbIndex);

        $sql = "UPDATE $sanitizeTable SET $sanitizeColumn = :value WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':value', $sanitizeValue);
        $stmt->bindParam(':id', $sanitizeId);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            //console('update is successfully!');
            return true;
        }else{
            //console('Failed to update!');
            return false;
        }
    }
}

?>