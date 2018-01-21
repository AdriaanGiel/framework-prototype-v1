<?php

namespace Homework\migrations;


class Attribute
{

    private $name;
    private $type;
    private $num;
    private $null;

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
    public function __construct($name, $type, $num, $null = 'NOT NULL')
    {
        $this->name = $name;
        $this->type = $type;
        $this->num = $num;
        $this->null = $null;
    }

    /**
     * @param $name
     * @param $type
     * @param $num
     * @return static
     */
    public static function generate($name, $type, $num, $null = false)
    {
        if($null)
        {
            return new static($name, $type, $num,$null);
        }
        return new static($name, $type, $num);
    }

    /**
     * @return string
     */
    public function getNull(): string
    {
        return $this->null;
    }


}