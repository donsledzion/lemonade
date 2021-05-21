<?php

//require_once "dbconnect.php";

class CargoGateway {
    
    private $db = null;
    
    public function __construct($db){
        $this->db = $db;
    }

    public function findAll() {
        $statement = "SELECT IDCargo, IDShipping, Name, Weight, CargoType FROM Cargos;";
        
        try {
            $statement = $this->db->query($statement);
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
    public function find($id) {        
        $statement = "SELECT IDCargo, IDShipping, Name, Weight, CargoType WHERE IDCargo = ?;" ;
        
        try {
            $statement = $this->db->query($statement, $id);
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
    public function insert(Array $input) {
        $statement = "INSERT INTO Cargos "
                    . "(IDCargo, IDShipping, Name, Weight, Type)"
                . " VALUES "
                . "(:IDCargo, :IDShipping, :Name, :Weight, :Type);";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement = $this->db->execute(array(
                'IDCargo'   => null,
                'IDShipping'=> $input['IDShipping'],
                'Name'      => $input['Name'],
                'Weight'    => $input['Weight'],
                'Type'      => $input['Type'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage);
        }
                
    }
    
}
