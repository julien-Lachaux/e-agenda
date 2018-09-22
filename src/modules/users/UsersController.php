<?php
require_once('src/class/Controller.php');

class UsersController extends Controller 
{
	static public function creer($usersData) {
		if (User::valider($usersData)) {
			$user = new User($usersData);
			if ($user->creer()) {
				echo "user creer avec success";
			} else {
				echo "user existe deja";
			}
		} else {
			echo "data invalide pour creer user}";
		}
	}

}
