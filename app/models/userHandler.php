<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbh');
route('date');
route('carbon');

use config\dbh;
use app\models\Date;
use app\models\carbon;

class userHandler
{
    public static function user($funder_codes, $sites)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT cd.dbh, cd.height, cd.site, wd.density AS w FROM carbon_data cd LEFT JOIN wood_density wd ON cd.tree_species = wd.cname WHERE cd.funder_code IN ($funder_codes) AND cd.site IN ($sites) AND cd.status = 'approved'";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();

            $totalBiomass = 0;
            $totalTrees = count($result);
            $totalDBH = 0;
            $totalHeight = 0;
            $sites = [];

            foreach ($result as $data) {
                $wd = $data["w"] > 0 ? $data["w"] : 0.6;
                //$biomass = carbon::chave2005($data['dbh']);
                $biomass = carbon::chave2014($wd, $data['dbh'], $data['height']);
                $totalBiomass += $biomass;
                $totalDBH += $data['dbh'];
                $totalHeight += $data['height'];
                $sites[] = $data['site'];
            }

            $site = array_unique($sites);

            $avgDBH = $totalDBH / $totalTrees;
            $avgH = $totalHeight / $totalTrees;

            $TotalCarbonStored = 0.47 * $totalBiomass;
            $TotalCO2Stored = $TotalCarbonStored * 3.16;

            $biomass = carbon::toTones($totalBiomass);
            $carbon = carbon::toTones($TotalCarbonStored);
            $CO = carbon::toTones($TotalCO2Stored);


            $statistics = [$totalTrees, $avgDBH, $avgH, $biomass, $carbon, $CO, count($site)];

