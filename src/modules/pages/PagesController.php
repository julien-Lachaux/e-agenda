<?php
namespace Modules\pages;

use Source\Controller;

class PagesController extends Controller 
{
    public $module = 'pages';
    
    /**
     * renvoie la page d'accueil
     *
     * @param Requete $requete
     * @return HTML
     */
    public function accueil(Requete $requete) {
        return $this->render('templates/avecNavbar', [
            "page" => [
                "nom"       => "accueil",
                "titre"     => "Accueil",
                "scripts"   => [ "pages/accueil" ],
            ]
        ]);
    }

    /**
     * renvoie la pge de connecxion
     *
     * @param Requete $requete
     * @return HTML
     */
    public function connexion(Requete $requete) {
        return $this->render('templates/base', [
            "page" => [
                "nom"       => "connexion",
                "titre"     => "Connectez-vous",
                "scripts"   => [ "pages/utilisateurs/connexion" ],
            ]
        ]);
    }

    /**
     * renvois la pages mes contacts
     *
     * @param Requete $requete
     * @return HTML
     */
    public function utilisateurContacts(Requete $requete) {
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

    /**
     * renvoie la page mon profile
     *
     * @param Requete $requete
     * @return HTML
     */
    public function utilisateurProfile(Requete $requete) {
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

    /**
     * renvoie la page erreur 404
     *
     * @param Requete $requete
     * @return HTML
     */
    public function erreur404(Requete $requete) {
        return $this->render('erreurs/404', [
            "page" => [
                "nom"       => "erreur404",
                "titre"     => "Page Introuvable",
                "style"     => [ "erreur" ]
            ]
        ]);
    }

}