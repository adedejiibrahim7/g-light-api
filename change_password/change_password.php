<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorisation, X-Requested-With");


include_once 'config/database/php';
include_once 'objects/user.php';

allow_methods(["POST"]);

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = $_POST;

$user->checkLoginStatus();
$old_password = $data['old_password'];
$new_password = $data['new_password'];

$check = "SELECT * FROM".$this->table_name. "WHERE `token` = :token AND `password` = :password";
$stmt = $this->conn->prepare($check);
$stmt->bindParam(":token", $this->token);
$stmt->bindParam(":password", $old_password);
$count = $stmt->rowCount();
if($count>0){
		$row = $ustmt->fetch(PDO:: FETCH_ASSOC);
		$password = $row['password'];
		if($new_password == $password){
			$update = "UPDATE".$this->table_name. "SET `password` = :npass WHERE `token` = :ntoken";
			$ustmt = $this->conn->prepare($update);
			$ustmt->bindParam(":npass", $new_password);
			$ustmt->bindParam(":ntoken", $this->token);
			if($ustmt->execute()){
				echo '{';
					echo '"message:" "Password change successful"';
				echo '}';
			}else{
				echo '{';
					'"message:" "An error occurred"';
				echo '}';
		}else{
			echo '{';
				echo '"message:" "Incorrect Password"';
			echo '}';
		}
}
	}

	}

	?>
