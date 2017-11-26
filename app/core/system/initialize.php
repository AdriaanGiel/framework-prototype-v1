<?php
require_once "config.php";
require_once "settings.php";

//require_once __DIR__ . "/../../migrations/RunMigrations.php";


function hasError($name)
{
    return isset($_SESSION['errors']['error'][$name]);
}

function getError($name)
{
    return $_SESSION['errors']['error'][$name][0];
}

function hasFlash($name)
{
    return isset($_SESSION['flashdata'][$name]);
}

function getFlash($name)
{
    return $_SESSION['flashdata'][$name];
}

require_once "routes.php";