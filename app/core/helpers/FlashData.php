<?php

namespace Homework\core\helpers;

class FlashData
{
    private function __construct($data)
    {
        $_SESSION['flashdata'] += $data;
        $_SESSION['flashdata_used'] = false;
    }

    public static function make($data)
    {
        return new static($data);
    }

    public static function destroy()
    {
        if(!$_SESSION['flashdata_used']){
            $_SESSION['flashdata_used'] = true;
        }else{
            $_SESSION['flashdata'] = [];
        }
    }
}