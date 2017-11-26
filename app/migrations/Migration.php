<?php
namespace Homework\migrations;

class Migration
{
    public $table;
    public $attributes = [];

    /**
     * Migration constructor.
     * @param $table
     * @param $attributes
     */
    public function __construct($table, $attributes)
    {
        $this->table = $table;
        $this->attributes = $attributes;
    }

    public static function generate($table, $attributes)
    {
        return new static($table, $attributes);
    }


}