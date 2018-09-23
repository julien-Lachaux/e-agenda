<?php

namespace modules\users;

use Source\Controller;

class UsersController extends Controller 
{
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
		if ($data === false) {
			return $this->render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $data);
		} else {
			return $this->render("CHEMIN VUE LISTE", $data);
		}
	}

}
