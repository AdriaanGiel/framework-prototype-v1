<?php
require_once "config.php";
require_once "settings.php";

//require_once __DIR__ . "/../../migrations/RunMigrations.php";

function loggedIn()
{
    return \Homework\core\helpers\Session::has("logged_in_user");
}

function getUser()
{
    return \Homework\core\helpers\Session::get("logged_in_user");
}


function hasError($name)
{
    if(isset($_SESSION['errors']) && count($_SESSION['errors']) > 0)
    {
        return isset($_SESSION['errors']['error'][$name]);
    }

    return false;
}

function getError($name)
{
    if(is_array($_SESSION['errors']['error'][$name]))
    {
        return implode('<br>',$_SESSION['errors']['error'][$name]);
    }

    return $_SESSION['errors']['error'][$name];
}

function hasFlash($name)
{
    return isset($_SESSION['flashdata'][$name]);
}

function getFlash($name)
{
    return $_SESSION['flashdata'][$name];
}

function getViewOption()
{
    return isset($_SESSION['view_option']);
}

require_once "routes.php";