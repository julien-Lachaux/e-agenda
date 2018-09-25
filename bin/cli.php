<?php
require(__DIR__ . '/../src/class/Autoloader.php');

use Source\Utils;
use Source\Autoloader;

// configuration 
Autoloader::register();
Utils::recupererEnvVar();
$cliParametre = Utils::recupererCliArgs($argv);

// definier quel script cli doit être appeler
$cliFonction = $cliParametre['fonction'];
$cliFonctionParametres = $cliParametre['arguments'];

// appel du script demander
$appelClasse = "\\Source\\cli\\{$cliParametre['class']}";
$classe = new $appelClasse;
$classe->{$cliFonction}($cliFonctionParametres);