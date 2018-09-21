<?php

abstract class Model
{

    protected $_db; // la base de donnees
    
    /**
     * Constructeurs
     *
     * @param BaseInterface $db
     */
    public function __construct(BaseInterface $db, $params) {
        $this->_db = $db->getInstance();
        foreach ($params as $param => $valeur) {
            $this->{$param} = $valeur;
        }
    }
    
    /**
     * update la valeur d'un champs
     * @param String $param nom de la colonne
     * @param String|Int $valeur valeur
     * @param String|Int $condition SQL string : WHERE condition
     */
    private function set($param, $valeur, $condition) {
        // on ne peut pas modifier l'id
        if ($param == 'id') { return false; }
        
        if(is_string($valeur)) {
            $this->_db->query("UPDATE {$this->_table} SET {$param}='{$valeur}' WHERE {$condition}");
        } else if (is_int($valeur)) {
            $this->_db->query("UPDATE {$this->_table} SET {$param}={$valeur} WHERE {$condition}");
        } else {
            $this->_db->query("UPDATE {$this->_table} SET {$param}=NULL WHERE {$condition}");
        }

        return true;
    }
    
}
