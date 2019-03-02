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

        // on ajoute la gestion du proxy pour le front pour que les requetes se fasse au travers du proxy si il est activé
        $data["withProxy"] = HTTP_PROXY;
        $data["proxyHost"] = HTTP_PROXY ? HTTP_PROXY_HOST : null;
        
        return $m->render($cheminDeLaVue, array('data' => $data));
    }
    
}