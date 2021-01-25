<?php
// print_r($_REQUEST)


// print_r($_SERVER);
// die();
// Login to be directed here
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorisation, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';

allow_methods(["POST"]);

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = $_POST;

$user->email = $data['email'];
$user->password = $data['password'];

// $token = $user->login();
$user->login();
if($user->token){
    $user->getUser();
    $user_arr = array(
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'points' => $user->points,
        // 'latitude' => $user->latitude,
        // 'longitude' => $user->longitude,
        'token' => $user->token
    );
    print(json_encode($user_arr));
}else{
    print(json_encode(array("error"=> "Invalid login details")));
}




