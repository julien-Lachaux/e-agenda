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
    
    static function recupererContenuFichier($emplacement) {
        $contenu = file_get_contents($emplacement, FILE_USE_INCLUDE_PATH);

        return $contenu;
    }
}
