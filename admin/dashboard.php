<?php
session_start();
if(isset($_SESSION['Username'])){
    $pageTitle='Dashboard'; 

    include 'init.php';
    /***welcome Dashboard Page */
//echo  'welcome ' . $_SESSION['Username'];


/***
 * getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = 'DESC' , $limit = NULL)
 */


$usersNum = 5;  //Number of Latest Users
$latestUsers = getAllFrom("*", "users", "WHERE GroupID != 1", "", "UserID", "DESC" , $usersNum); //fetches latest users array
// $latestUsers = getLatest("*", "users", "UserID", $usersNum); 


$itemsNum = 5;  //Number of Latest Items
$latestItems = getAllFrom("*", "items", "", "", "ItemID", "DESC" , $itemsNum); //fetches latest items array
// $latestItems = getLatest("*", "items", "Item_ID", $itemsNum); 

$commentsNum = 3;

?>

<div class="home-stat">
    <div class="container text center">
        <h1>Dashboard</h1>
        <div class="row">

            <div class="col-md-3">
                <div class="stat st-members">
                   <div class="info">
                    <i class="fa fa-users"></i>
                        Total Members
                        <span>
                            <a href="members.php"><?php echo countItem('UserID' , 'users');?></a>
                        </span>
                   </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-pending">
                    <div class="info">
                    <i class="fa fa-user-plus"></i>
                         Pending Members
                        <span>
                            <a href="members.php?action=manage&page=pending"><?php echo checkItem("RegStatus" , "users" , 0 );?></a>
                        </span>
                    </div>  
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-items">
                    <div class="info">
                    <i class="fa fa-tag"></i>
                        Total Items
                        <span>
                            <a href="items.php"><?php echo countItem('ItemID' , 'items');?></a>
                        </span> 
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-comments">
                    <div class="info">
                    <i class="fa fa-comments"></i>
                        Total Comments
                        <span>
                        <a href="comments.php"><?php echo countItem('CommentID' , 'comments');?></a>
                        </span>
                    </div>
                </div>
            </div>


        </div><!--end of row-->
    </div><!--end of container-->
</div><!--end of home-stats-->



<div class="latest">
    <div class="container">
        <div class="row dash-row">

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-users"></i> Latest <?php echo $usersNum;?> Registered Users
                        <span class="toggle-info float-end">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>            <!--end of card heading-->
                    <div class="card-body"> 
                        <ul class="list-unstayled latest-users">
                            <?php
                            if(! empty($latestUsers)){
                                foreach($latestUsers as $user){
                                    echo '<li>' ;
                                        echo $user['Username'];
                                        echo '<a href= "members.php?action=edit&userid=' . $user['UserID'] . '">';
                                            echo '<span class="btn btn-success float-end">'; //pull-right & pull-left is replaced by 
                                                                                            //float-right & float-left in bootstrap 4   
                                                                                            //Then float-start & float-end in bootstrap 5
                                                                                            //read more from the link below
                                                                                            //https://www.webdevsplanet.com/post/bootstrap-pull-left-and-pull-right-not-working
                                                echo '<i class="fa fa-edit"></i>Edit ';
                                                if($user['RegStatus'] == 0){
                                                    echo "<a href = 'members.php?action=activate&userid= " . $user['UserID'] . "' class ='btn btn-info float-end activate'> <i class='fa fa-check'></i>Activate</a>";
                                                    
                                                }
                                            echo '</span>';
                                        echo '</a>';
                                    echo '</li>';
                                }
                            }else{
                                echo 'There\'s No Members To Show ';
                            }
                            ?>
                        </ul>
                    </div>             <!-- end of panel body-->
                </div>             <!--end of panel-->
            </div>             <!--end of col-sm-6-->


            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-tag"></i> Latest <?php echo $itemsNum; ?> Items
                        <span class="toggle-info float-end">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>            <!--end of panel heading-->
                    <div class="card-body"> 
                        <ul class="list-unstayled latest-users">
                                <?php
                            if(! empty($latestItems)){
                                foreach($latestItems as $item){
                                    echo '<li>' ;
                                        echo $item['Name'];
                                        echo '<a href= "items.php?action=edit&itemid=' . $item['ItemID'] . '">';
                                            echo '<span class="btn btn-success float-end">'; //pull-right & pull-left is replaced by 
                                                                                            //float-right & float-left in bootstrap 4   
                                                                                            //Then float-start & float-end in bootstrap 5
                                                                                            //read more from the link below
                                                                                            //https://www.webdevsplanet.com/post/bootstrap-pull-left-and-pull-right-not-working
                                                echo '<i class="fa fa-edit"></i>Edit ';
                                                if($item['Approve'] == 0){
                                                    echo "<a href = 'items.php?action=approve&itemid=".$item['ItemID']."'  class ='btn btn-info float-end activate'> <i class='fa fa-check'></i>Approve</a>";
                                                    
                                                }
                                            echo '</span>';
                                        echo '</a>';
                                    echo '</li>';
                                }
                            }else{
                                    echo 'There\'s No Items To Show ';
                                }
                                ?>
                            </ul>
                    </div>             <!-- end of panel body-->
                </div>             <!--end of panel-->
            </div>             <!--end of col-sm-6-->

        </div><!--end of row-->
        <div class="row dash-row"> <!--start of 2nd row-->

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-comment"></i> Latest <?php echo $commentsNum; ?> Comments
                        <span class="toggle-info float-end">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>            <!--end of card heading-->
                    <div class="card-body"> 
                        <?php
                        //select all users except admin
                        $stmt = $conn->prepare("SELECT 
                                                comments.*, users.Username AS Member 
                                        FROM 
                                            comments 
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.User_ID 
                                        ORDER BY
                                            CommentID DESC
                                        LIMIT $commentsNum");

                        //execute statment
                        $stmt->execute();

                        //assign to variable
                        $comments = $stmt->fetchAll();
                        if(! empty($comments)){
                            foreach($comments as $comment){
                                echo '<div class="comment-box">';
                                    echo '<span class="member-n">
                                         <a href = "members.php?action=edit&userid=' . $comment['User_ID'] . '" >
                                            ' .  $comment['Member'] . '</a></span>';
                                    echo '<p class="member-c">' .  $comment['Comment'] . '</p>';
                                echo '</div>';
                            }
                        }else{
                            echo 'There\'s No Comments To Show ';
                        }
                        ?>

                    </div>             <!-- end of card body-->
                </div>             <!--end of card-->
            </div>             <!--end of col-sm-6-->
        </div>    <!--end of 2nd row-->



    </div><!--end of container-->
</div><!--end of latest-->


<?php
include $tpl . 'footer.php';
}


else{
  //  echo 'you ere not authorized to view this page'; //in case no session is set that is no match found for user
    header('location: index.php'); //redirect index page to login 
    exit();
}


 ?>