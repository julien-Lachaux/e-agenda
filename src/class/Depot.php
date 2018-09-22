<?php

abstract class Depot {

    protected $table; // la table dans la base

    /**
     * retourne toute les entrée de la table
     *
     * @return Array
     */
    public static function findAll() {
        return Base::getInstance()->query("SELECT * FROM {$this->table}")->fetchArray();
    }

    /**
     * Retourne l'entrée de la table avec l'id passé en paramètre
     *
     * @param Int $id
     * @return Object
     */
    public static function findById($id) {
        return Base::getInstance()->query("SELECT * FROM {$this->table} WHERE id='{$id}'")->fetchObject();
    }
    
}
