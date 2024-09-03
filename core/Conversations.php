<?php


namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class Conversations{


    private $db;
    private static $status = 1;
    public function __construct(Database $database) {
        $this->db = $database->connect();
    }

    public function create($sender, $receiver){
        try {
            $stmt = $this->db->prepare("INSERT INTO conversations (`uuid`, `sender_id`, `receiver_id`, `created_at`) VALUES (UUID(), :sender, :receiver, NOW())");
            $stmt->bindParam(':sender', $sender);
            $stmt->bindParam(':receiver', $receiver);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        
    }


    public function getOnlineUsers()
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM  user_activity AS activities 
            INNER JOIN users ON activities.user_id=users.uuid WHERE updated_at >= NOW() - INTERVAL 5 MINUTE' 
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function saveMessage($message, $sender, $receiver, $room_id)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO messages (`uuid`, `content`, `sender`, `receiver`,  `created_at`)
                VALUES (UUID(), :content, :sender, :receiver,  NOW())");
            $stmt->bindParam(':content', $message);
            $stmt->bindParam(':sender', $sender);
            $stmt->bindParam(':receiver', $receiver);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
            die;
        }
        
    }


    public function exists($room_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM  conversations WHERE uuid=:uuid');
        $stmt->bindParam(':uuid', $room_id);
        $stmt->execute();
        if($stmt->fetchAll(\PDO::FETCH_OBJ)){
            return true;
        }
        return false;
    }


    
    /**
     * Get coversation between users
     * @param string $receiver_id
     * @param string $sender_id
     * @return array;
     * 
     */
    public function getConversations($receiver_id, $sender_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM  conversations WHERE sender_id=:sender_id AND receiver_id=:receiver_id");
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $stmt->execute();
        if($stmt->fetchAll(\PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }
}