<?php

/**
 * Author: Rey Mark Balaod
 * Under Philippine Eagle Foundation ©️ 2025
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './app/autoload.php';

use App\Core\Models\View;
use App\Core\Models\Route;
use App\Core\Models\Session;
use App\Controllers\AuthController;
use App\Controllers\UserDashboardController;
use App\Controllers\AdminDashboardController;

class_alias(Route::class, 'Route');

View::setPath('resources/views');

Route::post('/loging-in', [AuthController::class, 'login'])->name('login');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [AuthController::class, 'check']);

Route::group(
    '/admin',
    ['superadmin'],
    function () {
        Route::get('/', [AdminDashboardController::class, 'index']);
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin_settings');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin_profile');
    }
);

Route::group(
    '/admin/manage',
    ['superadmin'],
    function () {
        Route::get('/', function () {
            Route::redirect('/admin');
        });
        Route::get('/{site}', [AdminDashboardController::class, 'dashboardManager'])->name('siteHome');
        Route::get('/{site}/{page}', [AdminDashboardController::class, 'page']);
    }
);

Route::group(
    '/moderator',
    ['admin'],
    function () {
        Route::get('/{site}', [UserDashboardController::class, 'dashboardManager']);
    }
);

Route::group(
    '/user',
    ['user'],
    function () {
        Route::get('/', [UserDashboardController::class, 'index'])->name('user_home');
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user_dashboard');
    }
);

Route::dispatch();
