<?php

namespace Homework\core\helpers;


class PostValueCleaner extends Collection
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function generate($data)
    {
        $input = new static($data);
        return $input->sanitize()->all();
    }

    public function sanitize()
    {
        return $this->map(function ($input){
            return strip_tags(htmlspecialchars($input,ENT_QUOTES, 'UTF-8'));
        });
    }



}