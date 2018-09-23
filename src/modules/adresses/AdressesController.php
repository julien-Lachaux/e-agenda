<?php

namespace Modules\adresses;

use Source\Controller;

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
				return $this->render("CHEMIN VUE AFFICHER", $data);
			} else {
				return $this->render("CHEMIN VUE EXISTE PAS AFFICHER", $data);
			}
		} else {
			return $this->render("CHEMIN VUE EXISTE PAS AFFICHER", $data);
		}
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
			return $this->render("CHEMIN VUE LISTE", $data);
		} else {
			return $this->render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $data);
		}
	}

}
