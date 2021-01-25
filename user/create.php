<?php
/**
 * Created by PhpStorm.
 * User: FIZZLE31
 * Date: 8/19/2018
 * Time: 9:48 AM
 */
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
// die(file_get_contents("php://input"));
// print_r($_POST);
// die();
$data = $_POST;
$user->name = $data['name'];
$user->email = $data['email'];
$user->password = $data['password'];
$user->longitude = $data['longitude'];
$user->latitude = $data['latitude'];
$user->token = $data['token'];

if($user->create()){
    echo '{';
        echo '"message:" "Account Created"';
    echo '}';
}else{
    echo '{';
        echo '"message:" "Unable to create account"';
    echo '}';
}
