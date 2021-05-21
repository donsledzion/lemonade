<?php

class ShippingGateway {
    
    private $db = null;
    
    public function __construct($db){
        $this->db = $db;
    }

    public function findAll() {
        $statement = "SELECT IDShipping, IDPlaneType, Shipping_From, Destination, Shipping_Date FROM Shippings;";
        $id = null;
        try {
            $statement = $this->db->query($statement, $id);
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
    public function find($id) {        
        $statement = "SELECT IDShipping, IDPlaneType, Shipping_From, Destination, Shipping_Date FROM Shippings WHERE IDShipping = ?;" ;
        
        try {
            $statement = $this->db->query($statement, $id);
            $result = $statement;
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    
    public function insertShipping(Array $input) {

        $statement = "INSERT INTO shippings "
                    . "(IDShipping, IDPlaneType, Shipping_From, Destination, Shipping_Date)"
                    . " VALUES "
                    . "(:IDShipping, :IDPlaneType, :Shipping_From, :Destination, :Shipping_Date);";
        try {
            $statement = $this->db->prepareAndExec($statement,array(
                'IDShipping'    => null,
                'IDPlaneType'   => $input['plane_type'],
                'Shipping_From' => $input['ship_from'],
                'Destination'   => $input['ship_to'],
                'Shipping_Date' => $input['shipping_date'],
            ));            
            return $statement->rowCount();
        } catch (\PDOException $e) {            
            exit($e->getMessage);
        }
    }
    
    public function insertCargo(Array $input) {
        
        $statement = "INSERT INTO cargos"
                    . "(IDCargo, IDShipping, Name, Weight, CargoType)"
                    . " VALUES "
                    . "(:IDCargo, :IDShipping, :Name, :Weight, :CargoType);";        
        try {
            $statement = $this->db->prepareAndExec($statement,array(            
                'IDCargo'   => null,
                'IDShipping'=> $input['IDShipping'],
                'Name'      => $input['Name'],
                'Weight'    => $input['Weight'],
                'CargoType' => $input['Type'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage);
        }
                
    }
        
}
