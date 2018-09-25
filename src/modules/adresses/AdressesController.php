<?php

namespace Modules\adresses;

use Source\Utils;
use Source\Requete;
use Source\Controller;
use Modules\adresses\Adresse;
use Modules\adresses\Adresses;

class AdressesController extends Controller 
{
	public $module = 'adresses';

	/**
	 * Creer un nouveau: adresse
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function creer(Requete $requete) {
		$adresseData = $requete->getBody();
		if (Adresse::valider($requete->getBody())) {
			$Adresse = new Adresse($adresseData);
			$data = $Adresse->creer();
			if ($data !== false) {
				return $this->render("formulaire", array("creationReussi" => true)
				);
			}
			return $this->render("formulaire", array(
				"erreur" => ["message" => "formulaire invalide"]
			));
		}
		return $this->render("formulaire", array(
			"erreur" => ["message" => "id manquant"]
		));
	}

	/**
	 * Afficher les informations d'un adresse
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function afficher(Requete $requete) {
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
	 * @param Requete $requete
	 * @return HTML
	 */
	public function lister(Requete $requete) {
		$Adresses = new Adresses();
		$data = $Adresses->findAll();
		if ($data !== false) {
			return $this->render("lister", array("adresses" => $data));
		}
		return $this->render("lister", array(
			"erreur" => ["message" => "aucun adresses"]
		));
	}

	/**
	 * Afficher la liste des adresses
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function editer(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$adresse_id = $urlParams[0];
			$Adresses = new Adresses();
			$data = $Adresses->findById($adresse_id);

			if ($data !== false) {
				$formulaire = $requete->getBody();
				$test = Adresse::valider($formulaire);
				if ($test !== false) {
					foreach($formulaire as $colonne => $valeur) {
						if ($data->{$colonne} !== $valeur) {
							$test = Adresse::update($colonne, $valeur, "id={$adresse_id}");
						}
					}
					$adresseMisAJour = $Adresses->findById($adresse_id);
					return $this->render("formulaire", array(
						"editionReussi" => true,
						"adresse"		=> $adresseMisAJour
					)); // adresse editer avec succes
				}
				return $this->render("formulaire", array(
					"adresse" => $formulaire,
					"erreur" => [
						"message" => "formulaire invalide"
					]
				)); // formulaire invalide
			}
			return $this->render("formulaire", array(
				"erreur" => [
					"message" => "adresse introuvable"
				]
			));  // l'adresse n'existe pas
		}
		return $this->render("formulaire", array(
			"erreur" => [
				"message" => "requete invalide"
			]
		)); // il manque l'id
	}

	/**
	 * Supprime un utilisateur
	 *
	 * @param Requete $requete
	 * @return HTML
	 */
	public function supprimer(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) {
			$id = $urlParams[0];
			if(Adresse::supprimer($id) !== false) {
				return $this->render("supprimer", array( // suppresion reussi
					"reussite" => [
						"id" => $id
					]
				));
			}
			return $this->render("supprimer", array(
				"erreur" => ["message" => "adresse inconnue"] // adresse inconnue
			));
		}
		return $this->render("supprimer", array(
			"erreur" => [
				"message" => "id manquant" // pas d'id dans la requete
			]
		));
	}

	public function formulaire(Requete $requete) {
		$urlParams = $requete->getUrlParams();
		if (isset($urlParams[0]) && is_numeric($urlParams[0])) { // c'est une edition
			$id = $urlParams[0];
			$Adresses = new Adresses();
			$data = $Adresses->findById($id);
			if ($data !== false) {
				return $this->render("formulaire", array("adresse" => $data));
			}
		}
		// sinon c'est une creation
		return $this->render("formulaire", []);
	}

}
