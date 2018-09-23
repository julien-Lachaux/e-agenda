<?php

namespace Modules\users;

use Source\Utils;
use Source\Controller;
use Modules\users\User;
use Modules\users\Users;

class UsersController extends Controller 
{
	public $module = 'users';

	/**
	 * Creer un nouveau: user
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function creer($requete) {
		$usersData = $requete->getBody();
		if (User::valider($requete->getBody())) {
			$User = new User($usersData);
			$data = $User->creer();
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
	 * Afficher les informations d'un user
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function afficher($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$user_id = $urlParams[0];
			$Users = new Users();
			$data = $Users->findById($user_id);
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
	 * Afficher la liste des users
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function lister($requete) {
		$Users = new Users();
		$data = $Users->findAll();
		if ($data !== false) {
			return $this->render("usersList", $data);
		} else {
			return $this->render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $data);
		}
	}

	/**
	 * Afficher la liste des users
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function editer($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$user_id = $urlParams[0];
			$Users = new Users();
			$data = $Users->findById($user_id);

			if ($data !== false) {
				$formulaire = $requete->getBody();
				$test = User::valider($formulaire);
				if ($test !== false) {
					$colonneEditer = [];
					foreach($formulaire as $colonne => $valeur) {
						if ($data->{$colonne} !== $valeur) {
							$colonneEditer[] = array("nom" => "$colonne");
							$test = User::update($colonne, $valeur, "id={$user_id}");
						}
					}
					return $this->render("editionReussi", array("colonneEditer" => $colonneEditer)); // user editer avec succes
				} else {
					return $this->render("usersList", $data); // formulaire invalide
				}
			} else {
				return $this->render("usersList", $data); // le user n'existe pas
			}
		} else {
			return $this->render("usersList", $data); // il manque l'id
		}
	}

}
