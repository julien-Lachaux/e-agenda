<?php
include_once 'ReponseInterface.php';

abstract class Controller implements ReponseInterface   {
    
    public static function render($cheminDeLaVue) {
        echo($cheminDeLaVue);
    }
    
}