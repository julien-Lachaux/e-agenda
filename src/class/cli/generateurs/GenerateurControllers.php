<?php
namespace Source\cli\generateurs;

use Source\Utils;
use Source\cli\generateurs\Generateur;

class GenerateurControllers extends Generateur
{
    public function genererControllers($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomController  = ucfirst($table->nom) . "Controller";
            $nomModel       = substr(ucfirst($table->nom), 0, -1);
            $nomDepot       = ucfirst($table->nom);

            // on genere les models
            $fichierDepot = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomController}.php", "w+");

            $nouveauDepot = $this->genererClassHeader($nomController, $table->nom, "Controller", false, [
                (object) [
                    "source" => "Modules",
                    "nom"    => "{$table->nom}\\{$nomModel}"
                ],
                (object) [
                    "source" => "Modules",
                    "nom"    => "{$table->nom}\\{$nomDepot}"
                ]
            ]);

            $nouveauDepot .= $this->ajouterLignePhp("public \$module = '{$table->nom}';", 1, 2);

            $nouveauDepot .= $this->genererMethodeCreer($table);
            $nouveauDepot .= $this->genererMethodeAfficher($table);
            $nouveauDepot .= $this->genererMethodeLister($table);
            //$nouveauDepot .= $this->genererMethodeEditer($table);
            //$nouveauDepot .= $this->genererMethodeSupprimer($table);

            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
            Utils::consoleLog("Controller generer avec succes: {$nomController}");
        }
    }

    private function genererMethodeCreer($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomModel               = substr(ucfirst($nomTable), 0, -1);
        $nomController          = substr($nomTable, 0, -1);
        $cheminVue              = "creation";

        // on genere le commentaire
        $this->ajouterLignePhp("", 1);
        $methodeCreer = $this->genererCommentaireMethode("Creer un nouveau: {$nomController}", [(object)[
            "type" => "Object",
            "nom" => "requete"
        ]], "String");

        // on genere la methode creer
        $methodeCreer .= $this->ajouterLignePhp("public function creer(\$requete) {", 1);
        $methodeCreer .= $this->ajouterLignePhp("\${$nomTable}Data = \$requete->getBody();", 2);
        $methodeCreer .= $this->ajouterLignePhp("if ({$nomModel}::valider(\$requete->getBody())) {", 2);
        $methodeCreer .= $this->ajouterLignePhp("\${$nomModel} = new {$nomModel}(\${$nomTable}Data);", 3);
        $methodeCreer .= $this->ajouterLignePhp("\$data = \${$nomModel}->creer();", 3);
        $methodeCreer .= $this->ajouterLignePhp("if (\$data !== false) {", 3);
        $methodeCreer .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(\"nouveau{$nomTable}\" => \$data));", 4);
        $methodeCreer .= $this->ajouterLignePhp("}", 3);
        $methodeCreer .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(", 3);
        $methodeCreer .= $this->ajouterLignePhp("\"erreur\" => [\"message\" => \"{$nomTable} inconnue\"]", 4);
        $methodeCreer .= $this->ajouterLignePhp("));", 3);
        $methodeCreer .= $this->ajouterLignePhp("}", 2);
        $methodeCreer .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(", 2);
        $methodeCreer .= $this->ajouterLignePhp("\"erreur\" => [\"message\" => \"id manquant\"]", 3);
        $methodeCreer .= $this->ajouterLignePhp("));", 2);
        $methodeCreer .= $this->ajouterLignePhp("}", 1, 2);

        return $methodeCreer;
    }        

    private function genererMethodeAfficher($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomTableSinguler       = substr($nomTable, 0, -1);
        $nomDepot               = ucfirst($nomTable);
        $nomParametre           = substr($nomTable, 0, -1) . "_id";
        $nomVariable            = substr($nomTable, 0, -1) . "_data";
        $cheminVue              = "affichage";

        // on genere le commentaire
        $methodeAfficher = $this->genererCommentaireMethode("Afficher les informations d'un {$nomTableSinguler}", [(object)[
            "type" => "Object",
            "nom" => "requete"
        ]], "String");

        // on genere la methode afficher
        $methodeAfficher .= $this->ajouterLignePhp("public function afficher(\$requete) {", 1);
        $methodeAfficher .= $this->ajouterLignePhp("\$urlParams = \$requete->getUrlParams();", 2);
        $methodeAfficher .= $this->ajouterLignePhp("if (isset(\$urlParams[0]) && is_numeric(\$urlParams[0])) {", 2);
        $methodeAfficher .= $this->ajouterLignePhp("\${$nomParametre} = \$urlParams[0];", 3);
        $methodeAfficher .= $this->ajouterLignePhp("\${$nomDepot} = new {$nomDepot}();", 3);
        $methodeAfficher .= $this->ajouterLignePhp("\$data = \${$nomDepot}->findById(\${$nomParametre});", 3);
        $methodeAfficher .= $this->ajouterLignePhp("if (\$data !== false) {", 3);
        $methodeAfficher .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(\"{$nomTableSinguler}\" => \$data));", 4);
        $methodeAfficher .= $this->ajouterLignePhp("}", 3);
        $methodeAfficher .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(", 3);
        $methodeAfficher .= $this->ajouterLignePhp("\"erreur\" => [\"message\" => \"{$nomTableSinguler} inconnue\"]", 4);
        $methodeAfficher .= $this->ajouterLignePhp("));", 3);
        $methodeAfficher .= $this->ajouterLignePhp("}", 2);
        $methodeAfficher .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(", 2);
        $methodeAfficher .= $this->ajouterLignePhp("\"erreur\" => [\"message\" => \"id manquant\"]", 3);
        $methodeAfficher .= $this->ajouterLignePhp("));", 2);
        $methodeAfficher .= $this->ajouterLignePhp("}", 1, 2);

        return $methodeAfficher;
    }

    private function genererMethodeLister($config) {
        // on formatte nos informations
        $nomTable               = $config->nom;
        $nomDepot               = ucfirst($nomTable);
        $cheminVue              = "lister";

        // on genere le commentaire
        $methodeLister = $this->genererCommentaireMethode("Afficher la liste des {$nomTable}", [(object)[
            "type" => "Object",
            "nom" => "requete"
        ]], "String");

        // on genere la methode lister
        $methodeLister .= $this->ajouterLignePhp("public function lister(\$requete) {", 1);
        $methodeLister .= $this->ajouterLignePhp("\${$nomDepot} = new {$nomDepot}();", 2);
        $methodeLister .= $this->ajouterLignePhp("\$data = \${$nomDepot}->findAll();", 2);
        $methodeLister .= $this->ajouterLignePhp("if (\$data !== false) {", 2);
        $methodeLister .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(\"{$nomTable}\" => \$data));", 3);
        $methodeLister .= $this->ajouterLignePhp("}", 2);
        $methodeLister .= $this->ajouterLignePhp("return \$this->render(\"{$cheminVue}\", array(", 2);
        $methodeLister .= $this->ajouterLignePhp("\"erreur\" => [\"message\" => \"aucun {$nomTable}\"]", 3);
        $methodeLister .= $this->ajouterLignePhp("));", 2);
        $methodeLister .= $this->ajouterLignePhp("}", 1, 2);
        
        

        return $methodeLister;
    }

    private function genererMethodeEditer($config) {
        // TO DO
    }

    private function genererMethodeSupprimer($config) {
        // TO DO
    }
}