<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');
route('date', '');

use config\dbh;
use app\models\Date;

class dataApproval
{

    public static function toBeApprove($recorder, $health, $site, $validation, $date): array
    {

        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $date_ext = explode(' ', $date);
        $day = $date_ext[0];
        $month = str_replace(',', '', $date_ext[1]);
        $year = $date_ext[2];

        $status = 'Checking...';

        if (empty($recorder) && !empty($status) || !empty($health) && !empty($site)) {

            //console('logic 1');
            $status = 'approved';

            $sql = "SELECT * FROM carbon_data WHERE health = :health AND status = :status AND day = :day AND month = :month AND year = :year AND site = :site";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':day', $day, \PDO::PARAM_STR);
            $stmt->bindParam(':month', $month, \PDO::PARAM_STR);
            $stmt->bindParam(':year', $year, \PDO::PARAM_STR);
            $stmt->bindParam(':health', $health, \PDO::PARAM_STR);
            $stmt->bindParam(':site', $site, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();

                $records = [];

                foreach ($result as $record) {

                    $records[] = [
                        'id' => $record['id'],
                        'status' => $record['status'],
                        'qrcodeNum' => $record['qrcode_id'],
                        'treeNum' => $record['tree_no'],
                        'species' => $record['tree_species'],
                        'health' => $record['health'],
                        'diameter' => $record['dbh'],
                        'height' => $record['height'],
                        'funder' => $record['funder_code'],
                        'site' => $record['site'],
                        'plot' => $record['plot'],
                        'date' => $record['month'] . ' ' . $record['day'] . ', ' . $record['year'],
                        // 'location' => $record['latitude'] . ',' . $record['longitude'] . ',' . $record['accuracy'],
                        // 'lat' => $record['latitude'],
                        // 'long' => $record['longitude'],
                        // 'accuracy' => $record['accuracy'],
                        'recorder' => $record['recorder'],
                        'day' => $record['day'],
                    ];
                }

                return $records;
            }
        } elseif (!empty($recorder) && !empty($site) && $status === 'approved' || $validation == true) {

            //console('logic 2');

            $status = "approved";

            $sql = "SELECT * FROM carbon_data WHERE status = :status AND recorder = :recorder AND site = :site AND day = :day AND month = :month AND year = :year";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':recorder', $recorder, \PDO::PARAM_STR);
            $stmt->bindParam(':site', $site, \PDO::PARAM_STR);
            $stmt->bindParam(':day', $day, \PDO::PARAM_STR);
            $stmt->bindParam(':month', $month, \PDO::PARAM_STR);
            $stmt->bindParam(':year', $year, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();

                $records = [];

                foreach ($result as $record) {

                    $records[] = [
                        'id' => $record['id'],
                        'qrcodeNum' => $record['qrcode_id'],
                        'treeNum' => $record['tree_no'],
                        'species' => $record['tree_species'],
                        'health' => $record['health'],
                        'diameter' => $record['dbh'],
                        'height' => $record['height'],
                        'funder' => $record['funder_code'],
                        'site' => $record['site'],
                        'plot' => $record['plot'],
                        'date' => $record['month'] . ' ' . $record['day'] . ', ' . $record['year'],
                        // 'location' => $record['latitude'] . ',' . $record['longitude'] . ',' . $record['accuracy'],
                        // 'lat' => $record['latitude'],
                        // 'long' => $record['longitude'],
                        // 'accuracy' => $record['accuracy'],
                        'recorder' => $record['recorder'],
                        'day' => $record['day'],
                        'status' => $record['status'],
                    ];
                }

                return $records;
            }
        } else {

            //console('logic 3');

            $sql = "SELECT * FROM carbon_data WHERE status = :status AND recorder = :recorder AND site = :site AND day = :day AND month = :month AND year = :year";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':recorder', $recorder, \PDO::PARAM_STR);
            $stmt->bindParam(':site', $site, \PDO::PARAM_STR);
            $stmt->bindParam(':day', $day, \PDO::PARAM_STR);
            $stmt->bindParam(':month', $month, \PDO::PARAM_STR);
            $stmt->bindParam(':year', $year, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();

                $records = [];

                foreach ($result as $record) {

                    $records[] = [
                        'id' => $record['id'],
                        'qrcodeNum' => $record['qrcode_id'],
                        'treeNum' => $record['tree_no'],
                        'species' => $record['tree_species'],
                        'health' => $record['health'],
                        'diameter' => $record['dbh'],
                        'height' => $record['height'],
                        'funder' => $record['funder_code'],
                        'site' => $record['site'],
                        'plot' => $record['plot'],
                        'date' => $record['month'] . ' ' . $record['day'] . ', ' . $record['year'],
                        /*'location' => $record['latitude'].','. $record['longitude'].','.$record['accuracy'],
                        'lat' => $record['latitude'],*/
                        /*'long' => $record['longitude'],
                        'accuracy' => $record['accuracy'],*/
                        'recorder' => $record['recorder'],
                        'day' => $record['day'],
                        'status' => $record['status'],
                    ];
                }
                return $records;
            }
        }
        return [];
    }

    public static function nursery($recorder, $class, $site, $date, $case = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(4);

        switch ($case) {
            case 1:
                $sql = " SELECT *, DATE(date) AS date_only FROM nursery_monitoring WHERE recorder = :recorder AND  DATE(date) = :date AND validation = 'Checking...' ";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':recorder', $recorder);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                }
                return false;
                break;
            case 2:
            default:
                return false;
                break;
        }
    }

    public static function updateStatus($ids, $status)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if ($status === 'delete') {
            $sql = "DELETE FROM carbon_data WHERE id = :ids";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':ids', $ids, \PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        } elseif ($status === 'edit') {

            echo '<script>window.location="/?nav=ZGF0YVZhbGlkYXRpb24=&s=aGVhbHRoeQ==&a=TVBDRg==&d=25%20January,%202024&uid=' . $ids . '&action=edit"</script>';
        } else {
            $sql = "UPDATE carbon_data SET status = :status WHERE id = :ids";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':ids', $ids, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        }
    }

    public static function edit($id, $action, $data)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($data)) {
            $fetch = $data;
        } else {
            $fetch = [];
        }

        if ($action) {
            $sql = "UPDATE carbon_data SET qrcode_id = :qrcode_id, tree_species = :treeSpecies, health = :health, dbh = :dbh, height = :height, funder_code = :funder, site = :site, record_update = :updateDate, update_by = :updateBy, plot = :plot, recorder = :recorder WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':qrcode_id', $fetch['qrcode']);
            $stmt->bindParam(':treeSpecies', $fetch['species']);
            $stmt->bindParam(':health', $fetch['health']);
            $stmt->bindParam(':dbh', $fetch['diameter']);
            $stmt->bindParam(':height', $fetch['height']);
            $stmt->bindParam(':funder', $fetch['funder']);
            $stmt->bindParam(':site', $fetch['site']);
            $stmt->bindParam(':updateDate', $fetch['updateDate']);
            $stmt->bindParam(':updateBy', $fetch['editBy']);
            $stmt->bindParam(':plot', $fetch['plot']);
            $stmt->bindParam(':recorder', $fetch['recorder']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                alertRedirect('Record is update successfully!', '');
            } else {
                console('ERROR: check ' . __FILE__ . 'at line ' . __LINE__);
            }
        } else {
            $sql = "SELECT * FROM carbon_data WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch();
                return $result;
            } else {
                console('ERROR: check ' . __FILE__ . 'at line ' . __LINE__);
            }
        }
    }

    public static function edit_carbon_data_details($tree_health_details_detail_id, $tree_health_details_funder, $tree_health_details_site, $tree_health_details_recorder, $tree_health_details_plot, $tree_health_details_qr_code, $tree_health_details_tree_number, $tree_health_details_species, $tree_health_details_health, $tree_health_details_diameter, $tree_health_details_height, $tree_health_details_update_by, $tree_health_details_update_date)
    {

        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $result = "";

        // $sql = "UPDATE carbon_data SET qrcode_id = :qrcode_id, tree_species = :treeSpecies, health = :health, dbh = :dbh, height = :height, funder_code = :funder, site = :site, record_update = :updateDate, update_by = :updateBy, plot = :plot, recorder = :recorder WHERE id = :id";
        $sql = "UPDATE carbon_data SET qrcode_id = :qrcode_id, tree_species = :treeSpecies, health = :health, dbh = :dbh, height = :height, funder_code = :funder, site = :site, plot = :plot, recorder = :recorder WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode_id', $tree_health_details_qr_code);
        $stmt->bindParam(':treeSpecies', $tree_health_details_species);
        $stmt->bindParam(':health', $tree_health_details_health);
        $stmt->bindParam(':dbh', $tree_health_details_diameter);
        $stmt->bindParam(':height', $tree_health_details_height);
        $stmt->bindParam(':funder', $tree_health_details_funder);
        $stmt->bindParam(':site', $tree_health_details_site);
        // $stmt->bindParam(':updateDate', $tree_health_details_update_date);
        // $stmt->bindParam(':updateBy', $tree_health_details_update_by);
        $stmt->bindParam(':plot', $tree_health_details_plot);
        $stmt->bindParam(':recorder', $tree_health_details_recorder);
        $stmt->bindParam(':id', $tree_health_details_detail_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = "success";
        } else {
            $result = "failed";
            console('ERROR: check ' . __FILE__ . 'at line ' . __LINE__);
        }

        return $result;
    }

    //reforestation_data_validation_details
    public static function edit_reforestation_data_validation_details(
        $reforestation_data_validation_details_detail_id,
        $reforestation_data_validation_details_funder,
        $reforestation_data_validation_details_site,
        $reforestation_data_validation_detailsrecorder,
        $reforestation_data_validation_details_plot,
        $reforestation_data_validation_details_details_qr_code,
        $reforestation_data_validation_details_tree_number,
        $reforestation_data_validation_details_species,
        $reforestation_data_validation_details_health,
        $reforestation_data_validation_details_diameter,
        $reforestation_data_validation_details_height,
        $reforestation_data_validation_details_update_by,
        $tree_health_details_update_date
    ) {

        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $result = "";

        // $sql = "UPDATE carbon_data SET qrcode_id = :qrcode_id, tree_species = :treeSpecies, health = :health, dbh = :dbh, height = :height, funder_code = :funder, site = :site, record_update = :updateDate, update_by = :updateBy, plot = :plot, recorder = :recorder WHERE id = :id";
        $sql = "UPDATE carbon_data SET qrcode_id = :qrcode_id, tree_species = :treeSpecies, health = :health, dbh = :dbh, height = :height, funder_code = :funder, site = :site, plot = :plot, recorder = :recorder WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode_id', $reforestation_data_validation_details_qr_code);
        $stmt->bindParam(':treeSpecies', $reforestation_data_validation_details_species);
        $stmt->bindParam(':health', $reforestation_data_validation_details_health);
        $stmt->bindParam(':dbh', $reforestation_data_validation_details_diameter);
        $stmt->bindParam(':height', $reforestation_data_validation_details_height);
        $stmt->bindParam(':funder', $reforestation_data_validation_details_funder);
        $stmt->bindParam(':site', $reforestation_data_validation_details_site);
        // $stmt->bindParam(':updateDate', $tree_health_details_update_date);
        // $stmt->bindParam(':updateBy', $tree_health_details_update_by);
        $stmt->bindParam(':plot', $reforestation_data_validation_details_plot);
        $stmt->bindParam(':recorder', $reforestation_data_validation_details_recorder);
        $stmt->bindParam(':id', $reforestation_data_validation_details_detail_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = "success";
        } else {
            $result = "failed";
            console('ERROR: check ' . __FILE__ . 'at line ' . __LINE__);
        }

        return $result;
    }
}