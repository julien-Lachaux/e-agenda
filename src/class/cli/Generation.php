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

    /**
     * genère les modules gràces au fichhier @base.json
     *
     * @return void
     */
    public function generer() {
        Utils::consoleLog("DEBUT DE LA GENERATIONS DES CLASSES", "title");
        
        // on recupere les configurations de la base et des tables
        Utils::consoleLog("récuperation de la configuration...");
        $config = $this->recupererConfig();
        
        // on generes les class Depot
        Utils::consoleLog("GENERATION DES CLASSES DEPOTS", "title");
        $generateurDepots = new GenerateurDepots();
        $generateurDepots->genererDepots($config);
        
        // on generes les class Model
        Utils::consoleLog("GENERATION DES CLASSES MODELS", "title");
        $generateurModels = new GenerateurModels();
        $generateurModels->genererModels($config);
        
        // on generes les class Controller
        Utils::consoleLog("GENERATION DES CLASSES CONTROLLERS", "title");
        $generateurControllers = new GenerateurControllers();
        $generateurControllers->genererControllers($config);

        Utils::consoleLog("FIN DE LA GENERATION", "title");
    }

    /**
     * recupère la configuration des tables a creer pour les modules
     *
     * @return void
     */
    private function recupererConfig() {
        // on recupere la configuration de la base
        $baseJson = Utils::recupererContenuFichier($this->cheminDossierConfig . "/@base.json");
        if ($baseJson !== false) {
            $base = json_decode(utf8_encode($baseJson), false)->base;
        } else {
            Utils::consoleLog("ERREUR: fichier de configuration introuvable");
        }
 
        // on recupere les configurations des tables
        $tables = [];
        if ($dossierSrc = opendir($this->cheminDossierModule)) {
            while(false !== ($sousDossierModule = readdir($dossierSrc))) {
                if ($sousDossierModule      !== '.'
                    && $sousDossierModule   !== '..'
                    && $sousDossierModule   !== '.DS_Store'
                    && $sousDossierModule   !== 'pages'
                    && $sousDossierModule   !== 'AutoloaderModules.php') {
                    $cheminTableJson = $this->cheminDossierModule . "/{$sousDossierModule}/@table.json";
                    $tableJson = Utils::recupererContenuFichier($cheminTableJson);

                    if ($tableJson !== false) {
                        $table = json_decode(utf8_encode($tableJson), false)->table;
                        Utils::consoleLog("table OK: {$table->nom}");
                        $tables[$table->nom] = $table;
                    } else {
                        Utils::consoleLog("ERREUR : module " . $sousDossierModule . " -- json introuvable");
                    }
                }
            }
            closedir($dossierSrc);
        }
        $config = [
            "base"      => $base,
            "tables"    => $tables
        ];
        Utils::consoleLog("configuration: charger !");
        return $config;
    }
    
}
