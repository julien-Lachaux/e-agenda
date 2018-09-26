<?php

namespace Modules\utilisateurs;

use Source\Utils;
use Source\Requete;
use Source\Controller;
use Modules\contacts\Contact;
use Modules\utilisateurs\Utilisateur;
use Modules\utilisateurs\Utilisateurs;

class UtilisateursController extends Controller 
{
	public $module = 'utilisateurs';

	/**
	 * Creer un nouveau: utilisateur
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function creer(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function afficher(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function lister(Requete $requete) {
		$Utilisateurs = new Utilisateurs();
		$data = $Utilisateurs->findAll();
		if ($data !== false) {
			return $this->render("lister", array("utilisateurs" => $data));
		}
		return $this->render("lister", array(
			"erreur" => ["message" => "aucun utilisateurs"]
		));
	}

	/**
	 * renvoi le formulaire de connexion
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function connexionFormulaire(Requete $requete) {
		return $this->render("connexion", []);
	}

	/**
	 * renvoi le formulaire d'inscription
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function inscriptionFormulaire(Requete $requete) {
		return $this->render("inscription", []);
	}

	/**
	 * connecte l'utilisateur
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function connexion(Requete $requete) {
		$utilisateurInformation = (object) $requete->getBody();
		
		$utilisateur = Utilisateurs::findOne("login='{$utilisateurInformation->connexion_email}'");
		if ($utilisateur !== false) {
			if (password_verify($utilisateurInformation->connexion_password, $utilisateur->password)) {
				$_SESSION["utilisateur"] = $utilisateur;
				return $this->render("connexion", array(
					"connexionReussi" => true
				));
			}
			return $this->render("connexion", array(
				"erreur" => ["message" => "mauvais password !"]
			));
		}
		return $this->render("connexion", array(
			"erreur" => ["message" => "email inconnue !"]
		));
	}

	/**
	 * inscrit l'utilisateur
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function inscription(Requete $requete) {
		$utilisateurInformation = (object) $requete->getBody();
		$utilisateur = Utilisateurs::findOne("login='{$utilisateurInformation->login}'");
		if ($utilisateur === false) {
			if (Utilisateur::valider($utilisateurInformation)) {
				$Utilisateur = new Utilisateur($utilisateurInformation);

				if ($Utilisateur->creer()) {
					return $this->render("connexion", array(
						"inscriptionReussi" => [ "utilisateur" => $Utilisateur ]
					));	
				}

				return $this->render("inscription", array(
					"erreur" => ["message" => "Informations manquantes"]
				));
			}

			return $this->render("inscription", array(
				"erreur" => ["message" => "informations invalide"]
			));
		}

		return $this->render("inscription", array(
			"erreur" => ["message" => "le login est deja utilisé"]
		));
	}

	/**
	 * deconnecte l'utilisateur
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function deconnexion(Requete $requete) {
		if (isset($_SESSION["utilisateur"])) {
			session_destroy();
			header("Location: /connexion");
		}
		return $this->render("connexion", array(
			"erreur" => ["message" => "email inconnue !"]
		));
	}

	/**
	 * renvoie les contacts d'un utlisateurs
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function utilisateurContacts(Requete $requete) {
		$utilisateur = new Utilisateur($_SESSION["utilisateur"]);
		$contacts = $utilisateur->getContacts();

		foreach($contacts as $index => $contact) {
			$contactModel = new Contact($contact);
			$contacts[$index]["adresses"] = $contactModel->getAdresses();
		}
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function editer(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function supprimer(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function editerUtilisateurActuel(Requete $requete) {
		$utilisateurActuel = $_SESSION["utilisateur"];
		$utilisateurActuelId = $utilisateurActuel->id;
		$formulaire = $requete->getBody();

		if ($formulaire !== NULL) {
			$test = Utilisateur::valider($formulaire);
			if ($test !== false) {
				foreach($formulaire as $colonne => $valeur) {
					if (!($colonne === 'password' && empty($valeur))) {
						if ($colonne === 'password') {
							$valeur = password_hash($valeur, PASSWORD_DEFAULT);
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
				"utilisateur"	=> $utilisateurActuel,
				"erreur"		=> [ "message" => "informations invalide" ]
			)); 
		} else {
			return $this->render("profile", array(
				"utilisateur"	=> $utilisateurActuel
			)); 
		}
	}

}
