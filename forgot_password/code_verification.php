<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorisation, X-Requested-With");


include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

allow_methods(["POST"]);

$data = $_POST;

$code = $data['code'];


$query = "SELECT * FROM `password_recovery` WHERE `recovery_code` = :code";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(":code", $code);
$stmt->execute();
$num = $stmt->rowCount();

if($num>0){
	//'Don't think putting expiry time is necessary
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$email = $row['user_email'];
    $a = "PASSWORD RECOVERY";
    $str = substr(microtime(), rand(1, 9), 5);
    $token = md5("$a" . "$str");

    $uquery = "UPDATE `users` SET `token` = '$token" WHERE `email` = '$email';
    $ustmt = $this->conn->prepare($uquery);
    $ustmt->execute();
    header("Location: change_password.php");
}else{
	echo '{';
        echo '"message:" "Invalid verification code"';
    echo '}';
}

 ?>