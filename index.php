<?php
require 'src/class/Autoloader.php';
require 'src/controllers/UsersController.php';
Autoloader::register();

$requete = new Requete();
$router = new Router($requete);

$router->get('/test', UsersController::test());