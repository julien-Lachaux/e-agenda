<?php
require 'src/class/Autoloader.php';
require 'utils/class/Utils.php';
require 'src/controllers/UsersController.php';

// configuration de l'application
Autoloader::register();
$ENV = Utils::recupererEnvVar();
$bdd = new Base($ENV["BDD_NAME"], $ENV["BDD_USER"], $ENV["BDD_PASS"], $ENV["BDD_HOST"], $ENV["BDD_PORT"]);

// definitions des routes
$requete = new Requete();
$router = new Router($requete);

$router->get('/test', UsersController::test());

Utils::debugPre($ENV);