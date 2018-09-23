<?php
namespace Modules\core;

use Source\Controller;

class CoreController extends Controller 
{
    public $module = 'core';
    
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
                "scripts"   => [ "pages/contacts/liste" ],
            ]
        ]);
    }

    public function formulaireEditerContact($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "editerContact",
                "titre"     => "Modifier un contact",
                "scripts"   => [ "pages/contacts/formulaire" ],
            ]
        ]);
    }

    public function formulaireNouveauContact($requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "nouveauContact",
                "titre"     => "Ajouter un contact",
                "scripts"   => [ "pages/contacts/formulaire" ],
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