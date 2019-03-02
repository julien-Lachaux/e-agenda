<?php
namespace Source;

class Utils
{
    /**
     * retourne les arguments du script cli mis en forme dans un tableau associatif
     *
     * @param Array $argv
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
     * @param String $emplacement
     * @return void
     */
    static function recupererContenuFichier($emplacement) {
        $contenu = file_get_contents($emplacement, FILE_USE_INCLUDE_PATH);

        return $contenu;
    }

    /**
     * verifie si un element d'un object existe
     *
     * @param Object $object
     * @param String $element
     * @return Boolean
     */
    static function existe($object, $element) {
        if ($object->{$element} === NULL) {
            return false;
        } 
        return true;
    }

    /**
     * var_dump formater
     *
     * @param Any $variable
     * @param Boolean $exit
     * @return void
     */
    static function debug($variable = "--DEBUG--", $exit = false) {
        echo "\n\r";
        var_dump($variable);
        echo "\n\r";
        if ($exit) {
            exit(0);
        }
    }

    /**
     * var_dump formater avec pre
     *
     * @param Any $variable
     * @param Boolean $exit
     * @return void
     */
    static function debugPre($variable = "--DEBUG--", $exit = false) {
        echo "<pre>\n\r";
        var_dump($variable);
        echo "</pre>\n\r";
        if ($exit) {
            exit(0);
        }
    }

    /**
     * affiche un message dans la console
     *
     * @param String $message
     * @param String $special
     * @return void
     */
    static function consoleLog($message = "", $special = NULL) {
        if ($special === NULL && $message !== 'separateur') {
            echo("{$message} \r\n");
        } else if ($message === 'separateur') {
            echo("\r\n====================================\r\n\r\n");
        } else if ($special === 'title') {
            echo("\r\n");
            for ($i=0; $i < (strlen($message) + 4); $i++) { 
                echo("=");
            }
            echo("\r\n");
            echo("| {$message} |\r\n");
            for ($i=0; $i < (strlen($message) + 4); $i++) { 
                echo("=");
            }
            echo("\r\n");
        }
    }

    /**
     * recupère les variables d'environnements et les definis en variables global
     *
     * @return void
     */
    static function recupererEnvVar() {
        $envFile = Utils::recupererContenuFichier(__DIR__ . "/../../.env");
        $envFileLine = explode("\n", $envFile);
        $env = [];
        foreach ($envFileLine as $lineNbr => $line) {
            $envLine = explode("=", $line);
            if (substr($envLine[0], 0, 1) !== "#") {
                switch ($envLine[1]) {
                    case 'true':
                        $value = true;
                        break;

                    case 'false':
                        $value = false;
                        break;
                    
                    default:
                        $value = $envLine[1];
                        break;
                }
                define($envLine[0], $value);
            }
        }
    }
    
}
