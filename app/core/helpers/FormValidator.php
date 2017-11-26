<?php

namespace Homework\core\helpers;


class FormValidator
{
    protected $rules = [];

    public function __construct($rules)
    {
        $this->rules = $this->transformRules($rules);
    }

    public static function make($rules)
    {
        return new static($rules);
    }

    public function validate($input)
    {
        $errors = $this->checkRules($input);

        if(count($errors)){
            $_SESSION['errors'] = [
                "error" => $errors,
                "seen" => false
            ];

         header("Location: create");
         exit;
        }
    }

    private function checkRules($input)
    {
        $validateData = $this->seperateRules();


        $errors = [];

        foreach ($validateData->required->all() as $key => $req){
            if(!$this->checkRequired($input[$key]))
            {
                $errors[$key][] = "Input $key is required";
            }
        }

        foreach ($validateData->numeric->all() as $key => $req){
            if(!$this->checkNumeric($input[$key]))
            {
                $errors[$key][] = "Input $key has to be numeric";
            }
        }

        foreach ($validateData->integer->all() as $key => $req){
            if(!$this->checkInt($input[$key]))
            {
                $errors[$key][] = "Input $key has to be a number";
            }
        }


        return $errors;
    }



    private function transformRules($rules)
    {
        $data = new Collection($rules);
        return $data->map(function ($rule){
            return explode("|",$rule);
        });
    }

    private function seperateRules()
    {
        $required = $this->rules->filter(function ($rule){
            return in_array("required",$rule);
        });

        $numeric = $this->rules->filter(function ($rule){
            return in_array("num",$rule);
        });

        $int = $this->rules->filter(function ($rule){
            return in_array("integer",$rule);
        });

        return (object)[
            'required'  => $required,
            'numeric'   => $numeric,
            'integer'   => $int
        ];
    }

    private function checkRequired($item)
    {
        return $item != "";
    }

    private function checkNumeric($item)
    {
        return is_numeric($item);
    }

    private function checkInt($item)
    {
        if($this->checkNumeric($item)){
            return is_int((int)$item);
        }
        return false;
    }


}