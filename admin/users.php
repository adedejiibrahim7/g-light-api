<?php
    require 'static/header.php';
    include_once '../objects/user.php';

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

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

    if(isset($_GET['user-search'])){
        // $query = "SELECT * FROM `users` WHERE `name` = :search OR `email` = :search"
        $user->search($_GET['user-search']);
    }

?>

<div class="wrapper center">
    <div class="row">
        <div class="center col-sm-6">
            <form action="" class="form-inline div-auto">
                <input type="text" name="user-search" placeholder="Search User" id="user-search" class="form-control search-l">
                <input type="submit" id="search" class="btn btn-lg btn-primary search-btn" name="Search" value="Search">
            </form>
            <div>
                <?php if(isset($_SESSION['error'])){ ?>
                    
                    <p><?php echo $SESSION['error']; ?></p>
                <?php } elseif(isset($_GET['user-search']) && !isset($_SESSION['error'])){ ?>
                <div class="row">
                    <div class="col-sm-6 col-xs-6" style="text-align: center; padding-left: 50px;">
                        <p>Name:</p>
                        <p>Email:</p>
                        <p>Phone:</p>
                        <p>Latitude</p>
                        <p>Longitude</p>
                        <p>Points</p>
                    </div>
                    <div class="col-sm-6 col-xs-6" style="text-align: left">
                        <p><?php echo $user->name; ?></p>
                        <p><?php echo $user->email ?></p>
                        <p><?php echo $user->phone; ?></p>
                        <p><?php echo $user->latitude; ?></p>
                        <p><?php echo $user->longitude; ?></p>
                        <p><?php echo $user->points; ?></p>

                    </div>
                </div>
               <?php } ?>
            </div>
        </div>

        <div class="col-sm-6 panel-div">
            <h3 class="display-5">Users</h3>

            <table class="table table-striped">
                <th>
                    <td>S/N</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Latitude</td>
                    <td>Longitude</td>
                    <td>Points</td>
                </th>
               <?php
               $query = "SELECT * FROM `users`";
               $stmt = $database->conn->prepare($query);
               $stmt->execute();
               $count = $stmt->rowCount(); 
            
               for($i=1; $i<=$count; $i++){
                //    echo "Done";
                       $query = "SELECT * FROM `users` WHERE `id` = '$i'";
                       $stmt = $database->conn->prepare($query);
                       $stmt->execute();
                       $fetch = $stmt->fetch(PDO:: FETCH_ASSOC);
                       $name = $fetch['name'];
                       $email = $fetch['email'];
                       $latitude = $fetch['latitude'];
                       $longitude = $fetch['longitude'];
                       $points = $fetch['points'];
                    //    $count = $query->rowCount();
                   ?>
                <tr>
                <td></td>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $latitude; ?></td>
                    <td><?php echo $longitude; ?></td>
                    <td><?php echo $points; ?></td>
                    
                </tr>

                <?php } ?> 
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 panel-div">
            <h3 class="display-5">Add</h3>
        </div>
        <div class="col-sm-3 panel-div">
            <h3 class="display-5">Edit</h3>
        </div>
        <div class="col-sm-3 panel-div">
            <h3 class="display-5">Delete</h3>
        </div>
       
    </div>
</div>

<script>
   
    $(function(){

        $('#search').click(function(){
            var search = $('#user-search').val();
            var datastring = 'search=' +search;
            
            // alert(datastring);

            $.ajax({
                type: 'GET',
                url: 'users.php',
                data: datastring,
                cache: false,
                success: function(html){
                    return html;
                }
            });
            return false;
        });
        return false;
    });
    
</script>