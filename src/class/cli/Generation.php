<?php
namespace Source\cli;

use Source\Utils;
use Source\cli\convertisseurs\JsonVersSql;
use Source\cli\generateurs\Generateur;
use Source\cli\generateurs\GenerateurDepots;
use Source\cli\generateurs\GenerateurModels;
use Source\cli\generateurs\GenerateurControllers;

class Generation extends Generateur
{

    public function generer() {
        // on recupere les configurations de la base et des tables
        $config = $this->recupererConfig();

        // on generes les class Depot
        $generateurDepots = new GenerateurDepots();
        $generateurDepots->genererDepots($config);

        // on generes les class Model
        $generateurModels = new GenerateurModels();
        $generateurModels->genererModels($config);

        // on generes les class Controller
        $generateurControllers = new GenerateurControllers();
        $generateurControllers->genererControllers($config);
    }

    private function recupererConfig() {
         // on recupere la configuration de la base
         $baseJson = Utils::recupererContenuFichier($this->cheminDossierConfig . "/@base.json");
         if ($baseJson !== false) {
             $base = json_decode(utf8_encode($baseJson), false)->base;
         } else {
             Utils::consoleLog("ERREUR: fichier de configuration introuvable");
         }
 
         $tables = [];
         // on recupere les configurations des tables
         if ($dossierSrc = opendir($this->cheminDossierModule)) {
             while(false !== ($sousDossierModule = readdir($dossierSrc))) {
                 if ($sousDossierModule !== '.' && $sousDossierModule !== '..' && $sousDossierModule !== '.DS_Store') {
                     $cheminTableJson = $this->cheminDossierModule . "/{$sousDossierModule}/@table.json";
                     $tableJson = Utils::recupererContenuFichier($cheminTableJson);
 
                     if ($tableJson !== false) {
                         $table = json_decode(utf8_encode($tableJson), false)->table;
                         $tables[$table->nom] = $table;
                     } else {
                         Utils::consoleLog("ERRUR : module " . $sousDossierModule . " -- json introuvable");
                     }
                 }
             }
             closedir($dossierSrc);
         }
         $config = [
             "base"      => $base,
             "tables"    => $tables
         ];

         return $config;
    }
    
}
