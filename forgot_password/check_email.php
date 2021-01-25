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

$data = $_POST;
if($data['email']){
	$email = $data['email'];
	// echo $email;
	$query = "SELECT * FROM `users` WHERE `email` = :email";
	$stmt = $database->conn->prepare($query);
	$stmt->bindParam(":email", $email);
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num > 0){
		require '../assets/phpmailer/phpmailer.php';
		require '../assets/phpmailer/smtp.php';
		
		// require_once '../assets/phpmailer/mail_configuration.php';

		$row = $stmt->fetch(PDO::FETCH_ASSOC);


		$mail = new PHPMailer();
		$str = '';
		for($i=0;$i<8;$i++){
			$str .= mt_rand(0,9);
		 }

		$emailBody = "<div> Dear".$row["name"]. ", <br><br><p>You requested to change your account password. Here is your varification code: ".$str." </p></div>";

		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port     = 8025;  
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
		$mail->Host     = localhost; 
		$mail->Mailer   = MAILER;

		$mail->SetFrom("support@glight.com", "GLight Support");
		$mail->AddReplyTo("support@glight.com", "GLight Support");
		$mail->ReturnPath="support@glight.com";	
		$mail->AddAddress($email);
		$mail->Subject = "Forgot Password Recovery";		
		$mail->MsgHTML($emailBody);
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			echo '{';
			 '"message:" "Problem in Sending Password Recovery Email"';
			 echo '}';
		} else {
			echo '{';

			'"message:" "Please check your email to reset password!"';
			$re_query = "INSERT INTO `password_recover` SET `user_email` = '$email', `recovery_code` = '$str'";
			$re_stmt = $this->conn->prepare($re_query);
			$re_stmt->execute();

			//To be directed to the page where verificcation code would be entered
			header('location: update_password.php');
			echo '}';
		}
	}else{
		echo '{';
			echo '"message:" "Account not found"';
		echo '}';
		return false;
	}
}
 ?>
