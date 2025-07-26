<?php

namespace app\models;

class session
{

    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    //session user login validation
    public static function validateSession()
    {
        self::start();

        if (!isset($_SESSION['userName']) && empty($_SESSION['userName'])) {
            redirectJs('/login');
        }
    }

    public static function checkRole($role = false)
    {
        if ($role) {
            if ($role !== 'admin') {
                redirectJs('/');
            }
        }
    }

    /**
     * note that it cannot use in array datas 
     * 
     * @param string $value used format "name_of_session = value";
     * 
     * @return mixed;
     */

    public static function set($value)
    {
        self::start();
        $part = explode('=', $value);
        if (count($part) != 2) {
            return false;
        }
        $sesionName = $part[0];
        $sessionValue = $part[1];
        $_SESSION[$sesionName] = $sessionValue;
    }

    /**
     * This is use only to store array session
     * @param mixed $name name of the session
     * @param array $array array data
     * @return void
     */
    public static function setArray($name, $array)
    {
        self::start();

        $_SESSION[$name] = [];

        if (is_array($array) && !empty($array)) {
            $_SESSION[$name][] = $array;
        } else {
            return null;
        }
    }

    public static function reset($name)
    {
        self::start();
        if ($name) {
            unset($name);
            return true;
        }
        return null;
    }

    public static function get($name)
    {
        self::start();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
    }

    public static function delete($value = false)
    {
        self::start();
        $unsetSession = self::get($value) ?? '';

        if ($value) {
            unset($unsetSession);
        } else {
            session_destroy();
        }
    }

}