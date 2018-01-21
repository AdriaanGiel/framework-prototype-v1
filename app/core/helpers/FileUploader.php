<?php

namespace Homework\core\helpers;


class FileUploader
{
    private $name;
    private $type;
    private $size;
    private $temp;
    private $path;

    private function __construct($file)
    {
        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->size = $file['size'];
        $this->temp = $file['tmp_name'];
    }

    public static function make($key)
    {
        if(isset($_FILES[$key]))
        {
            if($_FILES[$key]['name'] != "")
            {
                $newFile = new static($_FILES[$key]);
                $newFile->proccesFile();
                return $newFile;
            }
        }

        return false;
    }

    public function getFilePath()
    {
        return $this->path;
    }

    private function proccesFile()
    {
        if(is_file($this->temp))
        {
            $fileName = $this->constructName();
            $this->path = "images/$fileName";
            move_uploaded_file($this->temp, IMG_PATH . $fileName);
        }else{

            throw new \Exception("Er is iets mis gegaan bij het uploaden");
        }
    }

    private function constructName()
    {
        $parts = explode(".", $this->name);
        $extension = array_pop($parts);

        return hash('md5',microtime() . implode("", $parts)) . ".$extension";
    }

    public static function deleteFile($fileName)
    {
        if(is_file($fileName)){
            unlink($fileName);
        }
    }



}