<?php

namespace Homework\core\helpers;

class FlashData
{
    private function __construct($data)
    {
        Session::set([
            'flashdata' => $data,
            'flashdata_used' => false
        ]);
//        $_SESSION['flashdata'] = $data;
//        $_SESSION['flashdata_used'] = false;
    }

    public static function make($data)
    {
        return new static($data);
    }

    public static function destroy()
    {
        if(Session::has('flashdata_used')){
            if(!Session::get('flashdata_used')){
                Session::set([
                    'flashdata_used' => true
                ]);
            }else{
                Session::set([
                    'flashdata' => []
                ]);
            }
        }

//        if(isset($_SESSION['flashdata_used']))
//        {
//            if(!$_SESSION['flashdata_used']){
//                $_SESSION['flashdata_used'] = true;
//            }else{
//                $_SESSION['flashdata'] = [];
//            }
//        }

    }
}