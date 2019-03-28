<?php
    class chatrooms
    {
        private $id;
        private $userId;
        private $msg;
        private $createdOn;
        protected $dbConn;

        function setId($id){
            $this->id = $id;
        }
        function getId(){
            return $this->id;
        }

        function setUserId($userId){
            $this->userId = $userId;
        }
        function getUserId(){
            return $this->userId;
        }

        function setMsg($msg){
            $this->msg = $msg;
        }
        function getMsg(){
            return $this->msg;
        }

        function setCreatedOn($createdOn){
            $this->createdOn = $createdOn;
        }
        function getCreatedOn(){
            return $this->createdOn;
        }

        public function __construct(){
            require_once('DbConnect.php');
            $db = new DBConnect();
            $this->dbConn = $db->connect();
        }

        public function saveChatRoom(){
            $stmt = $this->dbConn->prepare('insert into chatrooms values(null,:userid,:msg,:createdOn)');
            $stmt->bindParam(":userid",$this->userId);
            $stmt->bindParam(":msg",$this->msg);
            $stmt->bindParam(":createdOn",$this->createdOn);
            
            try{
                if($stmt->execute()){
                    return true;
                } else{
                    return false;
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
            
        }

        public function getAllChatRooms(){
            $stmt = $this->dbConn->prepare('select c.*, u.name from chatrooms c join users u on c.userid = u.id ORDER BY c.id DESC');
            $stmt->execute();
            $chatrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $chatrooms;
        }
    }
    
?>