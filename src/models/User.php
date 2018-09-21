<?php

class User extends Model 
{

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
}
