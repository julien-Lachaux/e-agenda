<?php
require_once('class/cli_utils.php');
$cliParametre = cli_utils::recupererCliArgs($argv);


// definier quel script cli doit être appeler
$cliClass = "cli_{$cliParametre['class']}";
$cliFonction = $cliParametre['fonction'];
$cliFonctionParametres = $cliParametre['arguments'];

// appel du script demander
require("class/{$cliClass}.php");

$classe = new $cliClass();
$classe->{$cliFonction}($cliFonctionParametres);

