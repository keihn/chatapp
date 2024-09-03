<?php

namespace App\Exceptions;
use Exception;



class AuthException extends Exception{

    public mixed $data;

    public function __construct(string $message = "", int $code = null, Exception $previous = null, $data = null){
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    } 

    public function getData(){
        return $this->data;
    }
}