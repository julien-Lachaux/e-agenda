<?php
namespace Source;

use \PDO;
use Source\Base;
use Source\Utils;

abstract class Depot
{

    protected static $table; // la table dans la base

    /**
     * retourne toute les entrée de la table
     *
     * @return Array|Boolean
     */
    public function findAll() {
        $table = static::$table;
        $all = Base::getInstance()->query("SELECT * FROM {$table}");
        if ($all !== false) {
            return $all->fetchAll(PDO::FETCH_ASSOC);
        } 
        return false;
    }

    /**
     * Retourne l'entrée de la table avec l'id passé en paramètre
     *
     * @param Int $id
     * @return Object|Boolean
     */
    public function findById($id) {
        $table = static::$table;
        $one = Base::getInstance()->query("SELECT * FROM {$table} WHERE id='{$id}'");
        if ($one !== false) {
            return $one->fetchObject();
        } 
        return false;
    }

    /**
     * retourne la premiere ligne de la table qui valide la condition
     *
     * @param String $condition
     * @return Object|Boolean
     */
    public static function findOne($condition) {
        $table = static::$table;
        $one = Base::getInstance()->query("SELECT * FROM {$table} WHERE {$condition} LIMIT 1");
        if ($one !== false) {
            return $one->fetchObject();
        } 
        return false;
    }

    /**
     * retourne toute les lignes de la table qui valide la condition
     *
     * @return Array|Boolean
     */
    public static function findMany($condition, $limit = false, $offset = false) {
        $table = static::$table;

        $requete = "SELECT * FROM {$table} WHERE {$condition}";
        if ($limit !== false) { $requete .= " LIMIT {$limit}"; }
        if ($offset !== false) { $requete .= " OFFSET {$limit}"; }

        $many = Base::getInstance()->query($requete);
        if ($many !== false) {
            return $many->fetchAll(PDO::FETCH_ASSOC);
        } 
        return false;
    }
    
}
