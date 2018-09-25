<?php

namespace Modules\utilisateurs;

use Source\Base;
use Source\Model;
use Source\Utils;
use Modules\contacts\Contacts;

class Utilisateur extends Model 
{

	protected static $table = 'utilisateurs';

	/**
	 * Retourne la valeur de id
	 *
	 * @return Integer
	 */
	public function getId($valeur) {
		 return $this->id;
	}

	/**
	 * Affecte la valeur $valeur à id
	 *
	 * @param Integer $id
	 * @return Integer
	 */
	public function setId($valeur) {
		$this->id = $valeur;
	}

	/**
	 * Retourne la valeur de login
	 *
	 * @return String
	 */
	public function getLogin($valeur) {
		 return $this->login;
	}

	/**
	 * Affecte la valeur $valeur à login
	 *
	 * @param String $login
	 * @return String
	 */
	public function setLogin($valeur) {
		$this->login = $valeur;
	}

	/**
	 * Retourne la valeur de password
	 *
	 * @return String
	 */
	public function getPassword($valeur) {
		 return $this->password;
	}

	/**
	 * Affecte la valeur $valeur à password
	 *
	 * @param String $password
	 * @return String
	 */
	public function setPassword($valeur) {
		$this->password = $valeur;
	}

	/**
	 * Retourne la valeur de nom
	 *
	 * @return String
	 */
	public function getNom($valeur) {
		 return $this->nom;
	}

	/**
	 * Affecte la valeur $valeur à nom
	 *
	 * @param String $nom
	 * @return String
	 */
	public function setNom($valeur) {
		$this->nom = $valeur;
	}

	/**
	 * Retourne la valeur de prenom
	 *
	 * @return String
	 */
	public function getPrenom($valeur) {
		 return $this->prenom;
	}

	/**
	 * Affecte la valeur $valeur à prenom
	 *
	 * @param String $prenom
	 * @return String
	 */
	public function setPrenom($valeur) {
		$this->prenom = $valeur;
	}

	/**
	 * Valide les données de Utilisateur
	 *
	 * @param Object $UtilisateurData
	 * @return Boolean
	 */
	public static function valider($UtilisateurData) {
		foreach ($UtilisateurData as $data) {
			if (gettype($data) !== 'string'
			 && gettype($data) !== 'integer'
			 && gettype($data) !== 'boolean'
			 && gettype($data) !== 'NULL') {
				return false;
			}
		}
		return true;
	}

	/**
	 * Retourne la valeur de Utilisateur
	 *
	 * @return Boolean
	 */
	public function creer() {
		$colonnesString = "";
		$valeursString = "";
		$colonnes = get_object_vars($this);

		foreach ($colonnes as $colonne => $valeur) {
			$colonnesString .= "{$colonne}, ";
			if ($colonne === "password") {
				$valeur = password_hash($valeur, PASSWORD_DEFAULT);
			}
			$valeursString .= "'{$valeur}', ";
		}

		$colonnesString = substr($colonnesString, 0, -2);
		$valeursString = substr($valeursString , 0, -2);

		$creation = Base::getInstance()->query("INSERT INTO utilisateurs ({$colonnesString}) VALUES({$valeursString})");

		if ($creation === false) { return false; }

		return true;
	}

	/**
	 * Retourne la liste des contacts de l'utilisateur
	 *
	 * @return Array|Boolean
	 */
	public function getContacts() {
		return Contacts::findMany("utilisateurs_id={$_SESSION['utilisateur']->id}");
	}
}
