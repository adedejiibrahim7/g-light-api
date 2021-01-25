<?php
/**
 * Created by PhpStorm.
 * User: FIZZLE31
 * Date: 8/31/2018
 * Time: 6:50 PM
 */

 $table_name = "feeds";

class Feeds
{
    private $conn;
    

    public $id;
    public $user_id;
    public $light_on;
    public $area_id;
    public $note;

    public function __construct($db){
        $this->conn = $db;
    }


    function updateFeed(){  
        //There is need to check whether to update the FEEDS table or the UPDATE table
        //Here we go 
        $check = "SELECT * FROM `feeds` WHERE `area_id` = :c_area_id ORDER BY `id` DESC";
        $check_stmt = $this->conn->prepare($check);
        $check_stmt->bindParam(":c_area_id", $this->area_id);
        $check_stmt->execute();
        $get = $check_stmt->fetch(PDO:: FETCH_ASSOC);
        //Converting the DateTime of `created` to Unix time stamp
        $created = strtotime($get['created']);
        $last_reported_status = $get['light_on'];
        $c_user_id = $get['user_id'];
        $feed_id = $get['id'];

        $check_status_query = "SELECT `status` FROM `areas` WHERE `id` = $this->area_id";
        $check_status = $this->conn->prepare($check_status_query);
        $check_status->execute();
        $fetch_status = $check_status->fetch(PDO:: FETCH_ASSOC);
        $area_status = $fetch_status['status'];
        // if($area_status == $this->status){
        //     //300 seconds = 5 minutes
        //     if($created < $created + 300){
        //         if($status == $this->light_on){
        //             $t_name = "update";
        //         }
        //     }else{
        //         if($status == $this->light_on){
        //             $t_name = "update";
        //         }
        //     }
        // }
        if($this->light_on == $last_reported_status){
            if($created < $created+300){
                $query = "INSERT INTO `update` SET `user` = :user_id, `feed_id` = :feed_id, `area_id` = :area_id, `note` = :note";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":user_id", $this->user_id);
                $stmt->bindParam(":feed_id", $this->feed_id);
                $stmt->bindParam(":area_id", $this->area_id);
                $stmt->bindParam(":note", $this->note);
                $stmt->execute();
            }else{
                if($this->light_on != $area_status){
                    $query = "INSERT INTO `feeds` SET `user_id` = :user_id, `light_on` = :light_on, `area_id` = :area_id, `note` = :note";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":user_id", $this->user_id);
                    $stmt->bindParam(":light_on", $this->light_on);
                    $stmt->bindParam(":area_id", $this->area_id);
                    $stmt->bindParam(":note", $this->note);
                    $stmt->execute();
                }else{
                    $query = "INSERT INTO `update` SET `user` = :user_id, `feed_id` = :feed_id, `area_id` = :area_id, `note` = :note";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":user_id", $this->user_id);
                    $stmt->bindParam(":feed_id", $this->feed_id);
                    $stmt->bindParam(":area_id", $this->area_id);
                    $stmt->bindParam(":note", $this->note);
                    $stmt->execute();
                }
            }
        }else{
            $query = "INSERT INTO `feeds` SET `user_id` = :user_id, `light_on` = :light_on, `area_id` = :area_id, `note` = :note";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":light_on", $this->light_on);
            $stmt->bindParam(":area_id", $this->area_id);
            $stmt->bindParam(":note", $this->note);
            $stmt->execute();
        }
    }

        function getAreaId($area){
        $query = "SELECT `id` FROM `areas` WHERE `area` = :area";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":area", $area);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $fetch = $stmt->fetch(PDO:: FETCH_ASSOC);
            $this->area_id = $fetch['id'];
            return $this->area_id;
        }else{
            echo '{';
                echo '"message:" "Invalid area input"';
            echo '}';
            return false;
        }
    }
}
