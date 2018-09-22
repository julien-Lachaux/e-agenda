<?php
namespace Modules\adresses;

use Source\Controller;

class AdressesController extends Controller 
{
	static public function creer($adressesData) {
		if (Adresse::valider($adressesData)) {
			$Adresse = new Adresse($adressesData);
			$data = $Adresse->creer();
			if ($data) {
				self::render("CHEMIN VUE SUCCESS CREATION", $data);
			} else {
				self::render("CHEMIN VUE EXISTE DEJA CREATION", $data);
			}
		} else {
			self::render("CHEMIN VUE ERREUR DONNEE CREATION", $data);
		}
	}

	static public function afficher($adresse_id) {
		$adresse_data = Adresses::findById($adresse_id);
		if ($adresse_data === false) {
			self::render("CHEMIN VUE EXISTE PAS AFFICHER", $adresse_data);
		} else {
			self::render("CHEMIN VUE AFFICHER", $adresse_data);
		}
	}

	static public function lister() {
		$adresses_data = Adresses::findAll();
		if ($adresses_data === false) {
			self::render("CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST", $adresses_data);
		} else {
			self::render("CHEMIN VUE LISTE", $adresses_data);
		}
	}

}
