<?php
namespace Source;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Source\interfaces\ReponseInterface;

abstract class Controller implements ReponseInterface
{
    
    public $module;
    
    /**
     * rend un template mustache
     *
     * @param String $cheminDeLaVue
     * @param Array|Object $data
     * @return HTML
     */
    public function render($cheminDeLaVue, $data) {

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . "/../modules/{$this->module}/views", array('extension' => '.html'))
        ));
        
        return $m->render($cheminDeLaVue, array('data' => $data));
    }
    
}