            return $statistics;
        }

        return [];
    }

    public static function treeStat($site = '', $case = '', $funder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        switch ($case) {
            case 1:
                $sql = "SELECT COUNT(*) as trees, SUM(dbh) as total_dbh, SUM(height) as total_height, COUNT(DISTINCT site) AS sites FROM carbon_data WHERE status = :status AND funder_code = :funder ";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->bindParam(':funder', $funder, \PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $row = $stmt->fetch();
                    $totalTrees = $row['trees'];
                    $totalDBH = $row['total_dbh'];
                    $tatalH = $row['total_height'];
                    $siteCount = $row['sites'];

                    $avgDBH = $totalDBH / $totalTrees;
                    $avgH = $tatalH / $totalTrees;

                    $averageBiomass = exp(-2.134 + 2.530 * log($avgDBH));
                    $averageCarbonStored = 0.47 * $averageBiomass;
                    $averageCO2Stored = $averageCarbonStored * 44 / 12;

                    $statistics = [$totalTrees, $avgDBH, $avgH, $averageBiomass, $averageCarbonStored, $averageCO2Stored, $siteCount];

                    return $statistics;
                } else {
                    echo 'error';
                }

                break;

            case 2:
                $sql = "SELECT COUNT(*) as trees, SUM(dbh) as total_dbh, SUM(height) as total_height FROM carbon_data WHERE site = :site AND status = :status";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':site', $site, \PDO::PARAM_STR);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $row = $stmt->fetch();
                    $totalTrees = $row['trees'];
                    $totalDBH = $row['total_dbh'];
                    $tatalH = $row['total_height'];

                    $avgDBH = $totalDBH / $totalTrees;
                    $avgH = $tatalH / $totalTrees;

                    $averageBiomass = exp(-2.134 + 2.530 * log($avgDBH));
                    $averageCarbonStored = 0.47 * $averageBiomass;
                    $averageCO2Stored = $averageCarbonStored * 44 / 12;

                    $statistics = [$totalTrees, $avgDBH, $avgH, $averageBiomass, $averageCarbonStored, $averageCO2Stored];

                    return $statistics;
                } else {
                    echo 'error';
                }
                break;
            default:
                $sql = "SELECT COUNT(DISTINCT site) as site, COUNT(*) as trees, SUM(dbh) as total_dbh, SUM(height) as total_height FROM carbon_data WHERE status = :status";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $row = $stmt->fetch();
                    $sites = $row['site'];
                    $totalTrees = $row['trees'];
                    $totalDBH = $row['total_dbh'];
                    $tatalH = $row['total_height'];

                    $avgDBH = $totalDBH / $totalTrees;
                    $avgH = $tatalH / $totalTrees;

                    $averageBiomass = exp(-2.134 + 2.530 * log($avgDBH));
                    $averageCarbonStored = 0.47 * $averageBiomass;
                    $averageCO2Stored = $averageCarbonStored * 44 / 12;

                    $statistics = [$totalTrees, $avgDBH, $avgH, $averageBiomass, $averageCarbonStored, $averageCO2Stored, $sites];

                    return $statistics;
                }

                return [];

        }
    }

    public static function plantingGraph($case = '', $funder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        switch ($case) {
            case 1:
                $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE status = :status AND funder_code = :funder GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->bindParam(':funder', $funder, \PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll();
                    $planting = [];

                    foreach ($result as $row) {
                        $planting[$row['month_year']] = $row['planted_count'];
                    }

                    return $planting;
                } else {
                    error_log('No data found!');
                    return [];
                }


            default:
                $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE status = :status GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll();
                    $planting = [];

                    foreach ($result as $row) {
                        $planting[$row['month_year']] = $row['planted_count'];
                    }

                    return $planting;
                } else {
                    error_log('No data found!');
                    return [];
                }


        }
    }

    public static function monitoring($funder_codes, $sites)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE funder_code IN ($funder_codes) AND site IN ($sites) AND status = 'approved' GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $planting = [];

            foreach ($result as $row) {
                $planting[$row['month_year']] = $row['planted_count'];
            }

            return $planting;
        } else {
            error_log('No data found!');
            return [];
        }
    }

    public static function site($funder_codes, $sites)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT site, COUNT(*) as tree_count FROM carbon_data WHERE funder_code IN ($funder_codes) AND site IN ($sites) AND status = 'approved' GROUP BY site";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $sites = [];

            foreach ($result as $row) {
                $sites[$row['site']] = $row['tree_count'];
            }

            return $sites;
        }
    }

    public static function siteData($case = '', $funder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        switch ($case) {
            case 1:
                $sql = "SELECT site, COUNT(*) as tree_count FROM carbon_data WHERE  status = :status AND funder_code = :funder GROUP BY site";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->bindParam(':funder', $funder, \PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll();
                    $sites = [];

                    foreach ($result as $row) {
                        $sites[$row['site']] = $row['tree_count'];
                    }

                    return $sites;
                } else {
                    error_log('no data found!');
                    return [];
                }


            case 2:
                $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE status = :status GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll();
                    $planting = [];

                    foreach ($result as $row) {
                        $planting[$row['month_year']] = $row['planted_count'];
                    }

                    return $planting;
                } else {
                    error_log('No data found!');
                    return [];
                }

            default:
                $sql = "SELECT site, COUNT(*) as tree_count FROM carbon_data WHERE  status = :status GROUP BY site";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll();
                    $sites = [];

                    foreach ($result as $row) {
                        $sites[$row['site']] = $row['tree_count'];
                    }

                    return $sites;
                } else {
                    error_log('no data found!');
                    return [];
                }

        }
    }

    public static function recorder(): array
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        $sql = "SELECT CONCAT(day,'/',
         CASE 
           WHEN month = 'January' THEN '01'
           WHEN month = 'February' THEN '02'
           WHEN month = 'March' THEN '03'
           WHEN month = 'April' THEN '04'
           WHEN month = 'May' THEN '05'
           WHEN month = 'June' THEN '06'
           WHEN month = 'July' THEN '07'
           WHEN month = 'August' THEN '08'
           WHEN month = 'September' THEN '09'
           WHEN month = 'October' THEN '10'
           WHEN month = 'November' THEN '11'
           WHEN month = 'December' THEN '12'
           ELSE month
         END,
         '/', SUBSTRING(year, 3, 2)) AS date, recorder, site, COUNT(*) as data_entry_count, day, month, year FROM carbon_data WHERE status = :status GROUP BY date,site, recorder, month ORDER BY date DESC, year DESC, day DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $sites = [];

            $date = Date::Now();
            $year = $date['year'];
            $month = $date['month'];
            $day = $date['day'];

            foreach ($result as $row) {

                $sites[] = [
                    'name' => $row['recorder'],
                    'site' => $row['site'],
                    'data_entry_count' => $row['data_entry_count'],
                    'date' => $row['date'],
                    'dateWord' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                ];
            }
            return $sites;
        } else {
            error_log('no data found!');
            return [];
        }
    }

    public static function recorder_update($projectFunder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT *, CONCAT(day,'/',
            CASE 
                WHEN month = 'January' THEN '01'
                WHEN month = 'February' THEN '02'
                WHEN month = 'March' THEN '03'
                WHEN month = 'April' THEN '04'
                WHEN month = 'May' THEN '05'
                WHEN month = 'June' THEN '06'
                WHEN month = 'July' THEN '07'
                WHEN month = 'August' THEN '08'
                WHEN month = 'September' THEN '09'
                WHEN month = 'October' THEN '10'
                WHEN month = 'November' THEN '11'
                WHEN month = 'December' THEN '12'
                ELSE month
                END,
         '/', SUBSTRING(year, 3, 2)) AS date, recorder, site, COUNT(*) as data_entry_count, day, month, year FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'Checking...'  GROUP BY date,site, recorder, month ORDER BY date DESC, year DESC, day DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') DESC";

            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();
                $sites = [];

                $date = Date::Now();
                $year = $date['year'];
                $month = $date['month'];
                $day = $date['day'];

                foreach ($result as $row) {

                    $sites[] = [
                        'name' => $row['recorder'],
                        'funder' => $row['funder_code'],
                        'site' => $row['site'],
                        'data_entry_count' => $row['data_entry_count'],
                        'date' => $row['date'],
                        'dateWord' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                    ];
                }
                return $sites;
            } else {
                error_log('No data found!');
                return [];
            }
        } else {

            $sql = "SELECT *, CONCAT(day,'/',
            CASE 
                WHEN month = 'January' THEN '01'
                WHEN month = 'February' THEN '02'
                WHEN month = 'March' THEN '03'
                WHEN month = 'April' THEN '04'
                WHEN month = 'May' THEN '05'
                WHEN month = 'June' THEN '06'
                WHEN month = 'July' THEN '07'
                WHEN month = 'August' THEN '08'
                WHEN month = 'September' THEN '09'
                WHEN month = 'October' THEN '10'
                WHEN month = 'November' THEN '11'
                WHEN month = 'December' THEN '12'
                ELSE month
                END,
            '/', SUBSTRING(year, 3, 2)) AS date, recorder, site, COUNT(*) as data_entry_count, day, month, year FROM carbon_data WHERE status = 'Checking...' GROUP BY date,site, recorder, month ORDER BY id DESC, date DESC, year DESC, day DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();
                $sites = [];

                $date = Date::Now();
                $year = $date['year'];
                $month = $date['month'];
                $day = $date['day'];

                foreach ($result as $row) {

                    $sites[] = [
                        'name' => $row['recorder'],
                        'funder' => $row['funder_code'],
                        'site' => $row['site'],
                        'data_entry_count' => $row['data_entry_count'],
                        'date' => $row['date'],
                        'dateWord' => $row['month'] . ' ' . $row['day'] . ', ' . $row['year'],
                    ];
                }
                return $sites;
            } else {
                error_log('no data found!');
                return [];
            }
        }
    }


    public static function treeHealth()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        $sql = "SELECT CONCAT(day,'/',
         CASE 
           WHEN month = 'January' THEN '01'
           WHEN month = 'February' THEN '02'
           WHEN month = 'March' THEN '03'
           WHEN month = 'April' THEN '04'
           WHEN month = 'May' THEN '05'
           WHEN month = 'June' THEN '06'
           WHEN month = 'July' THEN '07'
           WHEN month = 'August' THEN '08'
           WHEN month = 'September' THEN '09'
           WHEN month = 'October' THEN '10'
           WHEN month = 'November' THEN '11'
           WHEN month = 'December' THEN '12'
           ELSE month
         END,
         '/', SUBSTRING(year, 3, 2)) AS date, health, site, COUNT(*) as health_count, day, month, year FROM carbon_data WHERE status = :status GROUP BY health, site, date ORDER BY id DESC, year DESC, day DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $health = [];

            foreach ($result as $row) {

                $health[] = [
                    'area' => $row['site'],
                    'health' => $row['health'],
                    'health_count' => $row['health_count'],
                    'date' => $row['date'],
                    'dateWord' => $row['day'] . ' ' . $row['month'] . ', ' . $row['year'],
                ];
            }
            return $health;
        } else {
            return [];
        }
    }

    public static function treeCount($site)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'approved';

        $sql = "SELECT COUNT(*) as tree_count FROM carbon_data WHERE status = :status AND site = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->bindParam(':site', $site, \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            return number_format($result['tree_count']);
        } else {
            error_log('no data found!');
            return 'To be planted';
        }
    }

    public static function approval()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'Checking...';

        $sql = "SELECT CONCAT(day,'/',
         CASE 
           WHEN month = 'January' THEN '01'
           WHEN month = 'February' THEN '02'
           WHEN month = 'March' THEN '03'
           WHEN month = 'April' THEN '04'
           WHEN month = 'May' THEN '05'
           WHEN month = 'June' THEN '06'
           WHEN month = 'July' THEN '07'
           WHEN month = 'August' THEN '08'
           WHEN month = 'September' THEN '09'
           WHEN month = 'October' THEN '10'
           WHEN month = 'November' THEN '11'
           WHEN month = 'December' THEN '12'
           ELSE month
         END,
         '/', SUBSTRING(year, 3, 2)) AS date, status, qrcode_id, site, recorder, COUNT(*) as entry_count, day, month, year  FROM carbon_data WHERE status = :status GROUP BY recorder, site, date ORDER BY id DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
         day, year";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $info = [];

            foreach ($result as $row) {

                $info[] = [
                    'name' => $row['recorder'],
                    'site' => $row['site'],
                    'status' => $row['status'],
                    'count' => $row['entry_count'],
                    'qrcodeId' => $row['qrcode_id'],
                    'date' => $row['date'],
                    'dateWord' => $row['day'] . ' ' . $row['month'] . ' ' . $row['year'],
                ];
            }

            return $info;
        }
    }

    public static function notApproveCount()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $status = 'Checking...';

        $sql = "SELECT COUNT(*) as total FROM carbon_data WHERE status = :status ORDER BY id DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            return $result['total'];
        }

        return 0;
    }

    public static function masterList($projectFunder)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT * FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved'";

            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        }

        return [];
    }

    public static function masterList_query($projectFunder, $limit = '', $start = 0, $searchValue = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder) && count($projectFunder) > 0) {
            // Create placeholders for funder codes
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            // Base SQL query
            $sql = "SELECT * FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved'";

            // Add search condition if provided
            if (!empty($searchValue)) {
                $sql .= " AND (tree_species LIKE ? OR site LIKE ? OR month LIKE ? OR recorder LIKE ? OR year LIKE ?)";
            }

            if ($limit || $start) {
                // Add LIMIT and OFFSET
                $sql .= " LIMIT $start, $limit";
            }

            // Prepare the statement
            $stmt = $db->prepare($sql);

            // Build the parameters array
            $params = array_merge($projectFunder);

            // Add search value parameters if search is provided
            if (!empty($searchValue)) {
                $searchTerm = '%' . $searchValue . '%';
                for ($i = 0; $i < 5; $i++) {
                    $params[] = $searchTerm;
                }
            }

            // Bind the parameters dynamically
            $stmt->execute($params);

            // Fetch results
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        }

        return [];
    }


    public static function paging($array, $pageNumber = 1, $itemsPerPage = 100)
    {
        $offset = ($pageNumber - 1) * $itemsPerPage;
        return array_slice($array, $offset, $itemsPerPage);
    }

    public static function getPhoto($qrcode_id, $funder, $plot, $site)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT file_name FROM photo_mannual_upload WHERE qrcode_id = :qrcode AND funder = :funder AND plot = :plot AND site = :site";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':qrcode', $qrcode_id);
        $stmt->bindParam(':funder', $funder);
        $stmt->bindParam(':plot', $plot);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public static function query($site = '', $start = '', $limit = '', $date = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT * FROM carbon_data WHERE site = :site";

        if ($date) {
            $sql .= " AND dateTime LIKE :date";
        }

        if ($start !== '' && $limit !== '') {
            $sql .= " LIMIT :limit OFFSET :start";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);

        if ($date) {
            $date = $date . '%';
            $stmt->bindParam(':date', $date);
        }

        if ($start !== '' && $limit !== '') {
            // Directly inject LIMIT and OFFSET values into the SQL string to avoid SQL syntax errors
            $sql = str_replace(':limit', (int) $limit, $sql);
            $sql = str_replace(':start', (int) $start, $sql);
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':site', $site);
            if ($date) {
                $stmt->bindParam(':date', $date);
            }
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public static function statistics()
    {
        $planting = self::plantingGraph();
        $sites = self::siteData();
        $recorder = self::recorder();
        $health = self::treeHealth();
        $forApproval = self::approval();
        $NotApproveCount = self::notApproveCount();

        return [
            'health' => $planting,
            'sites' => $sites,
            'recorder' => $recorder,
            'tree_health_per_site' => $health,
            'approval' => $forApproval,
            'total_for_approval' => $NotApproveCount,
        ];
    }

    public static function getData()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $org = $_SESSION['funder'];

        if (!empty($org)) {

            if ($org === 'admin') {
                $treeStat = self::treeStat();
                $platingGraph = self::plantingGraph();
                $siteGraph = self::siteData();
            } else {
                $treeStat = self::treeStat(1, $org);
                $platingGraph = self::plantingGraph(1, $org);
                $siteGraph = self::siteData(1, $org);
            }

            $data = [
                'tree_status' => $treeStat,
                'planting_graph' => $platingGraph,
                'site_graph' => $siteGraph
            ];

            return $data;
        } else {
            redirectJs('location: /login/logout');
            exit();
        }
    }

    public static function fetch($url)
    {
        $ch = curl_init($url);

        // Set options for cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Set to true for POST request

        // Set the HTTP headers
        $headers = array(
            'Authorization: Bearer PEF_DB_ADMIN', // Replace YOUR_TOKEN_HERE with your actual token
            'Content-Type: application/json' // Assuming JSON data is being sent
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the cURL request
        $jsonData = curl_exec($ch);

        // Check for errors
        if ($jsonData === false) {
            die('Error fetching data from API: ' . curl_error($ch));
        }

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        return json_decode($jsonData, true);
    }

    public static function nurseryClass()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(4);

        $sql = "SELECT 
        *,
        COUNT(status) AS count,
        DATE(date) AS date_only
        FROM 
            nursery_monitoring
        WHERE 
            validation = 'approved'
        GROUP BY 
        status, recorder, DATE(date)
        ORDER BY
           id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public static function nurseryStatistics()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(4);

        $sql = "SELECT 
        COUNT(*) AS count, 
        SUM(CASE WHEN method = 'wildlings' THEN 1 ELSE 0 END) AS wildlings, 
        SUM(CASE WHEN method = 'seeds' THEN 1 ELSE 0 END) AS seeds, 
        SUM(CASE WHEN status = 'Hardening off' THEN 1 ELSE 0 END) AS planting, 
        SUM(CASE WHEN method = 'Cutting' THEN 1 ELSE 0 END) AS cuttings, 
        SUM(CASE WHEN status = 'Released' THEN 1 ELSE 0 END) AS released, 
        COUNT(DISTINCT cn) AS species 
        FROM 
        nursery_monitoring 
        WHERE 
        validation = 'approved'";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    /**
     * this filtering the whole sytem to a certain data base of site and funder
     * @param $site specific site to filter
     * @param $funder associat$projectFunder with funder
     * @return array|null
     */
    public static function carbon_data($site, $funder = "")
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT * FROM carbon_data WHERE site = :site";

        if ($funder) {
            $sql .= " AND funder_code = '$funder' ";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':site', $site);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
    }

    /**
     * this is automated calculation of trees per species that also subjective admin allowed access for user
     * @param $projectFunder array of allowed organization to access their scope of data
     * @param $site optional filter by site
     * @return array|null
     */
    public static function countPerTreeSpecies($projectFunder, $sites)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT COUNT(*) AS count, tree_species FROM carbon_data WHERE funder_code IN ($projectFunder) AND site IN ($sites) AND status = 'approved' GROUP BY tree_species";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dominantSpecies = [];
            $results = $stmt->fetchAll();

            // Step 1: Filter the array to only include elements with count >= 50
            $filteredResults = array_filter($results, function ($row) {
                return $row['count'] > 0;
            });

            // Step 2: Sort the filtered array by the count property in descending order
            usort($filteredResults, function ($a, $b) {
                return $b['count'] - $a['count'];
            });

            // Step 3: Slice the sorted array to get the top 20 elements
            $top20Results = array_slice($filteredResults, 0, 10);

            // Step 4: Create the $dominantSpecies array
            $dominantSpecies = [];
            foreach ($top20Results as $row) {
                $dominantSpecies[] = [
                    'species' => $row['tree_species'],
                    'count' => $row['count']
                ];
            }

            return $dominantSpecies;
        }
    }

    public static function onlineUsers()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT user_name FROM users WHERE status = 'online' ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public static function updateUserStatus($state, $id)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);
        $status = $state ? 'online' : 'offline';
        $sql = "UPDATE users SET status = :state WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':state', $status);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


    /**
     * this is the dynamic list of sites base on the organizational access
     * @param $funder_codes get from the user set access controled by admin
     * @return array
     */
    public static function listOfSite($funder_codes)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($funder_codes)) {
            $placeholders = implode(',', array_fill(0, count($funder_codes), '?'));

            $sql = "SELECT DISTINCT(site) AS sites FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved'";

            $stmt = $db->prepare($sql);

            foreach ($funder_codes as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            //echo $sql;
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        }

        return [];
    }
}