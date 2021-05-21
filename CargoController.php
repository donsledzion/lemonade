<?php

include 'CargoGateway.php';

class CargoController {

    private $db;
    private $requestMethod;
    private $IDCargo;
    
    private $CargoGateway;
    
    public function __construct($db, $requestMethod, $IDCargo) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->IDCargo = $IDCargo;
        
        $this->CargoGateway = new CargoGateway($db);
        
    }
    
    public function processRequest() {
        switch($this->requestMethod){
            case 'GET':
                if($this->IDCargo){
                    $response = $this->getCargo($this->IDCargo);
                } else {
                    $response = $this->getAllCargos();
                }
                break;
                
            case 'POST':
                $response = $this->createCargoFromRequest();
                echo "</br>...CreatingCargo---#1</br>";
                break;        
        }
        header($response['status_code_header']);
        if($response['body']){
            echo $response['body'];
        }
    }
    
    private function getAllCargos() {
        $result = $this->CargoGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    private function getCargo($id){
        $result = $this->CargoGateway->find($id);
        if(!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
   
    private function createCargoFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        
        if (! $this->validateCargo($input)){
            echo 'unprocessableEntity';
            return $this->unprocessableEntityResponse();
        }
        $this->CargoGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }
    
    private function validateCargo($input) {
        if(! isset($input['IDShipping'])){
            return false;
        }
        if(! isset($input['Name'])){
            return false;
        }
        if(! isset($input['Weight'])){
            return false;
        }
        if(! isset($input['Type'])){
            return false;
        }
   }
   
   private function unprocessableEntityResponse(){
       $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
       $response['body'] = json_encode([
           'error' => 'Invalid input'
       ]);
   }
    
    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 04 Not Found';
        $response['body'] = null;
        return $response;
    }
}
