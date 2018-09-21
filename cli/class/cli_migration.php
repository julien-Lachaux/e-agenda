<?php
require_once('cli_utils.php');
require_once('cli_jsonVersSql.php');

class cli_migration
{
    private $cheminDossierConfig;
    private $cheminDossierMigration;
    private $cheminDossierModule;
    private $requetesBase;
    private $requetesModules;

    public function __construct() {
        $this->cheminDossierConfig = __DIR__ . "/../../config/orm";
        $this->cheminDossierMigration = __DIR__ . "/../../migrations";
        $this->cheminDossierModule = __DIR__ . "/../../src/modules";
        $this->requetesModules = [
            "tables"        =>   [],
            "relationnels"  =>   []
        ];
    }

    /**
     * parepare la migration
     *
     * @return void
     */
    public function preparer() {
        // on prepare la base
        cli_utils::consoleLog("PREPARATION DE LA BASE POUR LA MIGRATION", "title");
        $baseJson = cli_utils::recupererContenuFichier($this->cheminDossierConfig . "/@base.json");
        if ($baseJson !== false) {
            $base = cli_jsonVersSql::convertirBase($baseJson);
            if ($base !== false) {
                cli_utils::consoleLog("OK: base {$base["nom"]}");
            } else {
                cli_utils::consoleLog("ERREUR: fichier json invalide");
            }
        } else {
            cli_utils::consoleLog("ERREUR: fichier de configuration introuvable");
        }

        // on prepares les modules (tables + relationnels)
        if ($dossierSrc = opendir($this->cheminDossierModule)) {
            cli_utils::consoleLog("PREPARATION DES MODULES POUR LA MIGRATION", "title");

            while(false !== ($sousDossierModule = readdir($dossierSrc))) {
                if ($sousDossierModule !== '.' && $sousDossierModule !== '..' && $sousDossierModule !== '.DS_Store') {
                    $cheminTableJson = $this->cheminDossierModule . "/{$sousDossierModule}/@table.json";
                    $tableJson = cli_utils::recupererContenuFichier($cheminTableJson);

                    
                    if ($tableJson !== false) {
                        $requetesTable = cli_jsonVersSql::convertirTable($tableJson);
                        if ($requetesTable !== false) {
                            $this->requetesModules["tables"][] = $requetesTable["table"];
                            if ($requetesTable["relationnels"] !== NULL) {
                                $this->requetesModules["relationnels"][] = $requetesTable["relationnels"];
                            }
                            cli_utils::consoleLog("OK : module " . $sousDossierModule);
                        } else {
                            cli_utils::consoleLog("ERRUR : module " . $sousDossierModule . " -- json invalide");
                        }
                    } else {
                        cli_utils::consoleLog("ERRUR : module " . $sousDossierModule . " -- json introuvable");
                    }
                }
            }
            closedir($dossierSrc);
        }
        cli_utils::consoleLog();
        // cli_utils::debug($this->requetesModules);
    }

    /**
     * ecrit le fichier sql de migration
     *
     * @return void
     */
    private function ecrireFichierMigration() {
        $fichierMigration = fopen(__DIR__ . "/../../migrations/{$table->nom}@table.sql", "w+");
        fwrite($fichierMigration, "{$this->requetesBase}\n{$requetesTables}\n{$requetesRelationnels}\n");
        fclose($fichierMigration);
    }

}
