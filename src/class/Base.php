<?php

class Base implements BaseInterface{
    
    private $db_host;
    private $db_port;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $instance;
    
    public function __construct($db_name, $db_user, $db_pass, $db_host, $db_port) {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
    }
    
    public function getInstance() {
        if($this->instance === null) {
            $instance = new PDO('mysql:dbname=' . $this->db_name . ';host=' . $this->db_host . ';port=' . $this->db_port, $this->db_user, $this->db_pass);
            $this->instance = $instance;
        }
        
        return $this->instance;
    }
    
    public function query($requete) {
        return $this->getInstance()->query($requete);
    }
    
}