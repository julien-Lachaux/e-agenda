<?php

abstract class Depot {

    protected static $table; // la table dans la base

    /**
     * retourne toute les entrée de la table
     *
     * @return Array
     */
    public function findAll() {
        $table = static::$table;
        $all = Base::getInstance()->query("SELECT * FROM {$table}");
        if ($all !== false) {
            return $all->fetchAll();
        } 
        return false;
    }

    /**
     * Retourne l'entrée de la table avec l'id passé en paramètre
     *
     * @param Int $id
     * @return Object
     */
    public function findById($id) {
        $table = static::$table;
        return Base::getInstance()->query("SELECT * FROM {$table} WHERE id='{$id}'")->fetchObject();
    }
    
}
