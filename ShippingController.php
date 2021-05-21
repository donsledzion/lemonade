<?php

include 'ShippingGateway.php';

class ShippingController {

    private $db;
    private $requestMethod;
    private $IDShipping;
    
    private $ShippingGateway;
    
    private $errors;
    
    public function __construct($db, $requestMethod, $IDShipping) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->IDShipping = $IDShipping;
        
        $this->ShippingGateway = new ShippingGateway($db);
        
        $this->errors = "";
        
    }
    
    public function processRequest() {
        switch($this->requestMethod){
            case 'GET':
                if($this->IDShipping){
                    $response = $this->getShipping($this->IDShipping);
                } else {
                    $response = $this->getAllShippings();
                }
                break;
                
            case 'POST':                
                $response = $this->createShippingFromRequest();                                
                break;        
        }
        header($response['status_code_header']);
        header_remove();
        
        header('Content-Type: application/json');
        http_response_code(205);
            echo json_encode($response);
            exit();
        if($response['body']){
            echo $response['body'];
        }
    }
    
    private function getAllShippings() {
        $result = $this->ShippingGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    private function getShipping($id){
        $result = $this->ShippingGateway->find($id);
        if(!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    private function createShippingFromRequest() {
         
    // import of php://input content (form data except files)    
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!empty($input['plane_type'])){
            $planeType = $this->db->query("SELECT * FROM typesofplane WHERE IDPlaneType=?;",$input['plane_type']);
            $planeDeadweight = $planeType[0][2];
            $planeContact = $planeType[0][3];

            $cargosCount = (int)$input['cargos_count'];
        }
        
    // validating shipping data    
    // if shipping form isn't correct error is returned to front-end
        $this->validateShipping($input);
            
        
    // if shipping data is correct all cargos are checked
    // if any of cargos form is invalid -> error messages are returned to fron-end
        $cargosOK = true;
        for($i = 1 ; $i <= $cargosCount ; $i++) {
            error_reporting(E_ERROR | E_PARSE);
            $cargo->CargoNumber=$i;
            $cargo->Name=$input['cargo_name_'.$i];
            $cargo->Weight=$input['cargo_weight_'.$i];
            $cargo->Type=$input['cargo_type_'.$i];
            $cargo->Deadweight=$planeDeadweight;
            $cargoJSON = json_encode($cargo);
            if(!$this->validateCargo($cargoJSON)){
                $cargosOK = false;
            }             
        }
    // if both shipping form and cargos form are valid we can proceed to 
    // to insert data to database
        $this->ShippingGateway->insertShipping($input);        
        
        $LastShippingQuery = $this->db->query("SELECT IDShipping FROM shippings ORDER BY IDShipping DESC;",null);
        $IDShipping = $LastShippingQuery[0][0];
        //above query obtain ID of last inserted shipping (it's vulnerable but - "works on ma machine")
        // just kidding - I know that I need to handle it in some other way...
        
        
        // inserting cargos to database and assigning last shipping ID to them
        for($i = 1 ; $i <= $cargosCount ; $i++) {
            $cargo->IDShipping=$IDShipping;
            $cargo->Name=$input['cargo_name_'.$i];
            $cargo->Weight=$input['cargo_weight_'.$i];
            $cargo->CargoType=$input['cargo_type_'.$i];
            $cargo->Deadweight=$planeDeadweight;
            
            $cargoJSON = json_encode($cargo);
            
            $cargoJSON = (array) json_decode($cargoJSON, TRUE);
            $this->ShippingGateway->insertCargo($cargoJSON);
            
        }
        //$this->mailShipping();
        
        $response->status = "All OK";
        $response->IDShipping = $IDShipping;
        header_remove();
        header('Content-Type: application/json');
        http_response_code(203);
        echo json_encode($response);
        exit();
    }
    
    private function mailShipping(){
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        
        $PlaneInfoQuery = $this->db->query("SELECT * FROM typesofplane WHERE IDPlaneType=?;",$input['plane_type']);
        $PlaneName          = $PlaneInfoQuery[0][1];
        $PlaneDeadweight    = $PlaneInfoQuery[0][2];
        $PlaneContact       = $PlaneInfoQuery[0][3];
        
        $ShippingDate       = $input['shipping_date'];
        
        //==============================================================================================
        // CREATING AN EMAIL
        //==============================================================================================
        // HEADERS AND SOME INITIAL DATA
        
         $to = $PlaneContact;
         $from = "noreply@lemonshipping.com";
         $subject = "New shipping! Important!";
         
         $message = "<h3>New shipping from ".$from."</h3>";
         $message .= "<b>We have new shipping from our client. All details are listed below:</b><br><br>";
         //==============================================================================================
         // SHIPPING DATA TABLE
         //==============================================================================================
         $message .= "<table style=\"border: 1px solid black; text-align:center;\">";
         $message .="<tr style=\"background-color:lightgray;\"><th>From</th><th>Destination</th><th>Shipping Date</th><th>Plane type</th></tr>";         
         $message .="<tr><td>".$input['ship_from']."</td><td>".$input['ship_to']."</td><td>".$input['shipping_date']."</td><td>".$PlaneName."</td></tr>";
         //$message .="<tr>Row 3</tr>";
         //$message .= "<b>We are shipping ".$input['cargo_name_1']." from ".$input['ship_from']."to ".$input['ship_to']." on ".$input['shipping_date']." <b>";
         $message .= "</table>";
         //==============================================================================================
         // CARGOS DATA TABLE
         //==============================================================================================
         $message .= "<br><br><b>Cargos to be send with shipping:</b>";
         $i = 1;
         $totalWeight = (int)0 ;
         $message .= "<table style=\"border: 1px solid black; text-align:center;\">";
         $message .= "<tr style=\"background-color:lightgray;\"><th>No.</th><th>Name</th><th>Weight [kg]</th><th>Type</th></tr>";
         while(isset($input['cargo_name_'.$i])){
             $message .= "<tr><td>".$i."</td><td>".$input['cargo_name_'.$i]."</td><td>".$input['cargo_weight_'.$i]."</td><td>".$input['cargo_type_'.$i]."</td></tr>" ;
             $totalWeight += (int)$input['cargo_weight_'.$i];
             $i++;
         } 
         $message .= "<tr style=\"border:1px solid black;\"><td colspan=\"2\" style=\"text-align:right;\"><b>Total:</b></td><td><b>".$totalWeight." kg</b></td><td></td></tr>";
         $message .= "</table>";
         
         
         //==============================================================================================
         // FOOTER
         //==============================================================================================
         $message .= "<br>All details are availble in database";
         $message .= "<br><br>this message was generated automatically, please do not reply to it";        
         
         
         $header = "From:".$from." \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         error_reporting(E_ERROR | E_PARSE);
         $retval = mail ($to,$subject,$message,$header);
         if(!$retval){
            error_reporting(E_ERROR | E_PARSE);            
            $this->errors->mailError = "Sorry! Mail can't be sent! [error]";            
            header_remove();
            header('Content-Type: application/json');
            http_response_code(203);
            echo json_encode($this->errors);
            exit();
         }
      
         
        //==============================================================================================
        
    }
    
    private function validateShipping($input) { 
        $shipping_ok = true;
        error_reporting(E_ERROR | E_PARSE);
        if(empty($input['plane_type'])){
            $this->errors->planeTypeError = "please select one of plane types";
            $shipping_ok = false;
        }
        if(empty($input['ship_from'])){
            $this->errors->shipFromError = "where are you shipping from?";
            $shipping_ok = false;
        }
        if(empty($input['ship_to'])){
            $this->errors->shipToError = "please choose shipping destination";
            $shipping_ok = false;
        }
        if(empty($input['shipping_date'])){
            $this->errors->shipDateError = "you have to pick up some date";
            $shipping_ok = false;
        } else {
            if(date('N', strtotime($input['shipping_date'])) >= 6) {
                $this->errors->shipDateError = "invalid date (only weekdays allowed)";
                $shipping_ok = false;
            }
        }
        if(!$shipping_ok){
            header_remove();
            header('Content-Type: application/json');
            http_response_code(203);
            echo json_encode($this->errors);
            exit(); 
        } else {
            return true;
        }
        
   }
   
   private function validateCargo($input) {
        $input = (array) json_decode($input, TRUE);
       
        $cargo_ok = true;
        
        if(empty($input['Name'])){            
            $this->errors->cargoNameError = "[Cargo no".$input['CargoNumber']."] error - Please name the cargo!";
            $cargo_ok = false;
        }
        
        if(empty($input['Weight'])){
            $this->errors->cargoWeightError = "[Cargo no".$input['CargoNumber']."] error - Please specify weight of cargo!";
            $cargo_ok = false;
        } else {
            if (((int)$input['Weight'])>((int)$input['Deadweight'])){
                $this->errors->cargoWeightError = "[Cargo no".$input['CargoNumber']."] error - Cargo weight exceed plane's maximum!";
                $cargo_ok = false;
            }
            if(((int)$input['Weight'])<=0){
                $this->errors->cargoWeightError = "[Cargo no".$input['CargoNumber']."] error - Weight must be positive value!";
                $cargo_ok = false;
            }
        }
        if(! isset($input['Type'])){
            $this->errors->cargoTypeError = "[Cargo no".$input['CargoNumber']."] error - Please specify type of cargo!";
            $cargo_ok = false;
        }
        if(!$cargo_ok){
            header_remove();
            header('Content-Type: application/json');
            http_response_code(203);
            echo json_encode($this->errors);
            exit(); 
        } else {
            return true;
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