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
        $this->cheminDossierDepots = __DIR__ . "/../../src/depots";
        $this->cheminDossierModels = __DIR__ . "/../../src/models";
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
            $fichierDepot = fopen($this->cheminDossierDepots . "/{$nomDepot}.php", "w+");

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
                            "type" => "getMany"
                        ];

                        // Un des Many recupere One
                        $fonctionsRelations[] = [
                            "model" => $nomModel,
                            "fonction" => "get{$cible}",
                            "type" => "getOne"
                        ];
                        break;
                }
            }

            $models[] = (object) [
                "nom"       => $nomModel,
                "colonnes"  => $colonnes
            ];
        }

        // on genere les models
        foreach ($models as $model) {
            $fichierModel = fopen($this->cheminDossierModels . "/{$model->nom}.php", "w+");

            $nouveauModel = $this->genererClassHeader($model->nom, "Model");

            // on genere les attributs, les getters et les setters
            $nouveauModel .= $this->genererAttribut($colonnes);
            foreach ($colonnes as $colonne) {
                $nouveauModel .= $this->genererGetter($colonne);
                $nouveauModel .= $this->genererSetter($colonne);
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
        $classHeader  = "<?php\n\n";
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

    private function genererCommentaireMethode($description, $params, $retour = NULL) {
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
