<?php
require_once('cli_utils.php');

class cli_migration
{
    /**
     * parepare la migration
     *
     * @return void
     */
    public function preparer() {
        if($dossierSrc = opendir(__DIR__ . '/../../src/modules')) {
            echo "debut de la preparation des modules pour la migration \n";
            while(false !== ($sousDossierModule = readdir($dossierSrc))) {
                if ($sousDossierModule !== '.' && $sousDossierModule !== '..' && $sousDossierModule !== '.DS_Store') {
                    if ($this->convertirJsonVersSql($sousDossierModule)) {
                        echo "module: " . $sousDossierModule . " ok pour migration\n";
                    } else {
                        echo "module: " . $sousDossierModule . " ERREUR: JSON INVALIDE\n";
                    }
                }
            }
            closedir($dossierSrc);
            echo "fin de la preparation des modules pour la migration \n";
        }
    }

    /**
     * convertit un json en fichier sql
     *
     * @param [string] $module
     * @return Boolean
     */
    private function convertirJsonVersSql($module) {
        $emplacementJson = __DIR__ . "/../../src/modules/{$module}/@table.json";

        $json = cli_utils::recupererContenuFichier($emplacementJson);

        if ($json !== false) {
            $json = json_decode(utf8_encode($json), false);

            if  (cli_migration::valideJson($json)) {
                $table = $json->table;

                $autoIncrement = [];
                $index = [];
                $requeteSql    = "-- \n";
                $requeteSql   .= "-- Table: {$table->nom} \n";
                $requeteSql   .= "-- \n";
                $requeteSql   .= "CREATE TABLE `{$table->nom}` (";
                
                // ajout des colonnes
                foreach ($table->colonnes as $i => $colonne) {
                    $requeteSql .= "`{$colonne->nom}` {$colonne->type}({$colonne->length}) ";
                    if ($colonne->NOT_NULL === true) { $requeteSql .= "NOT NULL "; }
                    if ($colonne->autoIncrement === true) {
                        $autoIncrement[] = $colonne->nom;
                    }
                    if ($colonne->index !== null) {
                        $index[$colonne->nom] = $colonne->index;
                    }
                    $requeteSql .= ",\n";
                }
                $requeteSql = substr($requeteSql, 0, -2);
                $requeteSql .= ") ENGINE={$table->engine} DEFAULT CHARSET={$table->charset}; \n";

                // index
                $requeteSqlIndex    = "-- \n";
                $requeteSqlIndex   .= "-- Index \n";
                $requeteSqlIndex   .= "-- \n";
                foreach ($index as $colonne => $indexList) {
                    foreach ($indexList as $key => $indexNom) {
                        $requeteSqlIndex .= "ALTER TABLE `{$table->nom}` ADD {$indexNom}(`$colonne`); \n";
                    }
                }
                
                // auto-increment
                $requeteSqlAutoIncrement    = "-- \n";
                $requeteSqlAutoIncrement   .= "-- AutoIncremen \n";
                $requeteSqlAutoIncrement   .= "-- \n";
                foreach ($autoIncrement as $i => $colonne) {
                    $requeteSqlAutoIncrement .= "ALTER TABLE `{$table->nom}` MODIFY `{$colonne}` int(11) NOT NULL AUTO_INCREMENT; \n";
                }

                $fichierMigration = fopen(__DIR__ . "/../../migrations/{$table->nom}@table.sql", "w+");
                fwrite($fichierMigration, "{$requeteSql}\n{$requeteSqlIndex}\n{$requeteSqlAutoIncrement}\n");
                fclose($fichierMigration);

                return true;
            }
            return false;
        }
    }

    /**
     * valide un json pour la migration
     *
     * @param [object] $json
     * @return Boolean
     */
    private function valideJson($json) {
        // on verifie que le json contient l'entrée table
        if (!cli_utils::existe($json, 'table')) { return false; }

        // on valide les informations sur la table
        if (!cli_utils::existe($json->table, 'engine')) { return false; }
        if (!cli_utils::existe($json->table, 'charset')) { return false; }
        if (!cli_utils::existe($json->table, 'ifNotExist')) { return false; }
        if (!cli_utils::existe($json->table, 'colonnes')) { return false; }

        // on valide les informations sur les colonnes
        foreach ($json->table->colonnes as $key => $colonne) {
            if (!cli_utils::existe($colonne, 'nom')) { return false; }
            if (!cli_utils::existe($colonne, 'type')) { return false; }
            if (!cli_utils::existe($colonne, 'length')) { return false; }
        }

        // le json est considéré valide
        return true;
    }

}
