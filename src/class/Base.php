<?php
namespace Source;

use PDO;
use Source\interfaces\BaseInterface;

class Base implements BaseInterface
{

    private static $instance;
    
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new PDO("mysql:dbname=" . BDD_NAME . ";host=" . BDD_HOST . ";port=" . BDD_PORT, BDD_USER, BDD_PASS);
        }
        
        return self::$instance;
    }
    
    public static function query($requete) {
        return self::getInstance()->query($requete);
    }
    
}