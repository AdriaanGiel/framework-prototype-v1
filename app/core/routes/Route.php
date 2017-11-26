<?php
namespace Homework\core\routes;

class Route
{
    private $type;
    private $name;
    private $controller;
    private $method;

    /**
     * Router constructor.
     * @param $type
     * @param $name
     * @param $controller
     * @param $method
     */
    private function __construct($type, $name, $controller, $method)
    {
        $this->type = $type;
        $this->name = $name;
        $this->controller = $controller;
        $this->method = $method;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    public static function generate($type, $name, $controller, $method)
    {
        return new static($type, $name, $controller, $method);
    }

}