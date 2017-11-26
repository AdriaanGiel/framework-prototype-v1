<?php

namespace Homework\core;

class Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
//        $this->checkFlashData();
    }

    /**
     * Method to redirect to other page
     * @param $url
     */
    protected function redirect($url):void
    {
        header("Location: http://framework.app/" . $url,true,301);
    }

    /**
     * Method to check if session errors have been used. If they have been used remove session errors
     */
    private function checkFlashData():void
    {
        if(count($_SESSION["errors"]) != 0)
        {
            if(!$_SESSION["errors"]["seen"]){

                var_dump("dasda");

                $_SESSION["errors"]["seen"] = true;
            }else{
                $_SESSION["errors"] = [];
            }
        }
    }

}