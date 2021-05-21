<?php

//require_once "dbconnect.php";

class PlaneTypeGateway {
    
    private $db = null;
    
    public function __construct($db){
        $this->db = $db;
    }

    public function findAll() {
        $statement = "SELECT IDPlaneType, Name, Deadweight FROM typesofplane;";
        $id = null ;
        try {
            $statement = $this->db->query($statement, $id);
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
    public function find($id) {        
        $statement = "SELECT IDPlaneType, Name, Deadweight FROM typesofplane WHERE IDPlaneType = ?;" ;
        
        try {
            $statement = $this->db->query($statement, $id);            
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
}
