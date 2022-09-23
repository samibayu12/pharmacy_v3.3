<?php
session_start();
$pageTitle="Comments";
if(isset($_SESSION['Username'])){

    include 'init.php';


$action=isset($_GET['action']) ? $_GET['action'] : 'manage'; 
 
//start manage page
if($action == 'manage'){//manage page
   
    //select all users except admin
$stmt = $conn->prepare("SELECT 
                            comments.*, items.Name, users.Username AS Member 
                        FROM 
                            comments 
                        INNER JOIN 
                            items
                        ON 
                            items.ItemID = comments.Item_ID
                        INNER JOIN
                            users
                        ON
                            users.UserID = comments.User_ID 
                        ORDER BY
                            CommentID DESC");

//execute statment
$stmt->execute();

//assign to variable
$comments = $stmt->fetchAll();
if (! empty($comments)){
?>

<h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                       <td>#ID</td> 
                       <td>Comment</td> 
                       <td>Item Name</td> 
                       <td>User Name</td> 
                       <td>Added Date</td> 
                       <td>Control</td> 
                    </tr>
<?php 
foreach($comments as $comment){
    echo '<div class="table-input">';
                    echo "<tr>";
                    echo  "<td>" . $comment['CommentID'] . "</td> ";
                    echo  "<td>" . $comment['Comment'] . "</td> ";
                    echo  "<td>" . $comment['Name'] . "</td> ";
                    echo  "<td>" . $comment['Member'] . "</td>"; 
                    echo  "<td>" . $comment['CommentDate'] . "</td>"; 
                    echo  "<td>
                           <a href = 'comments.php?action=edit&commentid=".$comment['CommentID']. "'  class ='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                           <a href = 'comments.php?action=delete&commentid=".$comment['CommentID']. "' class ='btn btn-danger confirm'> <i class='fa fa-close'></i>Delete</a>";


                           if($comment['Status'] == 0){
                            echo "<a
                             href = 'comments.php?action=approve&commentid=" 
                             . $comment['CommentID'] . "'  
                             class ='btn btn-info activate'> 
                             <i class='fa fa-check'></i>Approve</a>";
                            
                           }
                      echo " </td> ";
                  echo  "</tr>";
     echo '</div>';
              }
              ?>
             </table>
            </div>
        
        </div>
        <?php
        }else{
            echo '<div class="container">';
                echo '<div class="nice-message"> There\'s No Comments To Show</div>';
            echo '</div>';

        }
?>



<?php }
    
    elseif($action == 'edit'){//edit page

               
        //chec if Get requestuserid is Numeric &Get the integer value of it 
        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        //select  all data depending on this id
        $stmt=$conn->prepare("SELECT * FROM comments WHERE CommentID = ?");
        $stmt->execute(array($commentid)); // execute the query
        $row = $stmt ->fetch(); //fetch the data
        $count = $stmt->rowCount(); 

        //if there is such id  do the following
        if($count > 0){
        ?>
    
    
    <h1 class="text-center"> Edit Comment</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=update" method = "POST">
        <input type="hidden" name ="commentid" value="<?php echo $commentid?>">
         <!-- Start Comment Field -->
           <div class= "mb-3 row">
               <label for="username" class="col-sm-2 col-md-2 col-form-label">Comment</label>
               <div class="col-sm-10 col-md-6">
                   <textarea    
                        name="comment" 
                        id="comment" 
                        cols="30" 
                        rows="10" 
                        class="form-control">
                        <?php echo $row['Comment']?>
                  </textarea>
               </div>
           </div>
         <!-- End Comment Field -->

          <!-- Start SaveButton Field -->
          <div>
               <div class="offset-sm-2 offset-md-2"> <!-- It works in bootstrap 4, there were some changes in 
                                                        -- just use it it without the use of col- prefix
                                                        --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                        -->
                 <input type="submit" value="save" name="update" class="btn btn-primary btn-lg">
               </div>
           </div>
         <!-- End SaveButton Field -->
    </div>

<?php }//if we have id of member to be edited
else{
    echo "<div class = 'container'>";
    $theMsg= '<div class="alert alert-danger">no such id</div>';
    redirectHome($theMsg);
    echo "</div>";
    
}


}//else if  end


elseif($action == 'update'){//update page
  echo '<h1 class="text-center"> Update Comment</h1>';
  echo ' <div class="container"> ' ;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
//get the variables from the form
$commentid           = $_POST['commentid'];
$comment             = $_POST['comment'];

//update the database with this info

$stmt = $conn -> prepare("UPDATE comments SET `Comment` = ?  WHERE CommentID = ?");
$stmt -> execute(array($comment, $commentid ));
 
//echo success message
      $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div> ' ;
      redirectHome($theMsg ,'back');


}//checing if data is being sent via post
else{
    
    $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'Sorry you can\'t browse this page directly </div> ';
    redirectHome($theMsg);
}


echo ' </div> ';

}//end of update elseif 





elseif($action == 'delete'){//delete Comment  page
    echo "<h1 class='text-center'>Delete Comment</h1>";
    echo  "<div class='container'>";
 
         $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
       
         $check = checkItem("CommentID" , "comments" , $commentid);
 
 
     //if there is such id  do the following
         if($check > 0){
         $stmt=$conn->prepare("DELETE  FROM comments WHERE CommentID = :zcomment  LIMIT 1");
         $stmt->bindParam(":zcomment" , $commentid);
         $stmt->execute();
 
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div> ' ;
         redirectHome($theMsg );
 
         }
         else{
             $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . ' This id doesn\'t exist </div> ';
             redirectHome($theMsg, 'back' );
             }
 
   echo '</div>';
       
 }
 elseif($action == 'approve'){//Activate member  page
    echo "<h1 class='text-center'>Approve Comment</h1>";
    echo  "<div class='container'>";
 
         $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
       
         $check = checkItem("CommentID" , "comments" , $commentid);
 
 
     //if there is such id  do the following
         if($check > 0){
         $stmt = $conn->prepare("UPDATE comments SET Status =1 WHERE CommentID =?");
         $stmt->execute(array($commentid));
 
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Activated </div> ' ;
         redirectHome($theMsg, 'back' );
 
         }
         else{
             $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'This id doesn\'t exist </div> ';
             redirectHome($theMsg );
             }
 
   echo '</div>';
       
 }

 


include $tpl . 'footer.php';

}//end of session


else{
    header('Location: index.php'); //redirect index page to login 
    exit();
}


 ?>
