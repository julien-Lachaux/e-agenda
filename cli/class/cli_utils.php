<?php

class cli_utils
{
    /**
     * retourne les arguments du script cli mis en forme dans un tableau associatif
     *
     * @param [Array] $argv
     * @return Array
     */
    static function recupererCliArgs($argv) {
        $cliArgs = [
            "class"  => $argv[1],
            "fonction"     => $argv[2],
            "arguments" => []
        ];
        foreach ($argv as $i => $arg) {
            if ($i > 2) {
                $cliArgs["arguments"][] = $arg;
            }
        }
    
        return $cliArgs;
    }
    
    /**
     * recupere le contenu du fichier a l'emplacement passer en parametre
     *
     * @param [string] $emplacement
     * @return void
     */
    static function recupererContenuFichier($emplacement) {
        $contenu = file_get_contents($emplacement, FILE_USE_INCLUDE_PATH);

        return $contenu;
    }

    /**
     * verifie si un element d'un object existe
     *
     * @param [object] $object
     * @param [string] $element
     * @return Boolean
     */
    static function existe($object, $element) {
        if ($object->{$element} === NULL) {
            return false;
        } 
        return true;
    }
}
