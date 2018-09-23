<?php

namespace Modules\contacts;

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
		$contactsData = $requete->getBody();
		if (Contact::valider($requete->getBody())) {
			$Contact = new Contact($contactsData);
			$data = $Contact->creer();
			if ($data !== false) {
				return $this->render("creation", array("nouveaucontacts" => $data));
			}
			return $this->render("creation", array(
				"erreur" => ["message" => "contacts inconnue"]
			));
		}
		return $this->render("creation", array(
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

}
