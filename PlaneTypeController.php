<?php

include 'PlaneTypeGateway.php';

class PlaneTypeController {

    private $db;
    private $requestMethod;
    private $IDPlaneType;
    
    private $PlaneTypeGateway;
    
    public function __construct($db, $requestMethod, $IDPlaneType) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->IDPlaneType = $IDPlaneType;
        
        $this->PlaneTypeGateway = new PlaneTypeGateway($db);
        
    }
    
    public function processRequest() {
        switch($this->requestMethod){
            case 'GET':
                if($this->IDPlaneType){
                    $response = $this->getPlaneType($this->IDPlaneType);
                } else {
                    $response = $this->getAllPlaneTypes();
                }
                break;
                
            case 'POST':
                //$response = $this->createPlaneTypeFromRequest();
                echo "</br>send POST</br>";
                header('index.php');
                exit();
                break;
            /*
            case 'PUT':
                $response = $this->updatePlaneFromRequest($this->$IDPlaneType);
                break;
            case 'DELETE':
                $response = $this->deletePlaneType($this->$IDPlaneType);
                break;
                 * 
                 */
        }
        header($response['status_code_header']);
        if($response['body']){
            echo $response['body'];
        }
    }
    
    private function getAllPlaneTypes() {
        $result = $this->PlaneTypeGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    private function getPlaneType($id){
        $result = $this->PlaneTypeGateway->find($id);
        if(!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    /*
    private function createPlaneTypeFromRequest() {
        $input = (array)json_decode(file_get_contents('php://input'),TRUE);
        
        if(! $this->validatePlaneType($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->PlaneTypeGateway->insert($input);
    }
    
     * 
     */
    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 04 Not Found';
        $response['body'] = null;
        return $response;
    }
}
