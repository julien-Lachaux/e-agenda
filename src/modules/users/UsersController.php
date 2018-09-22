<?php
require_once('src/class/Controller.php');
require_once('src/modules/users/User.php');
require_once('src/modules/users/Users.php');

class UsersController extends Controller 
{
	public $module = 'users';

	public function creer($usersData) {
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

	public function afficher($user_id) {
		$users = new Users();
		$user_data = $users->indById($user_id);
		if ($user_data === false) {
			return $this->render("test", $user_data);
		} else {
			return $this->render("test", $user_data);
		}
	}

	public function lister() {
		$users = new Users();
		$users_data = $users->findAll();
		if ($users_data === false) {
			return $this->render("test", $users_data);
		} else {
			return $this->render("test", $users_data);
		}
	}

}
