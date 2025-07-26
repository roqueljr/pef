<?php

namespace app\models;

use config\dbh;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn');


class analyst
{
    public static function findMissingNumbers($site, $plot)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $min_max = "SELECT MIN(qrcode_id) AS min, MAX(qrcode_id) AS max FROM carbon_data WHERE site = :site AND plot = :plot";

        $stmt = $db->prepare($min_max);
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':plot', $plot);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $min_max = $stmt->fetch();

            $minimum = $min_max['min'];
            $maximum = $min_max['max'];

            $data = [
                'min' => $minimum,
                'max' => $maximum,
                'qrcodes' => [],
                'missing' => [],
                'checking' => []
            ];

            $qrcodes = "SELECT qrcode_id FROM carbon_data WHERE site = :site AND plot = :plot";
            $stmt1 = $db->prepare($qrcodes);
            $stmt1->bindParam(':site', $site);
            $stmt1->bindParam(':plot', $plot);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                $qrcodes = $stmt1->fetchAll();

                $qrcodes1 = "SELECT qrcode_id FROM carbon_data WHERE site = :site AND plot = :plot AND status = 'checking...'";
                $stmt2 = $db->prepare($qrcodes1);
                $stmt2->bindParam(':site', $site);
                $stmt2->bindParam(':plot', $plot);
                $stmt2->execute();
                if ($stmt2->rowCount() > 0) {
                    $checking = $stmt2->fetchAll();
                } else {
                    $checking = [];
                }

                foreach ($checking as $row) {
                    $data['checking'][] = $row['qrcode_id'];
                }

            } else {
                $qrcodes = [];
            }

            foreach ($qrcodes as $qrcode) {
                $data['qrcodes'][] = $qrcode['qrcode_id'];
            }

            for ($i = $minimum; $i <= $maximum; $i++) {
                if (!in_array($i, array_column($qrcodes, 'qrcode_id'))) {
                    $data['missing'][] = $i;
                }
            }

            return $data;

        } else {
            return [];
        }
    }
}