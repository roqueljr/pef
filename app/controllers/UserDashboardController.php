<?php

namespace App\Controllers;

use App\Core\Models\View;
use App\Core\Models\Session;
use App\Core\Database\Database;

class UserDashboardController extends Database
{
    public function index()
    {
        $id = Session::get('user_id');
        $data = self::query("SELECT user_sites FROM users WHERE id = ?", [$id]);

        $site = $data[0]['user_sites'] ?? null;

        $sql = "SELECT * FROM carbon_data WHERE site = ?";
        $params = ['ADCF'];
        $data = self::query($sql, $params);

        $count = count($data);

        // echo json_encode($data);

        View::render('user/home', [
            'username' => Session::get('user_name') ? ucfirst(Session::get('user_name')) : null,
            'dp' => '/0/assets/main_profile.png',
            'role' => Session::get('user_role') ? strtolower(Session::get('user_role')) : null,
            'org' => Session::get('user_org'),
            'total' => number_format($count),
        ]);
    }

    public function settings()
    {
        echo 'settings';
    }

    public function userDashboard($site)
    {
        $permissions = Session::get('user_pmn');
        $permissions = $permissions ? json_decode($permissions, true) : null;

        $username = Session::get('user_name');
        $username = $username ? ucfirst($username) : null;
        $role = Session::get('user_role');
        $role = $role ? strtolower($role) : null;

        switch ($site) {
            case 'rrg':
                $data = [
                    'username' => $username,
                    'dp' => '/0/assets/main_profile.png',
                    'role' => $role,
                    'total' => 1000000,
                    'srate' => 80,
                    'mrate' => 20,
                    'tsite' => 5,
                    'avgDBH' => 10,
                    'avgHeight' => 11,
                    'tAgb' => 272,
                    'tc' => 276,
                    'tco2' => 286
                ];
                break;
            default:
                $data = [];
                break;
        }

        $path = $site . '/home';
        View::render($path, $data);
    }
}
