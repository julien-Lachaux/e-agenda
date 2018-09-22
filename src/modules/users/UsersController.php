<?php
namespace Modules\users;

use Source\Controller;
use Modules\users\User;
use Modules\users\Users;

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
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$user_id = $urlParams[0];
			$users = new Users();
			$user_data = $users->findById($user_id);
			if ($user_data !== false) {
				return $this->render("user", [$user_data]); // le user existe
			} else {
				return $this->render("erreurs", array(
					"erreur" => [
						"code" => 404,
						"message" => "Utilisateur {$user_id} n'existe pas"
					]
				)); // le user n'existe pas
			}
		} else {
			return $this->render("erreur", [
				"erreur" => [
					"code" => 404,
					"message" => "Utilisateur {$user_id} n'existe pas"
				]
			]); // il manque l'id du user à afficher
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