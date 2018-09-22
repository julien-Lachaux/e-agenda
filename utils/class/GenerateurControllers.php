<?php
require_once('Generateur.php');

class GenerateurControllers extends Generateur
{
    public function genererControllers($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomDepot = ucfirst($table->nom);

            // on genere les models
            $fichierDepot = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomDepot}Controller.php", "w+");

            $nouveauDepot = $this->genererClassHeader($nomDepot, "Depot");
            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
        }
    }

    private function genererMethodeCreer() {}        

    private function genererMethodeAfficher() {}

    private function genererMethodeLister() {}

    private function genererMethodeEditer() {}

    private function genererMethodeSupprimer() {}
}