<?php

namespace App;


use App\Database;
use App\Request;
use App\Exceptions\AuthException;

Class Auth {

    private $username;
    private $password;

    protected $db;

    private static $status = 1;

    private array $tables = [
        'activity' => 'user_activity',
        'users' => 'users'
    ];

    public function __construct(Database $database){
       $this->db = $database->connect();
    }

    public function login( $username, $password){
        try {
            $stmt = $this->db->prepare("SELECT `uuid`, `username`, `password` FROM users WHERE username=:username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if(!$result = $stmt->fetch(\PDO::FETCH_OBJ)){
                throw new AuthException('Login failed', 401);
            }

            if(password_verify($password, $result->password)){
               
                session_start();
                $this->createCookie($result);
                $token = md5(uniqid());


                if(!$this->activityExists($result->uuid)){
                    $stmt_activity = $this->db->prepare("INSERT INTO user_activity (`user_id`, `status`, `token`, `updated_at`) VALUES (:user_id, :status, :token, NOW())");
                    $stmt_activity->bindParam(':user_id', $result->uuid);
                    $stmt_activity->bindParam(':status', Auth::$status);
                    $stmt_activity->bindParam(':token', $token);
                    $stmt_activity->execute();

                    $_SESSION['user'] = [
                        'uuid' => $result->uuid,
                        'username' => $result->username,
                        'token' => $token
                    ];

                    header('Location: index.php');
                }
                elseif($this->setUserActive($result->uuid, $token)){

                    $_SESSION['user'] = [
                        'uuid' => $result->uuid,
                        'username' => $result->username,
                        'token' => $token
                    ];

                    header('Location: index.php');

                }


                
            }

        } catch (\PDOException $e) {
            print_r($e->getMessage());
            throw new AuthException('Login failed', 401);
        }
    }

    public function register ($username, $password){

        $password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO users (`uuid`, `username`, `password`) VALUES (UUID(), :username, :password)'
            );
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new AuthException('Registration failed', 401);
        }
        
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: login.php');
    }

    protected function createCookie($result){
        $cookieName = 'chatappUserPiece';
        $cValue = $result->uuid;
        $cExpire = time() + (86400 * 7);
        setcookie($cookieName, $cValue, $cExpire, '/');
    }

    protected function activityExists($user_id){
        $stmt = $this->db->prepare("SELECT user_id FROM user_activity WHERE user_id=:user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if($stmt->fetch()){
            return true;
        }
        return false;
    }

    public function setUserActive($user_id, $token){
        try {
            $stmt = $this->db->prepare("UPDATE user_activity SET `status`=:status, `token`=:token, updated_at=NOW() WHERE user_id=:user_id");
            $stmt->bindParam(':status', Auth::$status);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }



    }
    public function setConnectionId($user_id, $connection_id){
        try {
            $stmt = $this->db->prepare("UPDATE user_activity SET `connection_id`=:id, updated_at=NOW() WHERE user_id=:user_id");
            $stmt->bindParam(':id', $connection_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return true;

        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }

        return false;

    }
}
