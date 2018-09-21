<?php
require_once('class/Utils.php');
$cliParametre = Utils::recupererCliArgs($argv);


// definier quel script cli doit être appeler
$cliFonction = $cliParametre['fonction'];
$cliFonctionParametres = $cliParametre['arguments'];

// appel du script demander
require("class/{$cliParametre['class']}.php");
$classe = new $cliParametre['class']();
$classe->{$cliFonction}($cliFonctionParametres);

