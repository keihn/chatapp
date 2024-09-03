<?php

namespace App;

use PDOException;

class User
{

    protected $db;

    public function __construct(Database $database)
    {
        $this->db = $database->connect();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user']) ? true : false;
    }

    public function setConnectionID($token, $connection_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE user_activity SET connection_id=:connection_id WHERE token=:token");
            $stmt->bindParam(':connection_id', $connection_id);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getConnectionID($token)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_activity WHERE token=:token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getResourceID($token)
    {
        try {
            $stmt = $this->db->prepare("SELEC' FROM user_activity WHERE token=:token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_OBJ);
            return $data;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }
}
