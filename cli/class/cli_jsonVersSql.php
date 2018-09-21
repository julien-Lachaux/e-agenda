<?php
require_once('cli_utils.php');

class cli_jsonVersSql {

    /**
     * convertit un json @table en sql
     *
     * @param Object $tableJson
     * @return Array|Boolean
     */
    static function convertirTable($tableJson) {
        $tableJson = json_decode(utf8_encode($tableJson), false);
        if  (self::valideJson($tableJson)) {
            $table = $tableJson->table;

            $relationnel = [];
            $autoIncrement = [];
            $index = [];

            $requeteSql    = self::titreSql($table->nom, "Table");
            $requeteSql   .= "CREATE TABLE `{$table->nom}` (";
            
            // ajout des colonnes
            foreach ($table->colonnes as $i => $colonne) {

                if (cli_utils::existe($colonne, 'length')) {
                    $requeteSql .= "`{$colonne->nom}` {$colonne->type}({$colonne->length}) ";
                } else if ($colonne->type === 'relationnel') {
                    $colonne->table = $table->nom;
                    $relationnel[] = self::convertirRelationnel($colonne);
                } else {
                    $requeteSql .= "`{$colonne->nom}` {$colonne->type} ";
                }

                if ($colonne->type !== 'relationnel') {
                    if ($colonne->NOT_NULL === true) { $requeteSql .= "NOT NULL "; }
                    if ($colonne->autoIncrement === true) {
                        $autoIncrement[] = $colonne->nom;
                    }
                    if ($colonne->index !== null) {
                        $index[$colonne->nom] = $colonne->index;
                    }
                    $requeteSql .= ",\n";
                }
            }
            $requeteSql = substr($requeteSql, 0, -2);
            $requeteSql .= ") ENGINE={$table->engine} DEFAULT CHARSET={$table->charset}; \n";

            // index
            $requeteSqlIndex = self::titreSql($table->nom, "Index pour la table");
            foreach ($index as $colonne => $indexList) {
                foreach ($indexList as $key => $indexNom) {
                    $requeteSqlIndex .= "ALTER TABLE `{$table->nom}` ADD {$indexNom}(`$colonne`) ";
                    $requeteSqlIndex .= ";\n";
                }
            }
            
            // auto-increment
            $requeteSqlAutoIncrement = self::titreSql($table->nom, "Auto-increment pour la table");
            foreach ($autoIncrement as $i => $colonne) {
                $requeteSqlAutoIncrement .= "ALTER TABLE `{$table->nom}` MODIFY `{$colonne}` INT UNSIGNED NOT NULL AUTO_INCREMENT; \n";
            }

            $reponse = [
                "table" => $requeteSql . $requeteSqlIndex . $requeteSqlAutoIncrement,
                "relationnels" => $relationnel,
            ];

            return $reponse;
        }
        return false;
    }

    /**
     * convertit un json @table en sql
     *
     * @param Object $baseJson
     * @return Array|Boolean
     */
    static function convertirBase($baseJson) {
        $baseJson = json_decode(utf8_encode($baseJson), false);
        if  (self::valideJson($baseJson)) {
            $base = $baseJson->base;

            $requeteSql    = self::titreSql($base->nom, "Base");
            $requeteSql   .= "CREATE DATABASE `{$base->nom}` CHARACTER SET = {$base->charset} COLLATE = {$base->encodage};\n";
            $requeteSql   .= "USE {$base->nom};\n";

            $reponse = [
                "nom"       => $base->nom,
                "requete"  => $requeteSql
            ];

            return $reponse;
        } else {
            return false;
        }
    }

