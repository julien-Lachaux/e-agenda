<?php
require_once('ReponseInterface.php');
require_once(__DIR__ . "/../../utils/class/Utils.php");
require_once(__DIR__ . "/../../vendor/mustache.php-master/bin/build_bootstrap.php");

abstract class Controller implements ReponseInterface   {
    
    public $module;

    public function render($cheminDeLaVue, $data) {
        $m = new Mustache_Engine;
        $cheminFichierVue  = __DIR__ . "/../modules/{$this->module}/views/{$cheminDeLaVue}.html";
        $vue = Utils::recupererContenuFichier($cheminFichierVue);

        return $m->render($vue, $data);
    }
    
}