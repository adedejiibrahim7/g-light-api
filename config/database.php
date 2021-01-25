<?php

/**
 * 
 */
class Database
{
    private $host = "localhost";
    private $db_name = "g_light_db";
    private $username = "root";
    private $password = "";

    public $conn;

    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . "; dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set name utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

function allow_methods($methods){
    $resp = array("message"=> $_SERVER["REQUEST_METHOD"]." not allowed here");
    if(!in_array(strtoupper($_SERVER["REQUEST_METHOD"]), $methods)){
        print(json_encode($resp));
        die();
    }
}

