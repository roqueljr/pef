<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn');
route('date');

use config\dbh;

class download extends dbh
{
    public static function CSV($arrays): string|false
    {
        ob_start();
        $file = fopen("php://output", "w");
        foreach ($arrays as $array) {
            fputcsv($file, $array);
        }
        fclose($file);
        return ob_get_clean();
    }
}