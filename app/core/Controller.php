<?php

namespace Homework\core;

use Homework\core\helpers\FlashData;
use Homework\core\helpers\Session;

abstract class Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->checkFlashData();
        FlashData::destroy();
    }

    /**
     * Method to redirect to other page
     * @param $url
     */
    protected function redirect($url):void
    {
        header("Location:" . BASE_URL . $url,true,301);
        exit;
    }

    protected function redirectBackWithErrors($url,$errors,$inputs =  []):void
    {
        FlashData::make($inputs);

        Session::setErrors($errors);

        $this->redirect($url);
    }

    /**
     * Method to check if session errors have been used. If they have been used remove session errors
     */
    private function checkFlashData():void
    {
        if(isset($_SESSION["errors"])){
            if(count($_SESSION["errors"]) != 0)
            {
                if(isset($_SESSION["errors"]["seen"])){

                    if(!$_SESSION["errors"]["seen"]){
                        $_SESSION["errors"]["seen"] = true;
                    }else{
                        $_SESSION["errors"] = [];
                    }
                }
            }
        }

    }

}