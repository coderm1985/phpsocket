<?php
    class users{
        private $id;
        private $name;
        private $email;
        private $loginStatus;
        private $lastLogin;
        public $dbConn;

        function setId($id){
            $this->id = $id;
        }
        function getId(){
            return $this->id;
        }

        function setName($name){
            $this->name = $name;
        }
        function getName(){
            return $this->name;
        }

        function setEmail($email){
            $this->email = $email;
        }
        function getEmail(){
            return $this->email;
        }

        function setLoginStatus($LoginStatus){
            $this->loginStatus = $LoginStatus;
        }
        function getLoginStatus(){
            return $this->loginStatus;
        }

        function setLastLogin($LastLogin){
            $this->lastLogin = $LastLogin;
        }
        function getLastLogin(){
            return $this->lastLogin;
        }

        public function __construct () {
            require_once('DbConnect.php');
            $db = new DBConnect();
            $this->dbConn = $db->connect();
        }

        public function save(){
            $sql = "INSERT INTO `users`(`id`,`name`,`email`,`login_status`,`last_login`) VALUES(null,:name,:email, :loginStatus, :lastLogin)";

            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(":name",$this->name);
            $stmt->bindParam(":email",$this->email);
            $stmt->bindParam(":loginStatus",$this->loginStatus);
            $stmt->bindParam(":lastLogin",$this->lastLogin);
           
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

        public function getUserByEmail(){

            $stmt = $this->dbConn->prepare('select * from users where email = :email');
            $stmt->bindParam(':email',$this->email);
            try{
                if($stmt->execute()){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $user;
        }

        public function getUserById(){

            $stmt = $this->dbConn->prepare('select * from users where id = :id');
            $stmt->bindParam(':id',$this->id);
            try{
                if($stmt->execute()){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $user;
        }


        public function updateLoginStatus(){
            $stmt = $this->dbConn->prepare('update users set login_status= :loginStatus, last_login= :lastLogin where id= :id');
            $stmt->bindParam(':loginStatus',$this->loginStatus);
            $stmt->bindParam(':lastLogin',$this->lastLogin);
            $stmt->bindParam(':id',$this->id);

            try{
                if($stmt->execute()){
                    return true;
                }
                else{
                    return false;
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

        public function getAllUsers(){
            $stmt = $this->dbConn->prepare('select * from users');
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        }
    }
?>