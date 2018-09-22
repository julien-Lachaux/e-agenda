<?php
require 'src/class/Autoloader.php';
require 'utils/class/Utils.php';
require 'src/modules/users/UsersController.php';

// configuration de l'application
Autoloader::register();
Utils::recupererEnvVar();

// definitions des routes
$requete = new Requete();
$router = new Router($requete);
$UsersController = new UsersController();

$router->get('/', $UsersController->lister());
$router->get('/users/lister', $UsersController->lister());