<?php
require(__DIR__ . "/../src/class/Autoloader.php");
require(__DIR__ . "/../src/modules/AutoloaderModules.php");
require(__DIR__ . "/../vendor/mustache.php-master/src/Mustache/Autoloader.php");

use Source\Utils;
use Source\Router;
use Source\Requete;
use Source\Autoloader;
use Modules\AutoloaderModules;
use Modules\core\CoreController;
use Modules\users\UsersController;

// configuration de l'application
Autoloader::register();
AutoloaderModules::register();
Mustache_Autoloader::register();

Utils::recupererEnvVar();

// definitions des routes
$requete = new Requete();
$router = new Router($requete);

// routes core
$Core = new CoreController();

$router->get("/", [
    "controller"    => $Core,
    "methode"       => "accueil"
]);

// routes modules
// users
$Users = new UsersController();

$router->get('/users/afficher', [
    "controller" => $Users,
    "methode"    => "afficher"
]);

$router->get('/users/lister', [
    "controller" => $Users,
    "methode"    => "lister"
]);

// routes erreurs
$router->get("/erreur/404", [
    "controller"    => $Core,
    "methode"       => "erreur404"
]);