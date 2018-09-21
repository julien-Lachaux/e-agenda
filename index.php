<?php
require 'src/class/Autoloader.php';
Autoloader::register();

echo "server up \n";

$requete = new Requete();
$router = new Router($requete);

echo "<pre>";
// var_dump($requete);
echo "</pre>";

$router->get('/test', function() {
  echo "<h1>nice</h1>";
  return "<h1>nice</h1>";
});