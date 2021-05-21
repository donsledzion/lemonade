<?php

class Database {
    private $pdo;
    
    public function __construct($host, $dbname, $username, $password) {
        $connection = new PDO ('mysql:host='.$host.'; dbname='.$dbname.';charset=utf8',$username,$password);
        $connection->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
        $this->pdo = $connection;
    }
    
    public function query($stm, $id){
        $statement = $this->pdo->prepare($stm);
        if($id){
            $statement->execute(array($id));
        } else {
            $statement->execute();
        }
        if($stm){
            $data = $statement->fetchAll();
            return $data;
        }
    }
    
    public function prepare($input){
        $statement = $this->pdo->prepare($input);
        return $statement;
    }
    
    public function prepareAndExec($inputStatement, $inputData){         
        $statement = $this->pdo->prepare($inputStatement);        
        $statement ->execute($inputData);        
        return $statement;
    }
    
    public function execute($input){        
        try {
        $statement = $this->pdo->execute(array($input));
        } catch (\PDOException $e) {            
        }
        return $statement ;
    }
}