<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn', '');
route('locker');

use config\dbh;
use app\models\locker;

class photo extends dbh
{

    public static function checkQrId($id, $funder, $site, $plot)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT qrcode_id FROM carbon_data WHERE qrcode_id = :qrCode AND funder_code = :funder AND site = :site AND plot = :plot";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrCode', $id);
        $stmt->bindParam(':funder', $funder);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':plot', $plot);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            $qrId_data_exist = $result['qrcode_id'];
        }

        $sql = "SELECT qrcode_id FROM refo_monitoring_photos WHERE qrcode_id = :qrCode AND funder_code = :funder AND site = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrCode', $id);
        $stmt->bindParam(':funder', $funder);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            $qrId_photo_exist = $result['qrcode_id'];
        }

        if ($qrId_data_exist === $qrId_photo_exist) {
            return true;
            die();
        }

        return false;
    }

    public static function getInfos($site, $funder, $plot)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT qrcode_id, path FROM refo_monitoring_photos WHERE area_code = :site AND funder_code = :funder";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':funder', $funder);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();

            $photos = [];

            foreach ($result as $row) {

                $response = self::checkQrId($row['qrcode_id'], $funder, $site, $plot);

                if ($response === false) {
                    continue;
                }

                $photos[] = [
                    'qrId' => $row['qrcode_id'],
                    'path' => $row['path']
                ];
            }

            return $photos;
        }
    }

    public static function getDetails($qrId)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT * FROM carbon_data WHERE qrcode_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $qrId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $details = [
                'id' => $row['id'],
                'status' => $row['status'],
                'qrcode' => $row['qrcode_id'],
                'no' => $row['tree_no'],
                'species' => $row['tree_species'],
                'health' => $row['health'],
                'dbh' => $row['dbh'],
                'height' => $row['height'],
                'funder' => $row['funder_code'],
                'site' => $row['site'],
                'plot' => $row['plot'],
                'date' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                'recorder' => $row['recorder'],
                'coordinates' => $row['latitude'] . ', ' . $row['longitude'] . ' ' . $row['accuracy'],
            ];

            return $details;
        }
    }

    public static function getDatailsBySite($site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT * FROM carbon_data WHERE site = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();

            $details = [];

            foreach ($result as $row) {

                $details[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
                    'qrcode' => $row['qrcode_id'],
                    'no' => $row['tree_no'],
                    'species' => $row['tree_species'],
                    'health' => $row['health'],
                    'dbh' => $row['dbh'],
                    'height' => $row['height'],
                    'funder' => $row['funder_code'],
                    'site' => $row['site'],
                    'plot' => $row['plot'],
                    'date' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                    'recorder' => $row['recorder'],
                    'coordinates' => $row['latitude'] . ', ' . $row['longitude'] . ' ' . $row['accuracy'],
                ];
            }


            return $details;
        }

        return [];
    }

    public static function getPlotCount($site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT DISTINCT plot FROM carbon_data WHERE site = :site AND status = 'approved' ORDER BY plot ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public static function plotGroupByMonthYear($plot, $site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT CONCAT(month, ' ', day, ', ', year) as date FROM carbon_data WHERE status = 'approved' AND site = :site AND plot = :plot GROUP BY date ORDER BY year DESC, day DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':plot', $plot);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            return $result;
        }

        return false;
    }

    public static function plotGroupByMonthYear2($plot, $site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT CONCAT(month, ' ', day, ', ', year) as date, CONCAT(month, '-', day,'-', year) as date_value, month, day, year, site, funder_code, plot FROM carbon_data WHERE status = 'approved' AND site = :site AND plot = :plot GROUP BY date ORDER BY year DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':plot', $plot);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $details = [];

            foreach ($result as $row) {

                if($row['month'] == "January"){
                    $row['month'] = 1;
                }else if($row['month'] == "February"){
                    $row['month'] = 2;
                }else if($row['month'] == "March"){
                    $row['month'] = 3;
                }else if($row['month'] == "April"){
                    $row['month'] = 4;
                }else if($row['month'] == "May"){
                    $row['month'] = 5;
                }else if($row['month'] == "June"){
                    $row['month'] = 6;
                }else if($row['month'] == "July"){
                    $row['month'] = 7;
                }else if($row['month'] == "August"){
                    $row['month'] = 8;
                }else if($row['month'] == "September"){
                    $row['month'] = 9;
                }else if($row['month'] == "October"){
                    $row['month'] = 10;
                }else if($row['month'] == "November"){
                    $row['month'] = 11;
                }else if($row['month'] == "December"){
                    $row['month'] = 12;
                } 

                $details[] = [
                    'date_value' => $row['date_value'],
                    'date' => $row['date'],
                    'month' => $row['month'],
                    'day' => $row['day'],
                    'year' => $row['year'],
                    'site' => $row['site'],
                    'funder_code' => $row['funder_code'],
                    'plot' => $row['plot']
                ];
            }

            return $details;
        }

        return false;
    }

    public static function getImageByQrcodeId($qrCode, $site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT path FROM refo_monitoring_photos WHERE qrcode_id = :qrcode AND area_code = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode', $qrCode);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            $dir = 'https://www.qrscanner.pefcarbonsink.info/reforestation/';
            $path = $dir . $result['path'];
            return $path;
        }

        return false;
    }

    // public static function getImageByQrcodeIdMultiple($qrCode, $site): array|bool
    public static function getImageByQrcodeIdMultiple($qrCode, $site)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT r.path, r.datetime, c.tree_species, p.Scientific_name, p.family_name FROM refo_monitoring_photos r LEFT JOIN carbon_data c ON r.qrcode_id = c.qrcode_id AND c.site = r.area_code LEFT JOIN plant_list p ON p.common_name = c.tree_species WHERE r.qrcode_id = :qrcode AND r.area_code = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode', $qrCode);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $dirPath = 'https://www.qrscanner.pefcarbonsink.info/reforestation/';
            $paths = [];

            foreach ($result as $row) {
                $dir = $dirPath . $row['path'];
                $date = $row['datetime'];
                $sp = $row['tree_species'];
                $sn = $row['Scientific_name'];
                $fn = $row['family_name'];
                $paths[] =  [$dir, $date, $sp, $sn, $fn];
            }

            return $paths;
        }

        return [];
    }

    public static function photoViewGroupByPlotDate($site, $plot, $date)
    {
        $dbInstance = new \config\dbh();
        $db = $dbInstance->connect(1);

        $extract_comma = str_replace(",", "", $date);
        $date = explode(' ', $extract_comma);
        $month = $date[0] ?? '';
        $day = $date[1] ?? '';
        $year = $date[2] ?? '';
        $status = 'approved';

        $start = 100;


        $sql = "SELECT * FROM carbon_data WHERE site = :site AND plot = :plot AND month = :month AND day = :day AND year = :year AND status = :status";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':plot', $plot);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();

            $dataInfo = [];

            foreach ($result as $row) {

                $imagePath = self::getImageByQrcodeId($row['qrcode_id'], $row['site']);

                $dataInfo[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
                    'qrcode' => $row['qrcode_id'],
                    'tree_no' => $row['tree_no'],
                    'species' => $row['tree_species'],
                    'health' => $row['health'],
                    'dbh' => $row['dbh'],
                    'height' => $row['height'],
                    'funder' => $row['funder_code'],
                    'site' => $row['site'],
                    'plot' => $row['plot'],
                    'date' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                    // 'recorder' => $row['recorder'],
                    // 'coordinates' => $row['latitude'] . ', ' . $row['longitude'] . ' ' . $row['accuracy'] ?? '',
                    // 'lat' => $row['latitude'] ?? '',
                    // 'lon' => $row['longitude'] ?? '',
                    'imagePath' => $imagePath,
                ];
            }

            return  $dataInfo;
        }

        return false;
    }

    public static function getPhotos($projectFunder = '', $offset = '', $limit = 50, $site = '', $plot = '', $date = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $offset_query = '';
        $site_query = '';
        $plot_query = '';
        $date_query = '';
        $limit_query = '';

        if (!empty($limit)) {
            $limit_query = " LIMIT $limit";
        }

        if (!empty($offset)) {
            $offset_query = "AND pmu.id >= $offset";
        }

        if (!empty($site)) {
            $site_query = "AND pmu.site = '$site'";
        }

        if (!empty($plot)) {
            $plot_query = "AND pmu.plot = $plot";
        }

        if (!empty($date)) {
            $date = $date . '%';
            $date_query = "AND pmu.upload_date LIKE '$date'";
        }

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT * FROM carbon_data cd LEFT JOIN photo_mannual_upload pmu ON cd.qrcode_id =  pmu.qrcode_id WHERE cd.funder_code IN ($placeholders) AND status = 'approved' $offset_query $site_query $plot_query $date_query $limit_query";
            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }

            return [];
        }else{
            $sql = "SELECT * FROM carbon_data cd LEFT JOIN photo_mannual_upload pmu ON cd.qrcode_id =  pmu.qrcode_id AND cd.site = pmu.site AND cd.plot = pmu.plot WHERE status = 'approved' $offset_query $site_query $plot_query $date_query $limit_query";
            $stmt = $db->prepare($sql);
            //echo $sql;
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
            return [];
        }
    }

    public static function countItems($projectFunder = '', $site = '', $plot = '', $date = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $site_query = '';
        $plot_query = '';
        $date_query = '';

        if (!empty($site)) {
            $site_query = "AND pmu.site = '$site'";
        }

        if (!empty($plot)) {
            $plot_query = "AND pmu.plot = $plot";
        }

        if (!empty($date)) {
            $date = $date . '%';
            $date_query = "AND pmu.upload_date LIKE '$date'";
        }

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT COUNT(*) as count FROM carbon_data cd LEFT JOIN photo_mannual_upload pmu ON cd.qrcode_id =  pmu.qrcode_id WHERE cd.funder_code IN ($placeholders) AND status = 'approved'  $site_query $plot_query $date_query";
            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch();

                return $result['count'];
            }

            return [];
        }else{
            $sql = "SELECT COUNT(*) as count FROM carbon_data cd LEFT JOIN photo_mannual_upload pmu ON cd.qrcode_id =  pmu.qrcode_id AND cd.site = pmu.site AND cd.plot = pmu.plot WHERE status = 'approved' $site_query $plot_query $date_query";
            $stmt = $db->prepare($sql);
            //echo $sql;
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch();

                return $result['count'];
            }
            return [];
        }
    }
}