<?php
require('src/class/Autoloader.php');

use Source\Utils;
use Source\Autoloader;

Autoloader::register();
$cliParametre = Utils::recupererCliArgs($argv);


// definier quel script cli doit être appeler
$cliFonction = $cliParametre['fonction'];
$cliFonctionParametres = $cliParametre['arguments'];

// appel du script demander
require("class/cli/{$cliParametre['class']}.php");
$appelClasse = "\\Source\\cli\\{$cliParametre['class']}";
$classe = new $appelClasse;
$classe->{$cliFonction}($cliFonctionParametres);