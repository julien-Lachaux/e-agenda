<?php
require_once('src/class/Controller.php');

class UsersController extends Controller 
{
	static public function creer($usersData) {
		if (User::valider($usersData)) {
			$User = new User($usersData);
			$data = $User->creer();
			if ($data) {
				self::render("CHEMIN VUE SUCCESS CREATION", $data);
			} else {
				self::render("CHEMIN VUE EXISTE DEJA CREATION", $data);
			}
		} else {
			self::render("CHEMIN VUE ERREUR DONNEE CREATION", $data);
		}
	}

	static public function afficher($user_id) {
		$user_data = Users::findById($user_id);
		if ($user_data === false) {
			self::render("CHEMIN VUE EXISTE PAS AFFICHER", $user_data);
		} else {
			self::render("CHEMIN VUE AFFICHER", $user_data);
		}
	}

	static public function lister() {
		$users_data = Users::findAll();
		if ($users_data === false) {
			self::render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $users_data);
		} else {
			self::render("CHEMIN VUE LISTE", $users_data);
		}
	}

}
