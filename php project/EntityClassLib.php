<?php
class User {
    private $userId;
    private $name;
    private $phone;
    
    private $messages;
    
    public function __construct($userId, $name, $phone)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        
        $this->messages = array();
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }
}
class Friendship {
    private $friendRequesterId;
    private $friendRequesteeId;
    private $status;

    public function __construct($friendRequesterId, $friendRequesteeId, $status)
    {
        $this->friendRequesterId = $friendRequesterId;
        $this->friendRequesteeId = $friendRequesteeId;
        $this->status = $status;
    }

    public function getFriendRequesterId() {
        return $this->friendRequesterId;
    }

    public function getFriendRequesteeId() {
        return $this->friendRequesteeId;
    }

    public function getStatus() {
        return $this->status;
    }
    public function sendRequest() {
        // Insert into friendship table using SQL query
    }

    public function approveRequest() {
        // Update status in friendship table using SQL query
    }
}

class FriendshipStatus {
    private $statusCode;
    private $description;

    public function __construct($statusCode, $description)
    {
        $this->statusCode = $statusCode;
        $this->description = $description;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getDescription() {
        return $this->description;
    }
}


