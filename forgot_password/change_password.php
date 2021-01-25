<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorisation, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/user.php';

allow_methods(["POST"]);

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->getUser();

$data = $_POST;
//Egbon, do the validation on your side
$password = $data['password']
//Believeing you have already done the hashing from your side
$query = "UPDATE ".$this->table_name. "SET password = :password WHERE token= :token";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(":password", $password);
$stmt->bindParam(":token", $this->token);
if($stmt->execute()){
	echo '{';
		echo '"message:" "Password changed successful"';
	echo '}';
	//Direct to login page
}else{
	echo '{';
		echo '"message:" "An error occured"';
	echo '}';
}

?>