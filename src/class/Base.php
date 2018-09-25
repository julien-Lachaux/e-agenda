<?php
namespace Source;

use PDO;
use Source\interfaces\BaseInterface;

class Base implements BaseInterface
{

    private static $instance;
    
    /**
     * retourne l'instance de PDO
     *
     * @return Object
     */
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new PDO("mysql:dbname=" . BDD_NAME . ";host=" . BDD_HOST . ";port=" . BDD_PORT, BDD_USER, BDD_PASS);
        }
        
        return self::$instance;
    }
    
    /**
     * effectue un query
     *
     * @param String $requete
     * @return Object
     */
    public static function query($requete) {
        return self::getInstance()->query($requete);
    }
    
}