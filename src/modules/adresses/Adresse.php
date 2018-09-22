<?php
namespace Modules\adresses;

use Source\Model;

class Adresse extends Model 
{

	private $id;
	private $pays;
	private $ville;
	private $codePostal;
	private $adresse;
	private $complementAdresse;
	private $contact_id;

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
	 * Retourne la valeur de pays
	 *
	 * @return String
	 */
	public function getPays($valeur) {
		 return $this->pays;
	}

	/**
	 * Affecte la valeur $valeur à pays
	 *
	 * @param String $pays
	 * @return String
	 */
	public function setPays($valeur) {
		$this->pays = $valeur;
	}

	/**
	 * Retourne la valeur de ville
	 *
	 * @return String
	 */
	public function getVille($valeur) {
		 return $this->ville;
	}

	/**
	 * Affecte la valeur $valeur à ville
	 *
	 * @param String $ville
	 * @return String
	 */
	public function setVille($valeur) {
		$this->ville = $valeur;
	}

	/**
	 * Retourne la valeur de codePostal
	 *
	 * @return String
	 */
	public function getCodePostal($valeur) {
		 return $this->codePostal;
	}

	/**
	 * Affecte la valeur $valeur à codePostal
	 *
	 * @param String $codePostal
	 * @return String
	 */
	public function setCodePostal($valeur) {
		$this->codePostal = $valeur;
	}

	/**
	 * Retourne la valeur de adresse
	 *
	 * @return String
	 */
	public function getAdresse($valeur) {
		 return $this->adresse;
	}

	/**
	 * Affecte la valeur $valeur à adresse
	 *
	 * @param String $adresse
	 * @return String
	 */
	public function setAdresse($valeur) {
		$this->adresse = $valeur;
	}

	/**
	 * Retourne la valeur de complementAdresse
	 *
	 * @return String
	 */
	public function getComplementAdresse($valeur) {
		 return $this->complementAdresse;
	}

	/**
	 * Affecte la valeur $valeur à complementAdresse
	 *
	 * @param String $complementAdresse
	 * @return String
	 */
	public function setComplementAdresse($valeur) {
		$this->complementAdresse = $valeur;
	}

	/**
	 * Retourne la valeur de contact_id
	 *
	 * @return Void
	 */
	public function getContact_id($valeur) {
		 return $this->contact_id;
	}

	/**
	 * Affecte la valeur $valeur à contact_id
	 *
	 * @param Void $contact_id
	 * @return Void
	 */
	public function setContact_id($valeur) {
		$this->contact_id = $valeur;
	}

	/**
	 * Valide les données de Adresse
	 *
	 * @param Object $AdresseData
	 * @return Boolean
	 */
	public function valider($AdresseData) {
		foreach ($AdresseData as $data) {
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
	 * Retourne la valeur de Adresse
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

		$creation = Base::getInstance()->query("INSERT INTO adresses ({$colonnesString}) VALUES({$valeursString})");

		if ($creation === false) { return false; }

		return true;
	}
	public function getContact() {
		return Contacts::findById($this->id);
	}

}
