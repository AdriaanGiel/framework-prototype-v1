<?php

namespace Homework\core\helpers;


class FormValidator
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * FormValidator constructor.
     * @param $rules
     */
    public function __construct($rules)
    {
        $this->rules = $this->transformRules($rules);
    }

    /**
     * Static constructor
     * @param $rules
     * @return static
     */
    public static function make($rules)
    {
        return new static($rules);
    }

    /**
     * Method to validate input
     * @param $input
     */
    public function validate($input)
    {
        $errors = $this->checkRules($input);

        if(count($errors)){
            $_SESSION['errors'] = [
                "error" => $errors,
                "seen" => false
            ];

         FlashData::make($input);

         header("Location: create");
         exit;
        }
    }

    /**
     * Method to return errors in correct format and matching input key
     * @param $input
     * @return array
     */
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


    /**
     * Method to get seperate rules for a input
     * @param $rules
     * @return Collection
     */
    private function transformRules($rules)
    {
        $data = new Collection($rules);
        return $data->map(function ($rule){
            return explode("|",$rule);
        });
    }

    /**
     * Method to filter through all possible rules and return all input data with matching rules
     * @return object
     */
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

    /**
     * Method to see if input is filled
     * @param $item
     * @return bool
     */
    private function checkRequired($item)
    {
        return $item != "";
    }

    /**
     * Method to check if input is numeric
     * @param $item
     * @return bool
     */
    private function checkNumeric($item)
    {
        return is_numeric($item);
    }

    /**
     * Method to check if input is a integer
     * @param $item
     * @return bool
     */
    private function checkInt($item)
    {
        if($this->checkNumeric($item)){
            return is_int((int)$item);
        }
        return false;
    }


}