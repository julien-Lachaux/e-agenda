<?php
namespace Modules\core;

use Source\Controller;

class CoreController extends Controller 
{
    public $module = 'core';
    
    public function accueil($requete) {
        return $this->render('templates/base', [
            "page" => [
                "nom"       => "accueil",
                "titre"     => "Accueil",
                "scripts"   => [ "pages/accueil" ],
            ]
        ]);
    }

    public function users($requete) {
        return $this->render('templates/base', [
            "page" => [
                "nom"       => "users",
                "titre"     => "Les utilisateurs",
                "scripts"   => [ "pages/users/liste" ],
            ]
        ]);
    }

    public function connexion($requete) {
        return $this->render('templates/base', [
            "page" => [
                "nom"       => "connexion",
                "titre"     => "Connectez-vous",
                "scripts"   => [ "pages/users/connexion" ],
            ]
        ]);
    }

    public function erreur404($requete) {
        return $this->render('erreurs/404', [
            "page" => [
                "nom"       => "erreur404",
                "titre"     => "Page Introuvable",
                "style"     => [ "erreur" ]
            ]
        ]);
    }

}