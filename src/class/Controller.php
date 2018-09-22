<?php
require_once('ReponseInterface.php');
require_once(__DIR__ . "/../../utils/class/Utils.php");
require_once(__DIR__ . "/../../vendor/mustache.php-master/src/Mustache/Autoloader.php");
Mustache_Autoloader::register();

abstract class Controller implements ReponseInterface   {
    
    public $module;
    
    public function render($cheminDeLaVue, $data) {

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . "/../modules/{$this->module}/views", array('extension' => '.html'))
        ));

        return $m->render($cheminDeLaVue, array('data' => $data));
    }
    
}