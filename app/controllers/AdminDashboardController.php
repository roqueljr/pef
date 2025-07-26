<?php

namespace App\Controllers;

use App\Core\Models\View;
use App\Core\Models\Route;
use App\Core\Models\Session;
use App\Core\Database\Database;

class AdminDashboardController extends Database
{
    public function index()
    {
        View::render('admin/home', [
            'title' => 'PEF-CSD | Admin Dashboard',
            'pageTitle' => 'Admin Dashboard',
            'userName' => Session::get('user_name'),
            'org' => Session::get('user_org'),
            'clients' => [
                [
                    'name' => 'Reduce Reuse Regrow',
                    'logo' => Route::assets('rrg_logo.svg', false),
                    'description' => 'Reduce Reuse Grow is empowering enterprise-level clients to become the world\'s most responsible companies',
                    'path' => '/admin/manage/rrg'
                ],
                [
                    'name' => 'Ayala Carbon Forest',
                    'logo' => Route::assets('ayala_logo.png', false),
                    'description' => 'Developing businesses that transform industries, challenging the status quo, and bringing innovations here in the Philippines and abroad that contribute to the nation\'s social and economic agenda.',
                    'path' => '/admin/manage/ayala'
                ],
                [
                    'name' => 'FDC Utilities, Inc.',
                    'logo' => Route::assets('fdcui_logo.jpg', false),
                    'description' => 'Harnessing Energy. Empowering Communities.',
                    'path' => '/admin/manage/fdcui'
                ]
            ],
            'apps' => [
                [
                    'name' => 'QR Code Manager',
                    'logo' => Route::assets('qr3tagger_logo.svg', false),
                    'description' => 'Manage and generate QR codes',
                    'path' => 'https://www.qrscanner.pefcarbonsink.info/qrcodeManager'
                ],
                [
                    'name' => 'QR3Tagger',
                    'logo' => Route::assets('qr3tagger_logo.svg', false),
                    'description' => 'Use to record planting progress and tree monitoring for individual trees',
                    'path' => 'https://www.qrscanner.pefcarbonsink.info/offline_mode/'
                ],
                [
                    'name' => 'n3recorder',
                    'logo' => Route::assets('n3recorder_logo.jpg', false),
                    'description' => 'Use to record nursery updates and acitivities',
                    'path' => 'https://www.nursery.pefcarbonsink.info/'
                ],
                [
                    'name' => 'Kanboard',
                    'logo' => Route::assets('kanboard_logo.png', false),
                    'description' => 'Use this platform to manage your productivity. Work with tasks inside project boards to track comments, files and activities.',
                    'path' => 'https://www.kb.pefcarbonsink.com'
                ]
            ],
            'websites' => [
                [
                    'name' => 'Threemilliontrees Website',
                    'logo' => Route::assets('3mtrees.webp', false),
                    'description' => 'Website for tree adoption',
                    'path' => 'https://www.plant.pefcarbonsink.com'
                ]
            ],
            'navLink' => [
                'dasboard' => '/admin/dashboard',
                'settings' => '/admin/settings',
                'profile' => '/admin/profile'
            ]
        ]);
    }

    public function settings()
    {
        View::render('admin/settings', [
            'title' => 'PEF-CSD | Settings',
            'pageTitle' => 'Settings',
            'userName' => null,
            'navLink' => [
                'dasboard' => '/admin/dashboard',
                'settings' => '/admin/settings',
                'profile' => '/admin/profile'
            ]
        ]);
    }

    public function profile()
    {
        View::render('admin/profile', [
            'title' => 'PEF-CSD | Profile',
            'pageTitle' => 'Profile',
            'userName' => null,
            'navLink' => [
                'dasboard' => '/admin/dashboard',
                'settings' => '/admin/settings',
                'profile' => '/admin/profile'
            ]
        ]);
    }

    public function page($site, $page)
    {
        $userId = Session::get('user_id');
        $username = Session::get('user_name');
        $username = $username ? ucfirst($username) : null;
        $role = Session::get('user_role');
        $role = $role ? strtolower($role) : null;
        $dp = self::query('SELECT user_dp FROM users WHERE id = ?', [$userId]);
        $dp = $dp ? $dp : '/0/assets/main_profile.png';
        $dp = '/0/assets/main_profile.png';

        $data = [
            'username' => $username,
            'dp' => $dp,
            'role' => $role
        ];

        View::render("$site/$page", $data);
    }

    public function dashboardManager($site)
    {
        $userId = Session::get('user_id');
        $username = Session::get('user_name');
        $username = $username ? ucfirst($username) : null;
        $role = Session::get('user_role');
        $role = $role ? strtolower($role) : null;
        $dp = self::query('SELECT user_dp FROM users WHERE id = ?', [$userId]);
        $dp = $dp ? $dp : '/0/assets/main_profile.png';
        $dp = '/0/assets/main_profile.png';

        switch ($site) {
            case 'rrg':
                $rrgData = $this->rrg();
                $data = [
                    'username' => $username,
                    'dp' => $dp,
                    'role' => $role
                ];
                $data = array_merge($rrgData, $data);
                break;
            default:
                $data = [];
                break;
        }

        $path = $site . '/home';
        View::render($path, $data);
    }

    private function rrg()
    {
        $sql = "SELECT SUM(total) AS planted FROM refo_planting_schedule WHERE status = 'Done'";
        $planted = self::query($sql, [], 1)[0];
        $total = number_format($planted['planted']);

        $sql = "SELECT *, SUM(survival) AS sTotal, SUM(mortality) AS mTotal FROM refo_monitoring_records";
        $mtng = self::query($sql, [], 1)[0];
        $surv = $mtng['sTotal'];
        $mort = $mtng['mTotal'];
        $pl_total = $surv + $mort;
        $srate = ($pl_total > 0) ? ceil(($surv / $pl_total) * 100) : 0;
        $mrate = ($pl_total > 0) ? ceil(($mort / $pl_total) * 100) : 0;

        //temporary data
        $tsite = 7;
        $avgDBH = 0.5;
        $avgHeight = 1;
        $tAgb = 0.0102 * $planted['planted'];
        $tc = number_format(($tAgb * 0.47), 3);
        $tco2 = number_format(($tc * 3.12), 3);

        return [
            'total' => $total,
            'srate' => $srate,
            'mrate' => $mrate,
            'tsite' => $tsite,
            'avgDBH' => $avgDBH,
            'avgHeight' => $avgHeight,
            'tAgb' => $tAgb,
            'tc' => $tc,
            'tco2' => $tco2
        ];
    }
}
