<?php
namespace Source;

use Source\Base;

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
        if (is_string($valeur)) {
            Base::getInstance()->query("UPDATE {$table} SET {$param}='{$valeur}' WHERE {$condition}");
        } else if (is_int($valeur)) {
            Base::getInstance()->query("UPDATE {$table} SET {$param}={$valeur} WHERE {$condition}");
        } else {
            Base::getInstance()->query("UPDATE {$table} SET {$param}=NULL WHERE {$condition}");
        }
        return true;
    }

    /**
     * supprime l'element cibler
     *
     * @param Integer $id
     * @return Boolean
     */
    public static function supprimer($id) {
        $table = static::$table;
        $test = Base::getInstance()->query("SELECT * FROM {$table} WHERE id={$id}");;
        if ($test !== false) {
            Base::getInstance()->query("DELETE FROM {$table} WHERE id={$id}");
            return true;
        }
        return false;
    }
    
}
