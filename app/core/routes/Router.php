<?php

namespace Homework\core\routes;

use Homework\core\helpers\Collection;

class Router extends Collection
{
    /**
     * @var string
     */
    private $namespace = "\Homework\controllers\\";

    /**
     * @var string
     */
    private $routeType;

    /**
     * Router constructor.
     */
    public function __construct($routes)
    {
        parent::__construct($routes);
        $this->routeType = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function getRouteType():string
    {
        return $this->routeType;
    }

    /**
     * @param $name
     * @return array|mixed
     */
    public function getPage($url)
    {
        $page = [];

        $routeArgument = $this->getPageWithArg($url);



        $url = $routeArgument->url;

        $pageInfo = $this->filter(function (Route $route) use( $url ){
            return $route->getName() == $url && strtoupper($route->getType()) == $this->getRouteType();
        });


        if(!empty($pageInfo->all()))
        {
            $page = $pageInfo->first();
        }else{
            echo "Page Does not exits";
        }

        if(count($page) != 0){

            $name = $this->namespace . $page->getController();
            $class = new $name();

            $this->useCorrectMethod($class,$page,$routeArgument);
        }

        return $page;
    }

    private function useCorrectMethod($class,$page,$routeArgument)
    {
        if(count($routeArgument->arguments) != 0)
        {
            return $class->{$page->getMethod()}(...$routeArgument->arguments);
        }

        return $class->{$page->getMethod()}();
    }

    private function getPageWithArg($url)
    {
        $urlParts = new Collection(explode("/",$url));

        $check = $urlParts->map(function ($part) {
            return $this->checkIfNumeric($part);
        });

        return $this->assembleUrlWithArgs($check);

    }

    private function assembleUrlWithArgs(Collection $urlObject)
    {
         $args = $urlObject->filter(function ($part){
             return is_int($part);
         });

         $url = $urlObject->map(function ($part){
             if(is_int($part)){
                return "{id}";
             }
             return $part;
         });

         return (object) [
             "arguments"    => $args->all(),
             "url"          => implode("/",$url->all())
         ];

    }

    private function explodeMultiple($string)
    {
        $exploded = explode(".",$string);

        if(count($exploded) > 1)
        {
            return $exploded;
        }

        return explode(",",$string);
    }

    private function checkIfNumeric($part)
    {
        if(is_numeric($part))
        {
            return $this->checkIfInteger($part);
        }

        return $part;
    }


    private function checkIfInteger($part)
    {
        if(count($this->explodeMultiple($part)) == 1)
        {
            return (int)$part;
        }

        return 0;
    }

}