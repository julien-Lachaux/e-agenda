<?php
require_once('src/class/Controller.php');

class AdressesController extends Controller 
{
	static public function creer($adressesData) {
		if (Adresse::valider($adressesData)) {
			$adresse = new Adresse($adressesData);
			if ($adresse->creer()) {
				echo "adresse creer avec success";
			} else {
				echo "adresse existe deja";
			}
		} else {
			echo "data invalide pour creer adresse}";
		}
	}

}
