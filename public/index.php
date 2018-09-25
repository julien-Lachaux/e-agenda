<?php
require(__DIR__ . "/../src/class/Autoloader.php");
require(__DIR__ . "/../src/modules/AutoloaderModules.php");
require(__DIR__ . "/../vendor/mustache/mustache/src/Mustache/Autoloader.php");

use Source\Utils;
use Source\Router;
use Source\Requete;
use Source\Autoloader;
use Modules\AutoloaderModules;

// configuration de l'application
Autoloader::register();
AutoloaderModules::register();
Mustache_Autoloader::register();

// recupération des variables d'environnement
Utils::recupererEnvVar();

// récupérations des routes
$requete = new Requete();
$router = new Router($requete);

$router->recupererRoutesModule("pages");
$router->recupererRoutesModule("utilisateurs");
$router->recupererRoutesModule("contacts");
$router->recupererRoutesModule("adresses");
$router->recupererRoutesModule("validateurs");