<?php
require_once('Utils.php');
require_once('JsonVersSql.php');

class Generation
{
    private $cheminDossierConfig;
    private $cheminDossierModule;
    private $cheminDossierDepots;
    private $cheminDossierModels;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->cheminDossierConfig = __DIR__ . "/../../config/orm";
        $this->cheminDossierModule = __DIR__ . "/../../src/modules";
    }

    public function generer() {
        // on recupere les configurations de la base et des tables
        $config = $this->recupererConfig();

        
        // on generes les class php
        $this->genererDepots($config);
        $this->genererModels($config);
        Utils::debug();
    }

    private function genererDepots($config) {
        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du depots à partir du nom de la table
            $nomDepot = ucfirst($table->nom);

            // on genere les models
            $fichierDepot = fopen($this->cheminDossierModule . "/{$table->nom}/{$nomDepot}.php", "w+");

            $nouveauDepot = $this->genererClassHeader($nomDepot, "Depot");
            $nouveauDepot .= "}\n";
            
            fwrite($fichierDepot, $nouveauDepot);
            fclose($fichierDepot);
        }
    }

    private function genererModels($config) {

        $models = [];
        $fonctionsRelations = [];

        foreach ($config["tables"] as $i => $table) {
            // on recupère le nom du model à partir du nom de la table
            $nomModel = substr(ucfirst($table->nom), 0, -1);

            $colonnes = array_filter($table->colonnes, function($colonne) {
                return $colonne->type !== 'relationnel';
            });

            // on vérifie si la table a une relation nécéssitant une implémentation de methode dans le depot
            $relations = array_filter($table->colonnes, function($colonne) {
                return $colonne->type === 'relationnel';
            });

            foreach ($relations as $relation) {
                switch ($relation->typeRelation) {
                    case 'ManyToOne':
                        $cible = substr(ucfirst($relation->tableCible), 0, -1);

                        // on ajoute la colonnes generer automatiquement pour la relation (elle n'est donc pas presente dans le json)
                        $colonnes[] = (object) [
                            "nom" => substr($relation->tableCible, 0, -1) . "_" . $relation->colonneCible
                        ];

                        // One recupere Many
                        $fonctionsRelations[] = [
                            "model" => $cible,
                            "fonction" => "get{$nomModel}s",
                            "type" => "getMany",
                            "cible"  => $nomModel
                        ];

                        // Un des Many recupere One
                        $fonctionsRelations[] = [
                            "model" => $nomModel,
                            "fonction" => "get{$cible}",
                            "type" => "getOne",
                            "cible"  => $cible
                        ];
                        break;
                }
            }
            $models[] = (object) [
                "module"    => $table->nom,
                "nom"       => $nomModel,
                "colonnes"  => $colonnes
            ];
        }
        // on genere les models
        foreach ($models as $model) {
            $fichierModel = fopen($this->cheminDossierModule . "/{$model->module}/{$model->nom}.php", "w+");
            
            $nouveauModel = $this->genererClassHeader($model->nom, "Model");
            
            // on genere les attributs, les getters et les setters
            $nouveauModel .= $this->genererAttribut($model->colonnes);

            foreach ($model->colonnes as $colonne) {
                $nouveauModel .= $this->genererGetter($colonne);
                $nouveauModel .= $this->genererSetter($colonne);
            }
            $nouveauModel .= $this->genererMethodesModel($model->nom);
            
            foreach ($fonctionsRelations as $relation) {
                $nouveauModel .= $this->genererMethodesRelationnelsModel($model->nom, $relation);
            }

            $nouveauModel .= "}\n";
            
            fwrite($fichierModel, $nouveauModel);
            fclose($fichierModel);
        }
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

    private function genererClassHeader($nomClass, $abstract = false, $interface = false) {
        $classHeader  = "<?php\n";
        if($abstract !== false) { $classHeader .= "require_once('src/class/{$abstract}.php');\n"; }
        $classHeader .= "\n";
        $classHeader .= "class {$nomClass} ";

        if($abstract !== false) { $classHeader .= "extends {$abstract} "; }
        if($interface !== false) { $classHeader .= "implements {$interface} "; }

        $classHeader .= "\n";
        $classHeader .= "{\n";

        return $classHeader;
    }

    private function genererAttribut($colonnes) {
        $attribut = "\n";
        foreach ($colonnes as $colonne) {
            $attribut .= "\tprivate $" . $colonne->nom . ";\n";
        }
        return $attribut;
    }

    private function genererSetter($colonne) {
        $nomMethode = ucfirst($colonne->nom);
        $setter = "\n";
        $paramSetterType = $this->convertirTypeSqlVersPhp($colonne->type);
        
        $setter .= $this->genererCommentaireMethode("Affecte la valeur \$valeur à {$colonne->nom}", [(object) [
            "nom"   => $colonne->nom,
            "type"  => $paramSetterType
        ]], $paramSetterType);
        $setter .= "\tpublic function set{$nomMethode}(" . '$valeur' . ") {\n";
        $setter .= "\t\t\$this->{$colonne->nom} = \$valeur;\n";
        $setter .= "\t}\n";

        return $setter;
    }

    private function genererGetter($colonne) {
        $nomMethode = ucfirst($colonne->nom);
        $retourGetterType = $this->convertirTypeSqlVersPhp($colonne->type);

        $getter = "\n";
        $getter .= $this->genererCommentaireMethode("Retourne la valeur de {$colonne->nom}", [], $retourGetterType);
        $getter .= "\tpublic function get{$nomMethode}(\$valeur) {\n";
        $getter .= "\t\t return \$this->{$colonne->nom};\n";
        $getter .= "\t}\n";

        return $getter;
    }

    private function genererMethodesModel($nomModel) {

        $nomTable = strtolower($nomModel) . "s";

        $methodeValider  = "\n";
        $methodeValider .= $this->genererCommentaireMethode("Valide les données de {$nomModel}", [(object)[
            "type" => "Object",
            "nom" => $nomModel . "Data"
        ]], "Boolean");
        $methodeValider .= "\tpublic function valider(\${$nomModel}Data) {\n";
        $methodeValider .= "\t\tforeach (\${$nomModel}Data as \$data) {\n";
        $methodeValider .= "\t\t\tif (gettype(\$data) !== 'string'\n";
        $methodeValider .= "\t\t\t && gettype(\$data) !== 'integer'\n";
        $methodeValider .= "\t\t\t && gettype(\$data) !== 'boolean'\n";
        $methodeValider .= "\t\t\t && gettype(\$data) !== 'NULL') {\n";
        $methodeValider .= "\t\t\t\treturn false;\n";
        $methodeValider .= "\t\t\t}\n";
        $methodeValider .= "\t\t}\n";
        $methodeValider .= "\t\treturn true;\n";
        $methodeValider .= "\t}\n";

        $methodeCreer  = "\n";
        $methodeCreer .= $this->genererCommentaireMethode("Retourne la valeur de {$nomModel}", [], "Boolean");
        $methodeCreer .= "\tpublic function creer() {\n";
        $methodeCreer .= "\t\t\$colonnesString = \"\";\n";
        $methodeCreer .= "\t\t\$valeursString = \"\";\n";
        $methodeCreer .= "\t\t\$colonnes = get_object_vars(\$this);\n\n";
        $methodeCreer .= "\t\tforeach (\$colonnes as \$colonne => \$valeur) {\n";
        $methodeCreer .= "\t\t\t\$colonnesString .= \"{\$colonne}, \";\n";
        $methodeCreer .= "\t\t\t\$valeursString .= \"{\$valeur}, \";\n";
        $methodeCreer .= "\t\t}\n\n";
        $methodeCreer .= "\t\t\$colonnesString = substr(\$colonnesString, 0, -2);\n";
        $methodeCreer .= "\t\t\$valeursString = substr(\$valeursString , 0, -2);\n\n";
        $methodeCreer .= "\t\t\$creation = Base::getInstance()->query(\"INSERT INTO {$nomTable} ({\$colonnesString}) VALUES({\$valeursString})\");\n\n";
        $methodeCreer .= "\t\tif (\$creation === false) { return false; }\n\n";
        $methodeCreer .= "\t\treturn true;\n";
        $methodeCreer .= "\t}\n";
        
        return $methodeValider . $methodeCreer;
    }

    private function genererMethodesRelationnelsModel($model, $relation) {
        $methodesRelations = "";

        if ($model === $relation["model"]) {
            $table = strtolower($model);
            switch ($relation["type"]) {
                case 'getOne':
                    $methodesRelations .= "\tpublic function {$relation['fonction']}() {\n";
                    $methodesRelations .= "\t\treturn {$relation['cible']}s::findById(\$this->id);\n";
                    $methodesRelations .= "\t}\n\n";
                    break;

                case 'getMany':
                    $methodesRelations .= "\tpublic function {$relation['fonction']}() {\n";
                    $methodesRelations .= "\t\treturn Base::getInstance()->query(\"SELECT * FROM {$table}s INNER JOIN {$relation['cible']} ON {$table}.id={$relation['cible']}.{$table}s_id WHERE {$relation['cible']}.{$table}s_id='{$this->id}'\")->fetchObject();\n";
                    $methodesRelations .= "\t}\n\n";
                    break;
            }
        }

        return $methodesRelations;
    }

    private function genererCommentaireMethode($description, $params = [], $retour = NULL) {
        $commentaire  = "\t/**\n";
        $commentaire .= "\t * {$description}\n";
        $commentaire .= "\t *\n";
        foreach ($params as $param) {
            $commentaire .= "\t * @param {$param->type} \${$param->nom}\n";
        }
        if ($retour !== NULL) { $commentaire .= "\t * @return {$retour}\n"; }
        $commentaire .= "\t */\n";

        return $commentaire;
    }

    private function convertirTypeSqlVersPhp($sqlType) {
        switch ($sqlType) {

            case 'varchar':
                $phpType = "String";
                break;

            case 'text':
                $phpType = "String";
                break;

            case 'int':
                $phpType = "Integer";
                break;
            
            default:
                $phpType = "Void";
                break;

        }

        return $phpType;
    }
}
