<?php

abstract class Depot {

    protected $_db; // la base de donnees
    protected $_table; // la table dans la base
    
    /**
     * constructeur
     *
     * @param BaseInterface $db
     */
    public function __construct(BaseInterface $db) {
        $this->_db = $db->getInstance();
    }

    /**
     * retourne toute les entrée de la table
     *
     * @return Array
     */
    public function findAll() {
        return $this->_db->query("SELECT * FROM {$this->_table}")->fetchArray();
    }

    /**
     * Retourne l'entrée de la table avec l'id passé en paramètre
     *
     * @param Int $id
     * @return Object
     */
    public function findById($id) {
        return $this->_db->query("SELECT * FROM {$this->_table} WHERE id='{$id}'")->fetchObject();
    }
    
}
