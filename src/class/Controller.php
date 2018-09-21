<?php
abstract class Controller implements ReponseInterface   {
    
    protected static function render($cheminDeLaVue) {
        echo($cheminDeLaVue);
    }
    
}