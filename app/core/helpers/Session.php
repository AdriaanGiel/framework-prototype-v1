<?php

namespace Homework\core\helpers;


class Session
{
    public static function set($data)
    {
        $_SESSION = array_merge($_SESSION,$data);
    }

    public static function setErrors($data)
    {
        $errors["errors"] = ["error" => $data];
        $errors["errors"]["seen"] = false;
        self::set($errors);
    }

    public static function destroy($key)
    {
        unset($_SESSION[$key]);
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

}