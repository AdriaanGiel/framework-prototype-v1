<?php

namespace Homework\core\helpers;


use Homework\core\routes\Router;

class Auth
{

    public static function isLoggedIn():bool
    {
        return Session::has('logged_in_user');
    }

    public static function guest():void
    {
        if(!static::isLoggedIn()){
            FlashData::make([
                "guest_message" => "Log in to access this page"
            ]);

            Router::redirect("login");
        }
    }


}