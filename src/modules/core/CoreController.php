<?php
namespace Modules\core;

use Source\Controller;

class CoreController extends Controller 
{
    public $module = 'core';
    
    public function accueil($requete) {
        return $this->render('templates/base', [
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

    public function erreur404($requete) {
        return $this->render('erreurs/404', [
            "page" => [
                "nom" => "erreur404",
                "titre" => "Page Introuvable",
                "style" => [
                    "erreur.css"
                ]
            ]
        ]);
    }

}