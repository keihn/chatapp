<?php

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use App\Database;
use App\Conversations;

class ServerSocket implements MessageComponentInterface
{
    protected $clients;
    public $message;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;

        session_start();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later      
        $this->clients->attach($conn);

        $urlQueryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($urlQueryString, $queryStringArray);
        
        $database = new Database();
        $user = new User($database);
        $user->setConnectionID($queryStringArray['token'], $conn->resourceId);
    
        
        
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

       
        $data = json_decode($msg, true);
        
        $message = $data['message'];
        $receiver_token = $data['recieverToken'];
        $from_token = $data['senderToken'];
        $room_id = md5(uniqid());

        $database = new Database();
        $user = new User($database);
        
        //Get reciever object
        $reciever = $user->getConnectionID($receiver_token);
        $receiver_resource_id = $reciever->connection_id;

        //Get sender object
        $sender = $user->getConnectionID($from_token);
        $sender_resource_id = $sender->connection_id;

        $database = new Database();
        $conversation = new Conversations($database);
        $results = $conversation->getConversations($reciever->user_id, $sender->user_id);

        if(count($results) == 1){
            $conversation_id = $conversation->create($sender->user_id, $reciever->user_id);
            $message_sent = $conversation->saveMessage($message, $sender->user_id, $reciever->user_id, $conversation_id);
        }else{
            $message_sent = $conversation->saveMessage($message, $sender->user_id, $reciever->user_id, $room_id);
        }


        if($message_sent){
            foreach ($this->clients as $client) {
                if($client->resourceId == $receiver_resource_id){
                    $client->send(json_encode(['message' => $message, 'from' => $from_token]));
                }
            }
        }
        
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
