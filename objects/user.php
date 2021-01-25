<?php
	/**
	 * 
	 */

	// $table_name = "users";

	class User
    {
        private $conn;
        private $table_name = "users";

        public $id;
        public $name;
        public $email;
        public $password;
        public $phone;
        public $latitude;
        public $longitude;
        public $token = null;  
        public $is_active;
        public $points;
        public $is_admin;

        function __construct($db)
        {
            $this->conn = $db;
        }

        function create()
        {
            $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password, latitude=:latitude, longitude=:longitude, token=:token";
            $stmt = $this->conn->prepare($query);
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->latitude = htmlspecialchars(strip_tags($this->latitude));
            $this->longitude = htmlspecialchars(strip_tags($this->longitude));
            $this->token = htmlspecialchars(strip_tags($this->token));

            
            $this->password = htmlspecialchars(strip_tags($this->password));
            // just in case the hashing has to be from over here
            // $this->password = hash(SHA256, $this->password);
            

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":latitude", $this->latitude);
            $stmt->bindParam(":longitude", $this->longitude);
            $stmt->bindParam(":token", $this->token);
            $stmt->bindParam(":password", $this->password);

            if ($stmt->execute()) {
                return true;
            }else{
                print_r($stmt->errorInfo());die();
                return false;
            }
        }

        function login()
        {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email=:email AND password=:password";
            $stmt = $this->conn->prepare($query);

            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);

            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                // $fetch = (PDO:: )
                $date = date("YmdHis");
                $str = substr(microtime(), rand(1, 9), 5);
                $pass = substr($this->password, rand(1, 4), 5);
                $token = md5("$date" . "$str" ."$pass");
                $insert = "UPDATE " . $this->table_name . " SET `token` = :token WHERE email = :email AND password = :password";
                $insertprepare = $this->conn->prepare($insert);

                $this->token = $token;
                $insertprepare->bindParam(":token", $this->token);
                $insertprepare->bindParam(":email", $this->email);
                $insertprepare->bindParam(":password", $this->password);
                $insertprepare->execute();
                return $this->token;
            }
        }

        function getUser()
        {
            // From every file to use this function, remember: $this->token = $token, after using the checkLoginStatus() function
            $query = "SELECT * FROM " . $this->table_name . " WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":token", $this->token);
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {

                $row = $stmt->fetch(PDO:: FETCH_ASSOC);

                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->points = $row['points'];
                // $this->latitude = $row['latitude'];
                // $this->longitude = $row['longitude'];   
                $this->token = $row['token'];

            } else {
                echo '{';
                echo '"message:" "You are not logged in"'; 
                echo '}';
                die();
            }
        }
        function get_profile(){
            $query = "SELECT * FROM " . $this->table_name . " WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":token", $this->token);
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {

                $row = $stmt->fetch(PDO:: FETCH_ASSOC);

                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->points = $row['points'];
                $this->latitude = $row['latitude'];
                $this->longitude = $row['longitude'];
                $this->token = $row['token'];

            } else {
                echo '{';
                echo '"message:" "Invalid Login details"'; 
                echo '}';
                die();
            }
        }

        function checkLoginStatus(){
            if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                $this->token = $_SERVER['HTTP_AUTHORIZATION'];
                $query = "SELECT * FROM `users` WHERE token = :token";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":token", $this->token);
                $stmt->execute();
                $num = $stmt->rowCount();
    
                if ($num > 0) {
                    
                    return $this->token;
                }else{
                    die("Not logged in");
                }
        }else{
            die("Not logged in");
        }
        }
       
          function search($data){
        unset($_SESSION['error']);
        $query = "SELECT * FROM `users` WHERE `name` = :data OR `email` = :data";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":data", $data);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count>0){
        $fetch = $stmt->fetch(PDO:: FETCH_ASSOC);
        $this->name = $fetch['name'];
        $this->email = $fetch['email'];
        $this->phone = $fetch['phone'];
        $this->latitude = $fetch['latitude'];
        $this->longitude = $fetch['longitude'];
        $this->points = $fetch['points'];
        }else{
            $_SESSION['$error'] = "User with the searched name or email doesn't exist";
        }
    }
    }


    // function search($data){
    //     unset($_SESSION['error']);
    //     $query = "SELECT * FROM `users` WHERE `name` = :data OR `email` = :data";
    //     $stmt = $database->conn->prepare($query);
    //     $stmt->bindParam(":data", $data);
    //     $stmt->execute;
    //     $count = $stmt->rowCount();
    //     if($count>0){
    //     $fetch = $stmt->fetch(PDO:: FETCH_ASSOC);
    //     $this->name = $fetch['name'];
    //     $this->email = $fetch['email'];
    //     $this->phone = $fetch['phone'];
    //     $this->latitude = $fetch['latitude'];
    //     $this->longitude = $fetch['longitude'];
    //     $this->points = $fetch['points'];
    //     }else{
    //         $_SESSION['$error'] = "User doesn't exist";
    //     }
    // }