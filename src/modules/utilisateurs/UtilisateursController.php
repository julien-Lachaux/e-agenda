<?php

namespace Modules\utilisateurs;

use Source\Controller;
use Modules\utilisateurs\Utilisateur;
use Modules\utilisateurs\Utilisateurs;

class UtilisateursController extends Controller 
{
	public $module = 'utilisateurs';

	/**
	 * Creer un nouveau: utilisateur
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function creer($requete) {
		$utilisateursData = $requete->getBody();
		if (Utilisateur::valider($requete->getBody())) {
			$Utilisateur = new Utilisateur($utilisateursData);
			$data = $Utilisateur->creer();
			if ($data !== false) {
				return $this->render("creation", array("nouveauutilisateurs" => $data));
			}
			return $this->render("creation", array(
				"erreur" => ["message" => "utilisateurs inconnue"]
			));
		}
		return $this->render("creation", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher les informations d'un utilisateur
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function afficher($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$utilisateur_id = $urlParams[0];
			$Utilisateurs = new Utilisateurs();
			$data = $Utilisateurs->findById($utilisateur_id);
			if ($data !== false) {
				return $this->render("affichage", array("utilisateur" => $data));
			}
			return $this->render("affichage", array(
				"erreur" => ["message" => "utilisateur inconnue"]
			));
		}
		return $this->render("affichage", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher la liste des utilisateurs
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function lister($requete) {
		$Utilisateurs = new Utilisateurs();
		$data = $Utilisateurs->findAll();
		if ($data !== false) {
			return $this->render("lister", array("utilisateurs" => $data));
		}
		return $this->render("lister", array(
			"erreur" => ["message" => "aucun utilisateurs"]
		));
	}

	public function connexionFormulaire($requete) {
		return $this->render("connexion", []);
	}

	public function connexion($requete) {
		header("Location: /contacts");
	}

	public function utilisateurContacts() {
		return $this->render("utilisateurContacts", []);
	}

}
