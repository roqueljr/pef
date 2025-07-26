<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbh');
route('date');

use config\dbh;
use app\models\Date;

class statistics
{

    public static function user($projectFunder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT *,  COUNT(*) as trees, SUM(dbh) as total_dbh, SUM(height) as total_height, COUNT(DISTINCT site) AS sites FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved'";
            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
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
            }
        } else {
            $sql = "SELECT *, COUNT(*) as trees, SUM(dbh) as total_dbh, SUM(height) as total_height, COUNT(DISTINCT site) AS sites FROM carbon_data WHERE status = 'approved' ";
            $stmt = $db->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
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
            }
        }

        return [];
    }

    public static function plantStat($projectFunder)
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
                $results = $stmt->fetchAll();
                $tree_count = count($results);
                $alive = 0;
                $die = 0;
                $new = 0;

                foreach ($results as $row) {
                    if (strtolower($row['health']) === 'healthy') {
                        $alive++;
                    }

                    if (strtolower($row['health']) === 'for replacement') {
                        $die++;
                    }

                    if (strtolower($row['health']) === 'newly planted') {
                        $new++;
                    }
                }

                $alive_count = $alive;
                $die_count = $die;
                $new_count = $new;

                $survival = ($tree_count > 0) ? ($alive_count / $tree_count * 100) : 0;
                $mortality = ($tree_count > 0) ? ($die_count / $tree_count * 100) : 0;
                $progress = ($tree_count > 0) ? ($new_count / $tree_count * 100) : 0;

                $survival_rate = $survival < 100 ? number_format($survival, 2) : $survival;
                $mortality_rate = $mortality < 100 ? number_format($mortality, 2) : $mortality;
                $progressing_rate = $progress < 100 ? number_format($progress, 2) : $progress;

                $plantation_status = [
                    'total' => $tree_count,
                    'alive' => $alive_count,
                    'die' => $die_count,
                    'new_count' => $new_count,
                    'survival_rate' => $survival_rate,
                    'mortality_rate' => $mortality_rate,
                    'progressing_rate' => $progressing_rate  // Corrected the variable name
                ];

                return $plantation_status;
            }
        }

        // If no data found or $projectFunder is not an array
        return null;  // Or handle the case appropriately based on your application logic
    }

    public static function yearly_stat($projectFunder)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT
                year, 
                COUNT(*) AS total_trees, 
                SUM(CASE WHEN health = 'healthy' THEN 1 ELSE 0 END) AS alive,
                SUM(CASE WHEN health = 'for replacement' THEN 1 ELSE 0 END) AS die,
                SUM(CASE WHEN health = 'newly planted' THEN 1 ELSE 0 END) AS progressing,
                (SUM(dbh) / COUNT(*)) AS avg_dbh,
                (SUM(height) / COUNT(*)) AS avg_height,
                (SUM(CASE WHEN health = 'healthy' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS survival_rate,
                (SUM(CASE WHEN health = 'for replacement' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS mortality_rate,
                (SUM(CASE WHEN health = 'newly planted' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS progressing_rate

            FROM
                carbon_data WHERE funder_code IN ($placeholders)
                AND status = 'approved' 
            GROUP BY 
                year";

            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $results = $stmt->fetchAll();

                return $results;
            }
        }

        // If no data found or $projectFunder is not an array
        return null;  // Or handle the case appropriately based on your application logic
    }

    public static function annual_data($projectFunder)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT cd.dbh, cd.height, cd.site, wd.density AS w, cd.year 
                    FROM carbon_data cd 
                    LEFT JOIN wood_density wd ON cd.tree_species = wd.cname 
                    WHERE cd.funder_code IN ($placeholders) 
                    AND cd.status = 'approved' 
                    ORDER BY cd.year"; // Ensure results are sorted by year

            $stmt = $db->prepare($sql);
            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();

                $annualStats = [];

                foreach ($result as $data) {
                    $year = $data["year"];
                    $wd = $data["w"] > 0 ? $data["w"] : 0.6;
                    $biomass = carbon::chave2014($wd, $data['dbh'], $data['height']);

                    if (!isset($annualStats[$year])) {
                        $annualStats[$year] = [
                            "totalBiomass" => 0,
                            "totalTrees" => 0,
                            "totalDBH" => 0,
                            "totalHeight" => 0,
                            "sites" => []
                        ];
                    }

                    $annualStats[$year]["totalBiomass"] += $biomass;
                    $annualStats[$year]["totalTrees"] += 1;
                    $annualStats[$year]["totalDBH"] += $data['dbh'];
                    $annualStats[$year]["totalHeight"] += $data['height'];
                    $annualStats[$year]["sites"][] = $data['site'];
                }

                foreach ($annualStats as $year => &$stats) {
                    $stats["avgDBH"] = $stats["totalDBH"] / $stats["totalTrees"];
                    $stats["avgHeight"] = $stats["totalHeight"] / $stats["totalTrees"];
                    $stats["uniqueSites"] = count(array_unique($stats["sites"]));

                    $totalCarbonStored = 0.47 * $stats["totalBiomass"];
                    $totalCO2Stored = $totalCarbonStored * 3.16;

                    $stats["biomass"] = carbon::toTones($stats["totalBiomass"]);
                    $stats["carbon"] = carbon::toTones($totalCarbonStored);
                    $stats["CO2"] = carbon::toTones($totalCO2Stored);

                    unset($stats["totalDBH"], $stats["totalHeight"], $stats["totalBiomass"], $stats["sites"]);
                }

                return $annualStats;
            }
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
                } else {
                    echo 'error';
                }
                break;
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

    public static function monitoring($projectFunder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved' GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }

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
        } else {
            $sql = "SELECT CONCAT(month,' ', year) as month_year, COUNT(*) as planted_count FROM carbon_data WHERE  status = 'approved' GROUP BY month_year ORDER BY year ASC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

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

        return [];
    }

    public static function site($projectFunder = '')
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT site, COUNT(*) as tree_count FROM carbon_data WHERE funder_code IN ($placeholders) AND status = 'approved' GROUP BY site";

            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll();
                $sites = [];

                foreach ($result as $row) {
                    $sites[$row['site']] = $row['tree_count'];
                }

                return $sites;
            }
        } else {
            $sql = "SELECT site, COUNT(*) as tree_count FROM carbon_data WHERE  status = 'approved' GROUP BY site";
            $stmt = $db->prepare($sql);
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
        $dbInstance = new \config\dbh();
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

    public static function masterList($projectFunder = "")
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (is_array($projectFunder)) {
            $placeholders = implode(',', array_fill(0, count($projectFunder), '?'));

            $sql = "SELECT * FROM carbon_data c LEFT JOIN photo_mannual_upload pmu ON c.qrcode_id =  pmu.qrcode_id WHERE funder_code IN ($placeholders) AND status = 'approved' GROUP BY c.qrcode_id";
            $stmt = $db->prepare($sql);

            foreach ($projectFunder as $index => $funder) {
                $stmt->bindValue($index + 1, $funder);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        }

        if ($projectFunder) {
            $sql = "SELECT * FROM carbon_data WHERE funder_code = :funder AND status = 'approved' ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':funder', $projectFunder);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        } else {
            $sql = "SELECT * FROM carbon_data WHERE status = 'approved' ";
            $stmt = $db->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            }
        }

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
        *, recorder, 
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
}