    /**
     * convertit une relation en sql
     *
     * @param Object $relationJson
     * @return Array|Boolean
     */
    static function convertirRelationnel($relationJson) {
        $table = $relationJson->table;
        $tableCible = $relationJson->tableCible;
        $colonneCible = $relationJson->colonneCible;
        $nomColonne = "{$tableCible}_id";

        switch ($relationJson->typeRelation) {
            case 'OneToOne':
                // ajout de la colonnes [cible]_id
                $requeteSql = "ALTER TABLE `{$table}` ADD COLUMN `{$nomColonne}` INT UNSIGNED NOT NULL;\n";

                // ajout de la clé étrangère sur la colonnes [cible]_id
                $requeteSql .= "ALTER TABLE `{$table}` ADD CONSTRAINT `FK_{$tableCible}_{$table}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`{$colonneCible}`) ON DELETE CASCADE ON UPDATE CASCADE;\n";

                // ajout d'un index UNIQUE sur la colonnes [cible]_id 
                $requeteSql .= "ALTER TABLE `{$table}` ADD CONSTRAINT `UNIQUE_{$table}_{$nomColonne}` UNIQUE KEY (`{$nomColonne}`);\n";

                break;

            case 'ManyToOne':
                // ajout de la colonnes [cible]_id
                $requeteSql = "ALTER TABLE `{$table}` ADD COLUMN `{$nomColonne}` INT UNSIGNED NOT NULL;\n";

                // ajout de la clé étrangère sur la colonnes [cible]_id
                $requeteSql .= "ALTER TABLE `{$table}` ADD CONSTRAINT `FK_{$tableCible}_{$table}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`{$colonneCible}`) ON DELETE CASCADE ON UPDATE CASCADE;\n";

                break;

            case 'OneToMany':
                $tableLiaison = "{$tableCible}_{$table}";

                // creation de la table de liaison
                $requeteSql = "CREATE TABLE `{$tableLiaison}` (";
                $requeteSql .= "`{$table}_id` INT UNSIGNED NOT NULL,";
                $requeteSql .= "`{$nomColonne}` INT UNSIGNED NOT NULL";
                $requeteSql .= ") ENGINE=InnoDB DEFAULT CHARSET='utf8';\n";

                // ajout des clés étrangères
                $requeteSql .= "ALTER TABLE `{$tableLiaison}` ADD CONSTRAINT `FK_{$table}_{$tableLiaison}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n";
                $requeteSql .= "ALTER TABLE `{$tableLiaison}` ADD CONSTRAINT `FK_{$tableCible}_{$tableLiaison}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`{$colonneCible}`) ON DELETE CASCADE ON UPDATE CASCADE;\n";

                // ajout d'un index UNIQUE sur la colonnes { $nomColonne }
                $requeteSql .= "ALTER TABLE `{$tableLiaison}` ADD CONSTRAINT `UNIQUE_{$tableLiaison}_{$table}_id` UNIQUE KEY (`{$table}_id`);\n";

                break;

            case 'ManyToMany':
                $tableLiaison = "{$tableCible}_{$table}";

                // creation de la table de liaison
                $requeteSql = "CREATE TABLE `{$tableLiaison}` (";
                $requeteSql .= "`{$table}_id` INT UNSIGNED NOT NULL,";
                $requeteSql .= "`{$nomColonne}` INT UNSIGNED NOT NULL";
                $requeteSql .= ") ENGINE=InnoDB DEFAULT CHARSET='utf8';\n";

                // ajout des clés étrangères
                $requeteSql .= "ALTER TABLE `{$tableLiaison}` ADD CONSTRAINT `FK_{$table}_{$tableLiaison}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;\n";
                $requeteSql .= "ALTER TABLE `{$tableLiaison} `ADD CONSTRAINT `FK_{$tableCible}_{$tableLiaison}` FOREIGN KEY (`{$nomColonne}`) REFERENCES `{$tableCible}`(`{$colonneCible}`) ON DELETE CASCADE ON UPDATE CASCADE;\n";

                break;
            
            default:
                $requeteSql = self::commentaireSql("ERREUR ON FOREIGN KEY {$nomColonne} IN {$table}");
                break;
        }

        return $requeteSql;
    }

    /**
     * valide un json pour la migration
     *
     * @param Object $json
     * @return Boolean
     */
    static function valideJson($json) {
        // on verifie que le json contient l'entrée table
        if (cli_utils::existe($json, 'base')) {

            // on valide les informations sur la base
            if (!cli_utils::existe($json->base, 'nom')) { return false; }
            if (!cli_utils::existe($json->base, 'charset')) { return false; }
            if (!cli_utils::existe($json->base, 'encodage')) { return false; }

        } else if (cli_utils::existe($json, 'table')) {

            // on valide les informations sur la table
            if (!cli_utils::existe($json->table, 'nom')) { return false; }
            if (!cli_utils::existe($json->table, 'engine')) { return false; }
            if (!cli_utils::existe($json->table, 'charset')) { return false; }
            if (!cli_utils::existe($json->table, 'ifNotExist')) { return false; }
            if (!cli_utils::existe($json->table, 'colonnes')) { return false; }
    
            // on valide les informations sur les colonnes
            foreach ($json->table->colonnes as $key => $colonne) {
                if (!cli_utils::existe($colonne, 'type')) { return false; }
                if (!cli_utils::existe($colonne, 'nom') && $colonne->type !== 'relationnel') { return false; }
                if ($colonne->type === 'relationnel') {
                    if (!cli_utils::existe($colonne, 'tableCible')) { return false; }
                    if (!cli_utils::existe($colonne, 'colonneCible')) { return false; }
                    if (!cli_utils::existe($colonne, 'typeRelation')) { return false; }
                    
                    $relations = ["OneToOne", "OneToMany", "ManyToOne" ,"ManyToMany"];
                    if (!array_search($colonne->typeRelation, $relations)) { return false; }
                }
            }

        } else {
            return false;
        }

        // le json est considéré valide
        return true;
    }

    /**
     * retourne un commentaire sql
     *
     * @param String $commentaire
     * @return String
     */
    static function commentaireSql($commentaire) {
        $commentaire = "-- {$commentaire}";
        return $commentaire;
    }

    /**
     * retourne un commentaire structuré pour faire un titre
     *
     * @param String $titre
     * @param String $type
     * @return String
     */
    static function titreSql($titre, $type = "Section") {
            $titre = "--\n-- {$type}: {$titre}\n--\n";
            return $titre;
    }

}