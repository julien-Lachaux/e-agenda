<?php
namespace Source\cli;

use Source\Utils;
use Source\cli\convertisseurs\JsonVersSql;

class Migration
{
    private $cheminDossierConfig;
    private $cheminDossierMigration;
    private $cheminDossierModule;
    private $requetesBase;
    private $requetesModules;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->cheminDossierConfig = __DIR__ . "/../../../config/orm";
        $this->cheminDossierMigration = __DIR__ . "/../../../migrations";
        $this->cheminDossierModule = __DIR__ . "/../../../src/modules";
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
        Utils::consoleLog("PREPARATION DE LA BASE POUR LA MIGRATION", "title");
        $baseJson = Utils::recupererContenuFichier($this->cheminDossierConfig . "/@base.json");
        if ($baseJson !== false) {
            $base = JsonVersSql::convertirBase($baseJson);
            if ($base !== false) {
                $this->requetesBase = $base["requete"];
                Utils::consoleLog("OK: base {$base["nom"]}");
            } else {
                Utils::consoleLog("ERREUR: fichier json invalide");
            }
        } else {
            Utils::consoleLog("ERREUR: fichier de configuration introuvable");
        }

        // on prepares les modules (tables + relationnels)
        if ($dossierSrc = opendir($this->cheminDossierModule)) {
            Utils::consoleLog("PREPARATION DES MODULES POUR LA MIGRATION", "title");

            while(false !== ($sousDossierModule = readdir($dossierSrc))) {
                if ($sousDossierModule !== '.' && $sousDossierModule !== '..' && $sousDossierModule !== '.DS_Store' && $sousDossierModule !== "pages") {
                    $cheminTableJson = $this->cheminDossierModule . "/{$sousDossierModule}/@table.json";
                    $tableJson = Utils::recupererContenuFichier($cheminTableJson);

                    
                    if ($tableJson !== false) {
                        $requetesTable = JsonVersSql::convertirTable($tableJson);
                        if ($requetesTable !== false) {
                            $this->requetesModules["tables"][] = $requetesTable["table"];
                            if ($requetesTable["relationnels"] !== []) {
                                $this->requetesModules["relationnels"][] = $requetesTable["relationnels"];
                            }
                            Utils::consoleLog("OK : module " . $sousDossierModule);
                        } else {
                            Utils::consoleLog("ERRUR : module " . $sousDossierModule . " -- json invalide");
                        }
                    } else {
                        Utils::consoleLog("ERRUR : module " . $sousDossierModule . " -- json introuvable");
                    }
                }
            }
            closedir($dossierSrc);
        }
        $this->ecrireFichierMigration();
    }

    /**
     * ecrit le fichier sql de migration
     *
     * @return void
     */
    private function ecrireFichierMigration() {
        $fichierMigration = fopen($this->cheminDossierMigration . "/@migration.sql", "w+");
        $migrationSql = $this->requetesBase;

        foreach ($this->requetesModules["tables"] as $table => $requete) {
            $migrationSql .= $requete;
        }
        foreach ($this->requetesModules["relationnels"] as $table => $requetes) {
            foreach ($requetes as $key => $requete) {
                $migrationSql .= $requete;
            }
        }
        fwrite($fichierMigration, $migrationSql);
        fclose($fichierMigration);

        Utils::consoleLog("MIGRATION PREPARER AVEC SUCCES", "title");
        Utils::consoleLog("fichier de migration SQL generer au chemin : " . $this->cheminDossierMigration . "/@migration.sql\n");
    }

}
