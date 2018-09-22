<?php

abstract class Generateur {

    protected $cheminDossierConfig;
    protected $cheminDossierModule;
    protected $cheminDossierDepots;
    protected $cheminDossierModels;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->cheminDossierConfig = __DIR__ . "/../../config/orm";
        $this->cheminDossierModule = __DIR__ . "/../../src/modules";
    }
    
    protected function genererClassHeader($nomClass, $abstract = false, $interface = false) {
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

    protected function genererCommentaireMethode($description, $params = [], $retour = NULL) {
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

    protected function convertirTypeSqlVersPhp($sqlType) {
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