<?php
namespace Homework\core\helpers;

class Collection
{
    private $data = [];

    /**
     * Collection constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function all()
    {
        return $this->data;
    }

    public function first()
    {
        $this->data = array_values($this->data);
        return $this->all()[0];
    }

    public function add($obj):void
    {
        $this->data[] = $obj;
    }

    public function filter($function):Collection
    {
        return new Collection(array_filter($this->data, $function));
    }

    public function filterWithKey($function):Collection
    {
        return new Collection(array_filter($this->data, $function,ARRAY_FILTER_USE_BOTH));
    }

    public function map($function):Collection
    {
        return new Collection(array_map($function, $this->data));
    }

    public function count():int
    {
        return count($this->data);
    }

}