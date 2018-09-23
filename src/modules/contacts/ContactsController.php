<?php

namespace Modules\contacts;

use Source\Controller;

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
			if ($data) {
				return $this->render("CHEMIN VUE SUCCESS CREATION", $data);
			} else {
				return $this->render("CHEMIN VUE EXISTE DEJA CREATION", $data);
			}
		} else {
			return $this->render("CHEMIN VUE ERREUR DONNEE CREATION", $data);
		}
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
				return $this->render("CHEMIN VUE AFFICHER", $data);
			} else {
				return $this->render("CHEMIN VUE EXISTE PAS AFFICHER", $data);
			}
		} else {
			return $this->render("CHEMIN VUE EXISTE PAS AFFICHER", $data);
		}
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
			return $this->render("CHEMIN VUE LISTE", $data);
		} else {
			return $this->render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $data);
		}
	}

}
