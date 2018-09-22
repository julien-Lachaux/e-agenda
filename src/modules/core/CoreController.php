<?php
namespace Modules\core;

use Source\Controller;

class CoreController extends Controller 
{
    public $module = 'core';
    
    public function accueil(requete $requete) {
        return $this->render('templates/base.html', [
            "page" => [
                "nom" => "accueil",
                "titre" => "Accueil",
                "scripts" => [
                    "main.js"
                ],
                "style" => [
                    "main.css"
                ]
            ]
        ]);
    }

}