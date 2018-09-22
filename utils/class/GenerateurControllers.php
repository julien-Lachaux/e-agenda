<?php
require_once('Generateur.php');

class GenerateurControllers extends Generateur
{
    public function genererControllers($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomController = ucfirst($table->nom) . "Controller";

            // on genere les models
            $fichierDepot = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomController}.php", "w+");

            $nouveauDepot = $this->genererClassHeader($nomController, "Controller");

            $nouveauDepot .= $this->genererMethodeCreer($table);
            //$nouveauDepot .= $this->genererMethodeAfficher($table);
            //$nouveauDepot .= $this->genererMethodeLister($table);
            //$nouveauDepot .= $this->genererMethodeEditer($table);
            //$nouveauDepot .= $this->genererMethodeSupprimer($table);

            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
        }
    }

    private function genererMethodeCreer($config) {
        $nomTable = $config->nom;
        $nomModel = substr(ucfirst($config->nom), 0, -1);
        $nomVariable = substr($config->nom, 0, -1);

        $methodeCreer  = "\tstatic public function creer(\${$nomTable}Data) {\n";
        $methodeCreer .= "\t\tif ({$nomModel}::valider(\${$nomTable}Data)) {\n";
        $methodeCreer .= "\t\t\t\${$nomVariable} = new {$nomModel}(\${$nomTable}Data);\n";
        $methodeCreer .= "\t\t\tif (\${$nomVariable}->creer()) {\n";
        $methodeCreer .= "\t\t\t\techo \"{$nomVariable} creer avec success\";\n";
        $methodeCreer .= "\t\t\t} else {\n";
        $methodeCreer .= "\t\t\t\techo \"{$nomVariable} existe deja\";\n";
        $methodeCreer .= "\t\t\t}\n";
        $methodeCreer .= "\t\t} else {\n";
        $methodeCreer .= "\t\t\techo \"data invalide pour creer $nomVariable}\";\n";
        $methodeCreer .= "\t\t}\n";
        $methodeCreer .= "\t}\n\n";

        return $methodeCreer;
    }        

    private function genererMethodeAfficher($config) {}

    private function genererMethodeLister($config) {}

    private function genererMethodeEditer($config) {}

    private function genererMethodeSupprimer($config) {}
}