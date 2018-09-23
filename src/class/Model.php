<?php
namespace Source;

use Source\Base;
use Source\Utils;

abstract class Model
{   

    protected static $table;

    /**
     * Constructeurs
     *
     */
    public function __construct($params) {
        foreach ($params as $param => $valeur) {
            $setter = 'set' . ucfirst($param);
            $this->$setter($valeur);
        }
    }
    
    /**
     * update la valeur d'un champs
     * @param String $param nom de la colonne
     * @param String|Int $valeur valeur
     * @param String|Int $condition SQL string : WHERE condition
     */
    public static function update($param, $valeur, $condition) {
        // on ne peut pas modifier l'id
        if ($param == 'id') { return false; }
        $table = static::$table;
        if(is_string($valeur)) {
            Base::getInstance()->query("UPDATE {$table} SET {$param}='{$valeur}' WHERE {$condition}");
        } else if (is_int($valeur)) {
            Base::getInstance()->query("UPDATE {$table} SET {$param}={$valeur} WHERE {$condition}");
        } else {
            Base::getInstance()->query("UPDATE {$table} SET {$param}=NULL WHERE {$condition}");
        }

        return true;
    }
    
}
