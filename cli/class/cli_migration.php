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
        $requeteSql = "CREATE TABLE `{$table->nom}` (";
        
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
            $requeteSql .= "\n";
        }
        $requeteSql .= ") ENGINE={$table->engine} DEFAULT CHARSET={$table->charset}; \n";

        // auto-increment
        $requeteSqlAutoIncrement = "";
        foreach ($autoIncrement as $i => $colonne) {
            $requeteSqlAutoIncrement .= "ALTER TABLE `{$table->nom}` MODIFY `{$colonne->nom}` int(11) NOT NULL AUTO_INCREMENT; \n";
        }

        // index
        $requeteSqlIndex = "";
        foreach ($index as $colonne => $indexList) {
            foreach ($indexList as $key => $indexNom) {
                $requeteSqlIndex .= "ALTER TABLE `{$table->nom}` ADD {$indexNom}(`$colonne`); \n";
            }
        }

        echo("-- \n");
        echo("-- table \n");
        echo("-- \n");
        echo($requeteSql . "\n");
        
        echo("-- \n");
        echo("-- AutoIncrement \n");
        echo("-- \n");
        echo($requeteSqlAutoIncrement . "\n");
        
        echo("-- \n");
        echo("-- Index \n");
        echo("-- \n");
        echo($requeteSqlIndex . "\n");
    }
}
