<?php
namespace App;

class Request {

    public array $data = [];
    public object $requestPayload;

    public function __construct(){
        // $this->requestHandler();
        // $this->purge();
    }



    public static function __callStatic($method, $args){

    }

    public function get(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
           return true;
        }
        return false;
    }

    public static function post(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           return true;
        }
        return false;
    }

    public function getFormData(){
        $this->data = json_encode($this->data);
        return $this;
    }

    // public function requestHandler(){
    //     $this->requestPayload = (object) $_POST;
       
    //     foreach ($resources as $resource => $value) {
    //         $this->data[$resource] = $value;
    //     }

    //     return $this;
    // }

    public function purge(){
        $this->requestPayload = $this->getFormData();
        return $this->requestPayload;
    }
}