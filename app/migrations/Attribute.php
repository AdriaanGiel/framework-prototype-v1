<?php

namespace Homework\migrations;


class Attribute
{

    private $name;
    private $type;
    private $num;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * attribute constructor.
     * @param $name
     * @param $type
     * @param $num
     */
    public function __construct($name, $type, $num)
    {
        $this->name = $name;
        $this->type = $type;
        $this->num = $num;
    }

    /**
     * @param $name
     * @param $type
     * @param $num
     * @return static
     */
    public static function generate($name, $type, $num)
    {
        return new static($name, $type, $num);
    }


}