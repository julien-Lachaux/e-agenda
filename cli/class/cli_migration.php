<?php
require_once('cli_utils.php');

class cli_migration
{
    function preparer($cibleArray) {
        foreach ($cibleArray as $i => $cible) {
            $this->convertirJsonVersSql($cible);
        }
    }

    function convertirJsonVersSql($module) {
        $emplacementJson = __DIR__ . "/../../src/modules/{$module}/@table.json";

        $table = json_decode(utf8_encode(cli_utils::recupererContenuFichier($emplacementJson)), false)->table;

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
    }
}
