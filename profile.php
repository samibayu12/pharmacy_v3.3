<?php
//ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Profile';
 include 'init.php';
 if (isset($_SESSION['user'])){
     $getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");
     $getUser->execute(array($sessionUser));
     $info = $getUser->fetch();
     //storing the id of the user logged in to use it later to bring his data
     $userid = $info['UserID'];
?>

<h1 class="text-center">My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="card">
            <div class="card-header">My Information</div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li> <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Name:</span> <?php echo $info['Username'];?> 
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span>Email:</span> <?php echo $info['Email'];?> 
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name:</span> <?php echo $info['FullName'];?> 
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date:</span> <?php echo $info['Date'];?> 
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favorite Category: </span>
                    </li>
                </ul>
                <a href="#" class="btn btn-primary">Edit Information</a>
            </div>
        </div>
    </div>
</div>

<div id="my-items" class="latest-ads block">
    <div class="container">
        <div class="card">
            <div class="card-header">My Items</div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "ItemID") ;
                    if (! empty($myItems)){
                        //echo '<div class="row">';
                        foreach($myItems as $item){
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="card">';
                                    echo '<div class="card-body">';
                                        echo '<div class = "thumbnail item-card">';
                                            if ($item['Approve'] == 0){ echo '<span class="approve-status">Waiting Approval</span>';}
                                            echo '<span class="price-tag"> $ ' . $item['Price'] . '</span>';
                                            echo '<img class = "img-responsive" src="images/7.png" alt = "" />';
                                            echo '<div class = "caption">';
                                                echo '<h3><a href="items.php?itemid=' . $item['ItemID'] . ' ">' . $item['Name'] . '</a></h3>';
                                                echo '<p>' . $item['Description'] . '</p>';
                                                echo '<div class="date">' . $item['AddDate'] . '</div>';
                                            echo '</div>'; //end of caption div
                                        echo '</div>';    // end of thumbnail div
                                    echo '</div>';    //end of card body
                                echo '</div>';    //end of card 
                            echo '</div>';    //end of card col-..
                        }
                       // echo '</div>';
                    }else{
                        echo 'Sorry There\'s No Ads To Show </br> <a href="newAd.php">New Ad </a> ';
                    }
                    ?>
                </div> <!--end of row-->
            </div>  <!--end of card body-->
        </div>
    </div>
</div>

<div class="latest-comments block">
    <div class="container">
        <div class="card">
            <div class="card-header">Latest Comments</div>
            <div class="card-body">
                <?php  
                    $myComments = getAllFrom("Comment", "comments", "WHERE User_ID = $userid", "", "CommentID"); 
                    //select all users except admin
                    // $stmt = $conn->prepare("SELECT 
                    //                         Comment 
                    //                 FROM 
                    //                     comments 
                    //                 WHERE 
                    //                     User_ID = ? ");

                    // //execute statment
                    // $stmt->execute(array($info['UserID']));

                    // //assign to variable
                    // $comments = $stmt->fetchAll();



                    //if there is comment print it else no print
                    if (! empty($myComments)){
                        foreach($myComments as $comment){
                            echo '<p>' . $comment['Comment'] . '</p>';
                        }
                    }else{
                        echo 'There\'s No Comments To Show ' ; 
                    }  
                ?>
            </div>
        </div>
    </div>
</div>
<?php
 }else{
     header('location: login.php');
     exit();
 }

//include "includes/template/footer.php";
 include $tpl . 'footer.php';
 //ob_end_flush(); // Release the output
?>