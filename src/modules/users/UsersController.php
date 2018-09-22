<?php
require_once(__DIR__ . "/../../class/Controller.php");
require_once('User.php');
require_once('Users.php');

class UsersController extends Controller 
{
	public $module = 'users';

	public function creer($requete) {
		if (User::valider($usersData)) {
			$User = new User($usersData);
			$data = $User->creer();
			if ($data) {
				return $this->render("test", $data);
			} else {
				return $this->render("test", $data);
			}
		} else {
			return $this->render("test", $data);
		}
	}

	public function afficher($requete) {
		$urlParams = $requete->getUrlParams();
		$user_id = $urlParams[0];
		$users = new Users();
		$user_data = $users->findById($user_id);

		if ($user_data === false) {
			return $this->render("user", []);
		} else {
			return $this->render("user", $user_data);
		}
	}

	public function lister($requete) {
		$users = new Users();
		$users_data = $users->findAll();
		if ($users_data === false) {
			return $this->render('usersList', $users_data);
		} else {
			return $this->render('usersList', $users_data);
		}
	}

}
