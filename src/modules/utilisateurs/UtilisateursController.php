<?php

namespace Modules\utilisateurs;

use Source\Utils;
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
		$utilisateurInformation = (object) $requete->getBody();
		
		$utilisateur = Utilisateurs::findOne("login='{$utilisateurInformation->connexion_email}'");
		if ($utilisateur !== false) {
			if ($utilisateur->password === hash('sha512', $utilisateurInformation->connexion_password)) {
				$_SESSION["utilisateur"] = $utilisateur;
				header("Location: /contacts");
			}
			return $this->render("connexion", array(
				"erreur" => ["message" => "mauvais password !"]
			));
		}
		return $this->render("connexion", array(
			"erreur" => ["message" => "email inconnue !"]
		));
	}

	public function deconnexion($requete) {
		if (isset($_SESSION["utilisateur"])) {
			session_destroy();
			header("Location: /connexion");
		}
		return $this->render("connexion", array(
			"erreur" => ["message" => "email inconnue !"]
		));
	}

	public function utilisateurContacts($requete) {
		$utilisateur = new Utilisateur($_SESSION["utilisateur"]);
		$contacts = $utilisateur->getContacts();
		if ($contacts !== false) {
			return $this->render("utilisateurContacts", array("contacts" => $contacts));
		}
		return $this->render("utilisateurContacts", array(
			"erreur" => ["message" => "Aucun Contact"]
		));
	}

	/**
	 * Afficher la liste des users
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function editer($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$user_id = $urlParams[0];
			$Users = new Users();
			$data = $Users->findById($user_id);

			if ($data !== false) {
				$formulaire = $requete->getBody();
				$test = User::valider($formulaire);
				if ($test !== false) {
					$colonneEditer = [];
					foreach($formulaire as $colonne => $valeur) {
						if ($data->{$colonne} !== $valeur) {
							$colonneEditer[] = array("nom" => "$colonne");
							$test = User::update($colonne, $valeur, "id={$user_id}");
						}
					}
					return $this->render("editionReussi", array("colonneEditer" => $colonneEditer)); // user editer avec succes
				}
				return $this->render("usersList", $data); // formulaire invalide
			}
			return $this->render("usersList", $data); // le user n'existe pas
		}
		return $this->render("usersList", $data); // il manque l'id
	}

	/**
	 * Supprime un utilisateur
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function supprimer($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$user_id = $urlParams[0];
			if(User::supprimer($user_id) !== false) {
				return $this->render("supprimer", array( // suppresion reussi
					"reussite" => [
						"id" => $user_id
					]
				));
			}
			return $this->render("supprimer", array(
				"erreur" => ["message" => "utilisateur inconnue"] // utilisateur inconnue
			));
		}
		return $this->render("supprimer", array(
			"erreur" => [
				"message" => "id manquant" // pas d'id dans la requete
			]
		));
	}

	/**
	 * Edite l'utilisateur actuellement connecté
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function editerUtilisateurActuel($requete) {
		$utilisateurActuel = $_SESSION["utilisateur"];
		$utilisateurActuelId = $utilisateurActuel->id;
		$formulaire = $requete->getBody();

		if ($formulaire !== NULL) {
			$test = Utilisateur::valider($formulaire);
			if ($test !== false) {
				foreach($formulaire as $colonne => $valeur) {
					if (!($colonne === 'password' && empty($valeur))) {
						if ($colonne === 'password') {
							$valeur = hash('sha512', $valeur);
						}
						if ($utilisateurActuel->{$colonne} !== $valeur) {
							$test = Utilisateur::update($colonne, $valeur, "id={$utilisateurActuelId}");
						}
					}
				}
				$Utilisateurs = new Utilisateurs();
				$utilisateurEditer = $Utilisateurs->findById($utilisateurActuelId);
				$_SESSION["utilisateur"] = $utilisateurEditer;
				return $this->render("profile", array(
					"editionReussi" => true,
					"utilisateur"	=> $utilisateurEditer
				)); // user editer avec succes
			}
			return $this->render("profile", array(
				"utilisateur"	=> $utilisateurActuel
			)); 
		} else {
			return $this->render("profile", array(
				"utilisateur"	=> $utilisateurActuel
			)); 
		}
	}

}
