<?php

namespace Modules\contacts;

use Source\Utils;
use Source\Requete;
use Source\Controller;
use Modules\contacts\Contact;
use Modules\contacts\Contacts;

class ContactsController extends Controller 
{
	public $module = 'contacts';

	/**
	 * Creer un nouveau: contact
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function creer(Requete $requete) {
		$contactData = $requete->getBody();
		if (Contact::valider($requete->getBody())) {
			$contactData["utilisateurs_id"] = $_SESSION["utilisateur"]->id;
			$Contact = new Contact($contactData);
			$data = $Contact->creer();
			if ($data !== false) {
				return $this->render("formulaire", array("creationReussi" => true));
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function afficher(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$contact_id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($contact_id);
			if ($data !== false) {
				return $this->render("detail", array("contact" => $data));
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function lister(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function editer(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($id);

			if ($data !== false) {
				$formulaire = $requete->getBody();
				$test = Contact::valider($formulaire);
				if ($test !== false) {
					foreach($formulaire as $colonne => $valeur) {
						if ($data->{$colonne} !== $valeur) {
							$test = Contact::update($colonne, $valeur, "id={$id}");
						}
					}
					$contactMisAJour = $Contacts->findById($id);
					return $this->render("formulaire", array(
						"editionReussi" => true,
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function supprimer(Requete $requete) {
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

	/**
	 * renvoie le formulaire d'edition et de creation de contact
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function formulaire(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) { // c'est une edition
			$id = $urlParams[0];
			$Contacts = new Contacts();
			$data = $Contacts->findById($id);
			if ($data !== false) {
				$contact = new Contact($data);
				$data->adresses = $contact->getAdresses();
				return $this->render("formulaire", array("contact" => $data));
			}
		}
		// sinon c'est une creation
		return $this->render("formulaire", []);
	}

	/**
	 * renvoie les adresses d'un contact
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function contactAdresses(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$contactId = $urlParams[0];
			$Contacts = new Contacts();
			$contactData = $Contacts->findById($contactId);

			if ($contactData !== false) {
				$contact = new Contact($contactData);
				$contactAdresses = $contact->getAdresses();
				return $this->render("contactAdresses", array(
					"contactId" => $contactId,
					"adresses" => $contactAdresses
				));
			}
			return $this->render("contactAdresses", array(
				"erreur" => ["message" => "Aucune Adresses"]
			));
		}
		return $this->render("contactAdresses", array(
			"erreur" => ["message" => "Contact inconnue"]
		));
	}

}
