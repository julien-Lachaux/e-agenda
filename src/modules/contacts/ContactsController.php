<?php

namespace Modules\contacts;

use Source\Utils;
use Source\Controller;
use Modules\contacts\Contact;
use Modules\contacts\Contacts;

class ContactsController extends Controller 
{
	public $module = 'contacts';

	/**
	 * Creer un nouveau: contact
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function creer($requete) {
		$contactData = $requete->getBody();
		if (Contact::valider($requete->getBody())) {
			$contactData["utilisateurs_id"] = $_SESSION["utilisateur"]->id;
			$Contact = new Contact($contactData);
			$data = $Contact->creer();
			if ($data !== false) {
				return $this->render("formulaire", array("creationReussi" => true)
				);
			}
			return $this->render("formulaire", array(
				"erreur" => ["message" => "contacts inconnue"]
			));
		}
		return $this->render("formulaire", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher les informations d'un contact
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function afficher($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$contact_id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($contact_id);
			if ($data !== false) {
				return $this->render("affichage", array("contact" => $data));
			}
			return $this->render("affichage", array(
				"erreur" => ["message" => "contact inconnue"]
			));
		}
		return $this->render("affichage", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher la liste des contacts
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function lister($requete) {
		$Contacts = new Contacts();
		$data = $Contacts->findAll();
		if ($data !== false) {
			return $this->render("lister", array("contacts" => $data));
		}
		return $this->render("lister", array(
			"erreur" => ["message" => "aucun contacts"]
		));
	}

	/**
	 * Afficher la liste des contacts
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function editer($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($id);

			if ($data !== false) {
				$formulaire = $requete->getBody();
				$test = Contact::valider($formulaire);
				if ($test !== false) {
					$colonneEditer = [];
					foreach($formulaire as $colonne => $valeur) {
						if ($data->{$colonne} !== $valeur) {
							$colonneEditer[] = array("nom" => "$colonne");
							$test = Contact::update($colonne, $valeur, "id={$id}");
						}
					}
					$contactMisAJour = $Contacts->findById($id);
					return $this->render("formulaire", array(
						"colonneEditer" => $colonneEditer,
						"contact"		=> $contactMisAJour
					)); // contact editer avec succes
				}
				return $this->render("formulaire", array(
					"contact" => $formulaire,
					"erreur" => [
						"message" => "formulaire invalide"
					])); // formulaire invalide
			}
			return $this->render("formulaire", array(
				"erreur" => [
					"message" => "le contact n'existe pas"
				])); // le contact n'existe pas
		}
		return $this->render("formulaire", array(
			"erreur" => [
				"message" => "requete invalide"
			])); // il manque l'id
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
			$id = $urlParams[0];
			if(Contact::supprimer($id) !== false) {
				return $this->render("supprimer", array( // suppresion reussi
					"reussite" => [
						"id" => $id
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

	public function formulaire($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) { // c'est une edition
			$id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($id);
			if ($data !== false) {
				return $this->render("formulaire", array("contact" => $data));
			}
		}
		// sinon c'est une creation
		return $this->render("formulaire", []);
	}

}
