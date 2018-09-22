<?php
namespace Modules\contacts;

use Source\Controller;

class ContactsController extends Controller 
{
	static public function creer($contactsData) {
		if (Contact::valider($contactsData)) {
			$Contact = new Contact($contactsData);
			$data = $Contact->creer();
			if ($data) {
				self::render("CHEMIN VUE SUCCESS CREATION", $data);
			} else {
				self::render("CHEMIN VUE EXISTE DEJA CREATION", $data);
			}
		} else {
			self::render("CHEMIN VUE ERREUR DONNEE CREATION", $data);
		}
	}

	static public function afficher($contact_id) {
		$contact_data = Contacts::findById($contact_id);
		if ($contact_data === false) {
			self::render("CHEMIN VUE EXISTE PAS AFFICHER", $contact_data);
		} else {
			self::render("CHEMIN VUE AFFICHER", $contact_data);
		}
	}

	static public function lister() {
		$contacts_data = Contacts::findAll();
		if ($contacts_data === false) {
			self::render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $contacts_data);
		} else {
			self::render("CHEMIN VUE LISTE", $contacts_data);
		}
	}

}
