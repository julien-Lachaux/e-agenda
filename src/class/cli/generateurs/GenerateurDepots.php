<?php
namespace Source\cli\generateurs;

use Source\Utils;
use Source\cli\generateurs\Generateur;

class GenerateurDepots extends Generateur
{

    /**
     * genère un depot pour un module
     *
     * @param Array $config
     * @return void
     */
    public function genererDepots($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomDepot = ucfirst($table->nom);
            
            // on genere les models
            $fichierDepot   = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomDepot}.php", "w+");
            $nouveauDepot  = $this->genererClassHeader($nomDepot, $table->nom, "Depot");
            $nouveauDepot .= $this->ajouterLignePhp("");
            $nouveauDepot .= $this->ajouterLignePhp("protected static \$table = '{$table->nom}';", 1, 2);
            $nouveauDepot .= $this->ajouterLignePhp("}");
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
            Utils::consoleLog("Depot generer avec succes: {$nomDepot}");
        }
    }

}