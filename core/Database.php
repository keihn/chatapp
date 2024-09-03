<?php

namespace App;

use PDOException;
use PDO;

Class Database {

    private $username =  'root';
    private $host = 'localhost';
    private $password = '';

    public $stmt;

    public $conn;

    public function __construct(){

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=chat", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Failed to connect to database with error message:" . $e->getMessage());
        }
     
    }

    public function connect()
    {
        return $this->conn;
    }

//     public function bind($param, $value, $type = null){
//         if (is_null($type)){
//             switch(true){
//                 case is_int($value):
//                     $type = PDO::PARAM_INT;
//                     break;
//                 case is_bool($value):
//                     $type = PDO::PARAM_BOOL;
//                     break;
//                 case is_null($value):
//                     $type = PDO::PARAM_NULL;
//                     break;
//                 default:
//                     $type = PDO::PARAM_STR;
//             }
//         }

//         $this->stmt->bindValue($param, $value, $type);
//     }
    

//     /**
//      * Execute a query
//      * @return bool
//      */
//     public function execute(){
//         return $this->stmt->execute();
//     }

//     public function resultSet(){
//         $this->execute();
//         return $this->stmt->fetchAll(PDO::FETCH_OBJ);
//     }

//     public function single(){
//         $this->execute();
//         return $this->stmt->fetch(PDO::FETCH_OBJ);
//     }
    
}