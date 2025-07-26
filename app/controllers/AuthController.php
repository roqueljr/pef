<?php

namespace App\Controllers;

use App\Core\Models\View;
use App\Core\Models\Route;
use App\Core\Models\Session;
use App\Core\Database\Database;

class AuthController
{
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validation = "SELECT * FROM users WHERE user_email = :user AND user_account_status = 'Active'";
        } else {
            $validation = "SELECT * FROM users WHERE user_name = :user AND user_account_status = 'Active'";
        }

        $user = Database::query($validation, ['user' => $email]);
        $user = $user[0] ?? null;
        $role = strtolower($user['user_role']);

        if (!$user || !password_verify($password, $user['user_pass'])) {
            Route::redirect('/');
            return;
        }

        $specificUsers = [
            'tatianasarigumba',
            'RRG_REGAIN'
        ];

        if (in_array($user['user_name'], $specificUsers)) {
            Route::redirect('/0/rrg');
            return;
        }

        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['user_role']);
        Session::set('user_name', $user['user_name']);
        Session::set('user_pmn', $user['user_permissions']);
        Session::set('user_org', $user['user_org']);


        Route::redirect('/');
    }

    public function check()
    {
        $role = Session::get('user_role');
        $org = Session::get('org');
        $role = $role ? strtolower($role) : null;

        switch ($role) {
            case 'superadmin':
                Route::redirect('/admin/dashboard');
                break;
            case 'admin':
                Route::redirect('/moderator/dashboard');
                break;
            case 'user':
                Route::redirect('/user/dashboard');
                break;
            default:
                View::render('auth/login');
                break;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function register()
    {
        // Render the registration view
        View::render('auth/register');
    }
}