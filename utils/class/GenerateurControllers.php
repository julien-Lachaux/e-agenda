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
            $nouveauDepot .= $this->genererMethodeAfficher($table);
            $nouveauDepot .= $this->genererMethodeLister($table);
            //$nouveauDepot .= $this->genererMethodeEditer($table);
            //$nouveauDepot .= $this->genererMethodeSupprimer($table);

            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
        }
    }

    private function genererMethodeCreer($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomModel               = substr(ucfirst($nomTable), 0, -1);
        $nomController            = substr($nomTable, 0, -1);
        $cheminVue              = "CHEMIN VUE SUCCESS CREATION";
        $cheminVueErreurDonnee  = "CHEMIN VUE ERREUR DONNEE CREATION";
        $cheminVueExisteDeja    = "CHEMIN VUE EXISTE DEJA CREATION";

        // on genere la methode creer
        $methodeCreer  = "\tstatic public function creer(\${$nomTable}Data) {\n";
        $methodeCreer .= "\t\tif ({$nomModel}::valider(\${$nomTable}Data)) {\n";
        $methodeCreer .= "\t\t\t\${$nomModel} = new {$nomModel}(\${$nomTable}Data);\n";
        $methodeCreer .= "\t\t\t\$data = \${$nomModel}->creer();\n";
        $methodeCreer .= "\t\t\tif (\$data) {\n";
        $methodeCreer .= "\t\t\t\tself::render(\"{$cheminVue}\", \$data);\n";
        $methodeCreer .= "\t\t\t} else {\n";
        $methodeCreer .= "\t\t\t\tself::render(\"{$cheminVueExisteDeja}\", \$data);\n";
        $methodeCreer .= "\t\t\t}\n";
        $methodeCreer .= "\t\t} else {\n";
        $methodeCreer .= "\t\t\tself::render(\"{$cheminVueErreurDonnee}\", \$data);\n";
        $methodeCreer .= "\t\t}\n";
        $methodeCreer .= "\t}\n\n";

        return $methodeCreer;
    }        

    private function genererMethodeAfficher($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomDepot               = ucfirst($nomTable);
        $nomParametre           = substr($nomTable, 0, -1) . "_id";
        $nomVariable            = substr($nomTable, 0, -1) . "_data";
        $cheminVue              = "CHEMIN VUE AFFICHER";
        $cheminVueExistePas     = "CHEMIN VUE EXISTE PAS AFFICHER";

        // on genere la methode afficher
        $methodeAfficher  = "\tstatic public function afficher(\${$nomParametre}) {\n";
        $methodeAfficher .= "\t\t\${$nomVariable} = {$nomDepot}::findById(\${$nomParametre});\n";
        $methodeAfficher .= "\t\tif (\${$nomVariable} === false) {\n";
        $methodeAfficher .= "\t\t\tself::render(\"{$cheminVueExistePas}\", \${$nomVariable});\n";
        $methodeAfficher .= "\t\t} else {\n";
        $methodeAfficher .= "\t\t\tself::render(\"{$cheminVue}\", \${$nomVariable});\n";
        $methodeAfficher .= "\t\t}\n";
        $methodeAfficher .= "\t}\n\n";

        return $methodeAfficher;
    }

    private function genererMethodeLister($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomDepot               = ucfirst($nomTable);
        $nomVariable            = $nomTable . "_data";
        $cheminVue              = "CHEMIN VUE LISTE";
        $cheminVueTableVide     = "CHEMIN VUE AUCUNE ENTREE DANS LA BASE LIST";

        // on genere la methode lister
        $methodeLister  = "\tstatic public function lister() {\n";
        $methodeLister .= "\t\t\${$nomVariable} = {$nomDepot}::findAll();\n";
        $methodeLister .= "\t\tif (\${$nomVariable} === false) {\n";
        $methodeLister .= "\t\t\tself::render(\"{$cheminVueTableVide}\", \${$nomVariable});\n";
        $methodeLister .= "\t\t} else {\n";
        $methodeLister .= "\t\t\tself::render(\"{$cheminVue}\", \${$nomVariable});\n";
        $methodeLister .= "\t\t}\n";
        $methodeLister .= "\t}\n\n";

        return $methodeLister;
    }

    private function genererMethodeEditer($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomDepot               = ucfirst($nomTable);
        $nomVariable            = $nomTable . "_data";
        $cheminVue              = "CHEMIN VUE SUCCESS EDITER";
        $cheminVueTErreurDonnee = "CHEMIN VUE ERREUR DONNEE EDITER";
        $cheminVueErreurEdition = "CHEMIN VUE ERREUR EDITER";

        // on genere la methode lister
        $methodeEditer  = "\tstatic public function editer(\$newData) {\n";
        $methodeEditer .= "\t\t\${$nomVariable} = {$nomDepot}::findAll();\n";
        $methodeEditer .= "\t\tif (\${$nomVariable} === false) {\n";
        $methodeEditer .= "\t\t\tself::render(\"{$cheminVueErreurEdition}\", \${$nomVariable});\n";
        $methodeEditer .= "\t\t} else {\n";
        $methodeEditer .= "\t\t\tself::render(\"{$cheminVue}\", \${$nomVariable});\n";
        $methodeEditer .= "\t\t}\n";
        $methodeEditer .= "\t}\n\n";

        return $methodeEditer;
    }

    private function genererMethodeSupprimer($config) {}
}