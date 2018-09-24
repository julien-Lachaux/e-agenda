<?php
namespace Modules\pages;

use Source\Controller;

class PagesController extends Controller 
{
    public $module = 'pages';
    
    public function accueil($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "accueil",
                "titre"     => "Accueil",
                "scripts"   => [ "pages/accueil" ],
            ]
        ]);
    }

    public function utilisateurs($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "utilisateurs",
                "titre"     => "Les utilisateurs",
                "scripts"   => [ "pages/utilisateurs/liste" ],
            ]
        ]);
    }

    public function connexion($requete) {
        return $this->render('templates/base', [
            "page" => [
                "nom"       => "connexion",
                "titre"     => "Connectez-vous",
                "scripts"   => [ "pages/utilisateurs/connexion" ],
            ]
        ]);
    }

    public function utilisateurContacts($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "contacts",
                "titre"     => "Mes Contacts",
                "scripts"   => [ 
                    "components/contacts",
                    "components/adresses",
                    "pages/contacts/liste"
                ],
            ]
        ]);
    }

    public function utilisateurProfile($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "profile",
                "titre"     => "Mes informations",
                "scripts"   => [ 
                    "pages/utilisateurs/profile"
                ],
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