<?php
namespace Source\cli\generateurs;

use Source\cli\generateurs\Generateur;

class GenerateurDepots extends Generateur
{
    public function genererDepots($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomDepot = ucfirst($table->nom);

            // on genere les models
            $fichierDepot = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomDepot}.php", "w+");

            $nouveauDepot = $this->genererClassHeader($nomDepot, "Depot");
            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
        }
    }
}