<?php

namespace app\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('dbconn');
route('session');
route('date');

use config\dbh;
use app\models\session;
use app\models\Date;

class login
{

    public static function userLogin($userOrEmail, $password, $auth)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        if (!empty($auth)) {
            $url = base64_decode($auth);
            $redirect = $url;
        }

        if (filter_var($userOrEmail, FILTER_VALIDATE_EMAIL)) {
            // It's an email
            $validation = "SELECT user_name, user_pass, role, access, organization FROM users WHERE user_email = :email";
            $stmt = $db->prepare($validation);
            $stmt->bindParam(':email', $userOrEmail, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $userPass = $row['user_pass'];
                $role = $row['role'];
                $username = $row['user_name'];
                $access = $row['access'];
                $funder_code = $row['organization'];

                if (password_verify($password, $userPass)) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['userName'] = $username;
                    $_SESSION['role'] = $role;
                    $_SESSION['funder'] = $funder_code;

                    $status = 'online';

                    $sql = "UPDATE users SET status = :status WHERE user_name = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                    $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
                    if ($stmt->execute()) {

                        if (empty($auth)) {
                            self::access($access);
                        } else {
                            redirectJs($redirect);
                        }
                    } else {
                        echo '<script>alert(\'error!\');</script>';
                    }
                } else {
                    echo '<script>alert(\'Invalid password!\');</script>';
                }
            } else {
                echo '<script>alert(\'Invalid user!\');</script>';
            }
        } else {
            // It's a username
            $validation = "SELECT user_name, user_pass, role, access, organization FROM users WHERE user_name = :username";
            $stmt = $db->prepare($validation);
            $stmt->bindParam(':username', $userOrEmail, \PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $userPass = $row['user_pass'];
                $role = $row['role'];
                $username = $row['user_name'];
                $access = $row['access'];
                $funder_code = $row['organization'];

                if (password_verify($password, $userPass)) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['userName'] = $username;
                    $_SESSION['role'] = $role;
                    $_SESSION['funder'] = $funder_code;
                    $status = 'online';

                    $sql = "UPDATE users SET status = :status WHERE user_name = :username";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
                    $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
                    if ($stmt->execute()) {

                        if (empty($auth)) {
                            self::access($access);
                        } else {
                            redirectJs($redirect);
                        }
                    } else {
                        echo '<script>alert(\'error!\');</script>';
                    }
                } else {
                    echo '<script>alert(\'Invalid password!\');</script>';
                }
            } else {
                echo '<script>alert(\'Invalid user!\');</script>';
            }
        }
    }

    public static function login($password, $emailOrUsername)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);
        $date = Date::dateTimeToday();

        if (filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
            $validation = "SELECT * FROM users WHERE user_email = :user AND account_status = 'Active'";
        } else {
            $validation = "SELECT * FROM users WHERE user_name = :user AND account_status = 'Active'";
        }

        $stmt = $db->prepare($validation);
        $stmt->bindParam(':user', $emailOrUsername);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $userPass = $row['user_pass'];

            if (password_verify($password, $userPass)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                    session_regenerate_id(true);
                }
                $_SESSION['userName'] = $row['user_name'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['funder'] = $row['organization'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['user_email'];
                $_SESSION['access'] = $row['access'];
                $_SESSION['roque'] = 'roque';
                $_SESSION['userInfo'] = [
                    'user_id' => $row['id'],
                    'user_name' => $row['user_name'],
                    'user_email' => $row['user_email'],
                    'user_status' => $row['account_status'],
                    'user_role' => $row['role'],
                    'user_access' => $row['access'],
                    'user_org' => $row['organization'],
                    'user_sites' => $row['sites'],
                    'user_panel' => $row['user_panel'],
                    'user_mainOrg' => $row['mainOrg']
                ];

                $status = 'online';

                $sql = "UPDATE users SET status = :status, loginTime = :login  WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':login', $date);
                $stmt->bindParam(':id', $row['id']);
                if ($stmt->execute()) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public static function rememberMeToken($id, $token)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
    }

    public static function tokenChecker($token)
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        $sql = "SELECT * FROM users WHERE remember_token = :token";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            session_start();
            session_regenerate_id(true);
            $_SESSION['userName'] = $row['user_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['funder'] = $row['organization'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['user_email'];
            $_SESSION['access'] = $row['access'];

            $status = 'online';

            $sql = "UPDATE users SET status = :status, loginTime = :login  WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':login', $date);
            $stmt->bindParam(':id', $row['id']);
            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }

    public static function logout()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);
        $date = Date::dateTimeToday();

        session_start();

        $id = $_SESSION['user_id'] ?? '';

        if (empty($id)) {
            return true;
        }

        $status = 'offline';
        $token = 'off';

        $sql = "UPDATE users SET status = :status, logoutTime = :logout WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':logout', $date);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $_SESSION = array();
            session_destroy();

            return true;
        }

        return false;
    }

    public static function userLogout()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);

        session_start();

        $username = $_SESSION['userName'];

        $status = 'offline';

        $sql = "UPDATE users SET status = :status WHERE user_name = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $_SESSION = array();
            session_destroy();

            header('location: /login');
        } else {
            echo '<script>showCustomAlert("Error!", width = "50%", "");</script>';
        }
    }

    public static function access($access = ''): void
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userName'])) {

            redirectJs('/login');
            exit();
        }

        switch ($access) {
            case 'admin':
                redirectJs('/');
                break;
            case 'viewer':
                redirectJs('/0/');
                break;
            default:
                redirectJs('/login/logout');
                break;
        }
    }

    public static function forgotPassword()
    {
        $dbInstance = new dbh();
        $db = $dbInstance->connect(1);
    }
}