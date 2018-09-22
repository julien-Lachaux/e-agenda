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
$router->recupererRoutesModule("core");
$router->recupererRoutesModule("users");
// $router->recupererRoutesModule("contacts");
// $router->recupererRoutesModule("adresses");