<?php

require "dbconnect.php";

include 'PlaneTypeController.php';
include 'ShippingController.php';
include 'filesupload.php';  

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Conrol-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($uri[3] == 'planeType'){
    $IDPlaneType = null;
    if(isset($uri[4])){
        $IDPlaneType = (int)$uri[4];
    }
    $controller = new PlaneTypeController($db, $requestMethod, $IDPlaneType);
    
} else if($uri[3] == 'cargoType'){    
    $IDCagoType = null;
    if(isset($uri[4])){
        $IDCargoType = (int)$uri[4];
    }
    $controller = new CargoTypeController($db, $requestMethod, $IDCagoType);
    
} else if($uri[3] == 'Shipping'){
        
    $IDShipping = null;
    if(isset($uri[4])){
        $IDShipping = (int)$uri[4];
    }
    $controller = new ShippingController($db, $requestMethod, $IDShipping);
           
} else if($uri[3] == 'Cargo'){    
    $IDCargo = null;
    if(isset($uri[4])){
        $IDCargo = (int)$uri[4];
    }
    $controller = new CargoController($db, $requestMethod, $IDCargo);
    
} else if($uri[3]== 'Docs'){
    if(isset($uri[4])){
           $IDShipping = (int)$uri[4];
       }
        $i = 0 ;
        while(!empty($_FILES['documents']['name'][$i])){            
            $i++;
        }   
            $response = $i." files detected!";
            if($i>0){
                uploadFiles($IDShipping, $db);
            }
            header_remove();
            header('Content-Type: application/json');
            http_response_code(203);
            echo json_encode($response);
            exit();
            
        return $response;
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}
            
$controller->processRequest();
