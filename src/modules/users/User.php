<?php
require_once('src/class/Model.php');

class User extends Model 
{

	private static $table = 'users';
	private $id;
	private $login;
	private $password;
	private $email;
	private $nom;
	private $prenom;

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
	 * Retourne la valeur de email
	 *
	 * @return String
	 */
	public function getEmail($valeur) {
		 return $this->email;
	}

	/**
	 * Affecte la valeur $valeur à email
	 *
	 * @param String $email
	 * @return String
	 */
	public function setEmail($valeur) {
		$this->email = $valeur;
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
	 * Valide les données de User
	 *
	 * @param Object $UserData
	 * @return Boolean
	 */
	public function valider($UserData) {
		foreach ($UserData as $data) {
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
	 * Retourne la valeur de User
	 *
	 * @return Boolean
	 */
	public function creer() {
		$colonnesString = "";
		$valeursString = "";
		$colonnes = get_object_vars($this);

		foreach ($colonnes as $colonne => $valeur) {
			$colonnesString .= "{$colonne}, ";
			$valeursString .= "{$valeur}, ";
		}

		$colonnesString = substr($colonnesString, 0, -2);
		$valeursString = substr($valeursString , 0, -2);

		$creation = Base::getInstance()->query("INSERT INTO users ({$colonnesString}) VALUES({$valeursString})");

		if ($creation === false) { return false; }

		return true;
	}
	public function getContacts() {
		return Base::getInstance()->query("SELECT * FROM users INNER JOIN Contact ON user.id=Contact.users_id WHERE Contact.users_id=''")->fetchObject();
	}

}
