<?php
/**
 * Created by PhpStorm.
 * User: FIZZLE31
 * Date: 8/20/2018
 * Time: 7:23 AM
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorisation, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/feeds.php';

allow_methods(["POST"]);

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$feed = new Feeds($db);
$user->checkLoginStatus();
// $user->token = $token;

$user->getUser();

$data = $_POST;
$area = $data['area'];
$feed->getAreaId($area);
// print $id;
$feed->light_on = $data['light_on'];
$feed->user_id = $user->id;
$feed->note = $data['note'];
$arr = array(
    "Area ID" => $feed->area_id,
    "User Id" => $feed->user_id,
    "Note" => $feed->note,
    "Light On" =>$feed->light_on
);

print_r($arr);
// echo "<br>";
// echo $feed->user_id;  
// echo $token;  

$feed->updateFeed();
