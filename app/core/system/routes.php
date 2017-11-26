<?php

use Homework\core\routes\Route;
use Homework\core\routes\Router;

$routes = [

  Route::generate("get","",AlbumController::class,"index"),
  Route::generate("get","albums",AlbumController::class,"index"),
  Route::generate("get","albums/create",AlbumController::class,"create"),
  Route::generate("get","albums/edit/{id}", AlbumController::class,"edit"),
  Route::generate("get","albums/{id}",AlbumController::class,"show"),
  Route::generate("get","test",AlbumController::class,"test"),


  Route::generate("post","albums/create", AlbumController::class,"insert"),
  Route::generate("post","albums/edit/{id}",AlbumController::class,"update"),
  Route::generate("post","albums/delete/{id}",AlbumController::class,"delete"),


  Route::generate("get","artists",ArtistController::class,"index")

];

//var_dump((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//exit;

$router = new Router($routes);

$url = str_replace("?","", trim("$_SERVER[REQUEST_URI]","/"));

$router->getPage($url);

