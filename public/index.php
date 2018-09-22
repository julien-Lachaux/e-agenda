<?php
require __DIR__ . '/../src/class/Autoloader.php';
require __DIR__ . '/../utils/class/Utils.php';
require __DIR__ . '/../src/modules/users/UsersController.php';

// configuration de l'application
Autoloader::register();
Utils::recupererEnvVar();

// definitions des routes
$requete = new Requete();
$router = new Router($requete);
$UsersController = new UsersController();

// $router->get('/', $UsersController->lister($router->getRequete()));
$router->get('/users/afficher', $UsersController->afficher($requete));
$router->get('/users/lister', $UsersController->lister($requete));