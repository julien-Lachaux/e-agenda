<?php

namespace Modules\adresses;

use Source\Controller;
use Modules\adresses\Adresses;

class AdressesController extends Controller 
{
	public $module = 'adresses';

	/**
	 * Creer un nouveau: adresse
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function creer($requete) {
		$adressesData = $requete->getBody();
		if (Adresse::valider($requete->getBody())) {
			$Adresse = new Adresse($adressesData);
			$data = $Adresse->creer();
			if ($data !== false) {
				return $this->render("creation", array("nouveauadresses" => $data));
			}
			return $this->render("creation", array(
				"erreur" => ["message" => "adresses inconnue"]
			));
		}
		return $this->render("creation", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher les informations d'un adresse
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function afficher($requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$adresse_id = $urlParams[0];
			$Adresses = new Adresses();
			$data = $Adresses->findById($adresse_id);
			if ($data !== false) {
				return $this->render("affichage", array("adresse" => $data));
			}
			return $this->render("affichage", array(
				"erreur" => ["message" => "adresse inconnue"]
			));
		}
		return $this->render("affichage", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher la liste des adresses
	 *
	 * @param Object $requete
	 * @return String
	 */
	public function lister($requete) {
		$Adresses = new Adresses();
		$data = $Adresses->findAll();
		if ($data !== false) {
			return $this->render("lister", array("adresses" => $data));
		}
		return $this->render("lister", array(
			"erreur" => ["message" => "aucun adresses"]
		));
	}

}
