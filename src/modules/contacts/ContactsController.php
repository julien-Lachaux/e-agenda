<?php
require_once('src/class/Controller.php');

class ContactsController extends Controller 
{
	static public function creer($contactsData) {
		if (Contact::valider($contactsData)) {
			$contact = new Contact($contactsData);
			if ($contact->creer()) {
				echo "contact creer avec success";
			} else {
				echo "contact existe deja";
			}
		} else {
			echo "data invalide pour creer contact}";
		}
	}

}
