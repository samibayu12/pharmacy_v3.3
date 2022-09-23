<?php
session_start();
$pageTitle="Members";
if(isset($_SESSION['Username'])){

    include 'init.php';


$action=isset($_GET['action']) ? $_GET['action'] : 'manage'; 
 
//start manage page
if($action == 'manage'){//manage page


    if(isset($_GET['role']) && $_GET['role']=='customer'){
        $role = 0;
    }elseif(isset($_GET['role']) && $_GET['role']=='employee'){
        $role = 2;
    }elseif(isset($_GET['role']) && $_GET['role']=='admin'){
        $role = 1;
    }else{
        $role = 0;
    }
   
   $query = '';
    if(isset($_GET['page']) && $_GET['page'] == 'pending'){
        $query = 'AND RegStatus = 0';
    }


    //select all users except admin
$stmt = $conn->prepare("SELECT * FROM users WHERE GroupID = $role $query ORDER BY UserID DESC");
//$stmt = getAllFrom("*", "users", "GroupID != 1" , "{$query}", "UserID", "" , "");

//execute statment
$stmt->execute();

//assign to variable
$rows = $stmt->fetchAll();

if (! empty($rows)){
?>

<h1 class="text-center">Manage Members</h1>
        <div class="container">
            <ul class="nav nav-tabs  role-nav">
                <li class="nav-item">
                    <a class="nav-link selected" aria-current="page" href="?manage&role=customers">Customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?manage&role=employee">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?manage&role=admin">Admin</a>
                </li>
            </ul>
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                       <td>#ID</td>
                       <td>Avatar</td>  
                       <td>Username</td> 
                       <td>Email</td> 
                       <td>Full Name</td> 
                       <td>Registeration Date</td> 
                       <td>Control</td> 
                    </tr>
<?php
foreach($rows as $row){
    echo '<div class="table-input">';
                    echo "<tr>";
                    echo  "<td>" . $row['UserID'] . "</td> ";
                    echo  "<td>";
                        if(empty($row['Avatar'])){
                            echo "<img class='img-responsive rounded-circle' src='uploads/avatar/user-avatar-man.png' alt = '' />";
                        }else{
                            echo "<img class='img-responsive rounded-circle' src='uploads/avatar/" . $row['Avatar'] . "' alt = '' />";
                        }
                    "</td> ";
                    echo  "<td>" . $row['Username'] . "</td> ";
                    echo  "<td>" . $row['Email'] . "</td> ";
                    echo  "<td>" . $row['FullName'] . "</td>"; 
                    echo  "<td>" . $row['Date'] . "</td>"; 
                    echo  "<td>
                           <a href = 'members.php?action=edit&userid=".$row['UserID']. "'  class ='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                           <a href = 'members.php?action=delete&userid=".$row['UserID']. "' class ='btn btn-danger confirm'> <i class='fa fa-close'></i>Delete</a>";


                           if($row['RegStatus'] == 0){
                            echo "<a href = 'members.php?action=activate&userid=". $row['UserID'] . "'  class ='btn btn-info activate'> <i class='fa fa-check'></i>Activate</a>";
                            
                           }
                      echo " </td> ";
                  echo  "</tr>";
     echo '</div>';
              }
              ?>
             </table>
            </div>
        
 <a href="members.php?action=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member </a>    
        </div>

<?php
        }else{
            echo '<div class="container">';
                echo '<div class="nice-message"> There\'s No Members To Show</div>';
                echo '<a href="members.php?action=add" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add New Member 
                      </a>';
            echo '</div>';

        }
?>

<?php }
    
    elseif($action == 'add'){//add page
        ?>

            <h1 class="text-center"> Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?action=insert" method= "POST" enctype = "multipart/form-data">
                    <!--
                        * enctype(encoding-type) => is the type of encrypting the form data when sent to server
                        * By default the encrypting type is => application/x-www-form-url-encoded
                        * there are also text/plain , application/json ..etc
                        * But if you have an input with a file type in your form Then You must use  => "multipart/form-data"
                    -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="myimg">
                                <img 
                                    id='previewMyimg'
                                    class='img-responsive rounded-circle' 
                                    src='uploads/avatar/user-avatar-man.png' 
                                    alt = '' 
                                    />
                                <div class="avatarpic">
                                <input type="file" id="avatar" name="avatar" class="form-control-lg" >
                                </div>
                            </div>
                                
                            </div> <!---end of col-md-4  -->


                            <div class="col-md-8">

                                <!-- Start Username Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-2 col-form-label">Username</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="text" name="username" class="form-control-lg" autocomplete="off" required="required" placeholder="">
                                    </div>
                                </div>
                                <!-- End Username Field -->
                        
                                <!-- Start Password Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-2 col-form-label">Password</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="password" name="password" class="password form-control-lg" autocomplete="new-password"  required="required" placeholder="">
                                        <i class = "show-pass fa fa-eye fa-2x"></i>
                                        </div>
                                </div>
                                <!-- End Password Field -->
                        
                                <!-- Start Email Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-2 col-form-label">Email</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="email" name="email" class="form-control-lg"  required="required" placeholder="">
                                    </div>
                                </div>
                                <!-- End Email Field -->
                        
                                <!-- Start Fullname Field -->
                                <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-2 col-form-label">Full Name</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="text" name="full" class="form-control-lg"  required="required" placeholder="">
                                    </div>
                                </div>
                                <!-- End Fullname Field -->

                                <!-- Start Avatar Field -->
                                <!-- <div class= "mb-3 row">
                                    <label class="col-sm-2 col-md-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="file" id="avatar" name="avatar" class="form-control-lg avatar"  required="required" >
                                    </div>
                                </div> -->
                                <!-- End Avatar Field -->
                        
                                <!-- Start SaveButton Field -->
                                <div>
                                    <div class="offset-sm-2 offset-md-2"> <!-- It works in bootstrap 4, there were some changes in 
                                                                        -- just use it it without the use of col- prefix
                                                                        --  https://stackoverflow.com/questions/38357137/bootstrap-col-md-offset-not-working
                                                                        -->
                                        <input type="submit" value="save" name="add" class="btn btn-primary btn-lg">
                                    </div>
                                </div>
                                <!-- End SaveButton Field -->

                        </div>   <!-- End col-md-8 Field -->
                    </div>  <!-- End row Field -->
                </form>
            </div> <!-- end container -->
                
    
    
    
    
    <?php
        }
    
elseif($action == 'insert'){
   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //get the variables from the form
  
    echo '<h1 class="text-center"> Insert Member</h1>';
    echo ' <div class="container"> ' ;

    //Upload Variables
    $avatarName = $_FILES['avatar']['name'];
    $avatarSize = $_FILES['avatar']['size'];
    $avatarTemp = $_FILES['avatar']['tmp_name'];
    $avatarType = $_FILES['avatar']['type'];

    // List of allowed  file type to Upload
    $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
 
 /*** example on explode() function
  * $string = 'Petros.Haile.Ghebremedhin';
  * $avatarExtension = explode('.' , $string);
  * print_r($avatarExtension);
  * the output is =>  Array ( [0] => Petros [1] => Haile [2] => Ghebremedhin ) 
  * This explode function accepts two parameters
  * these parameters are (delimeter[dot, comma ..etc ] , string)
  * so what it does is it takes the delimeter you provide and remove it from 
  * the string following it and return an arry of strings.
  *end() function returns the last element in the array we can use it to return
  * the file extension as in the following practical implementation
  ***/


    // Get Avatar Extension
     $avatarExtension = strtolower(end(explode('.', $avatarName)));
     //we used strtolower b/c the allowed extensions are in lower case , it will cause a bug if the uploaded file extension is in upper case

    $user         = $_POST['username'];
    $pass         = $_POST['password'];
    $email        = $_POST['email'];
    $name         = $_POST['full'];
    
    $hashPass     = sha1($_POST['password']);
  
    
    $formErrors = array();
    if(strlen($user) < 4){
        $formErrors[] =  'username can\'t be <strong>less than 4 chartcters</strong>';
    }
    if(strlen($user) > 20){
        $formErrors[] =  'username can\'t be <strong>More than 20 chartcters</strong>';
    }
        if(empty($user)){
        $formErrors[] = 'username can\'t be <strong>Empty</strong>';
    }
    if(empty($pass)){
      $formErrors[] =  'password can\'t be <strong>Empty</strong>';
  }
    if(empty($name)){
        $formErrors[] = 'fullname can\'t be <strong>Empty</strong>';
    }
    if(empty($email)){
        $formErrors[] = 'email can\'t be <strong>Empty</strong>';
    }
    if (! empty($avatarName) && ! in_array($avatarExtension , $avatarAllowedExtension)){
        $formErrors[] = 'This Extension is Not<strong>Allowed</strong>';
    }
    if (empty($avatarName)){
        $formErrors[] = 'Avatar is <strong>Required</strong>';
    }
    if ($avatarSize > 4194304){
        $formErrors[] = 'Avatar Can\'t Be More Than <strong>4MB</strong>';
    }
    
    
    foreach($formErrors as $err){
        echo '<div class="alert alert-danger">' . $err . '</div>' ;
    }
    
    
    //chec if there is errors
    if(empty($formErrors)){
        //using random number in front of image to guarantee no repition of image
        $avatar = rand(0, 100000) . '_' . $avatarName;

        /***move_uploaded_file(filename, destination);  ***/
        // filename => is the temporary file name saved in the  $avatarTemp variable
        //destination => the folder to store the image 
        //Note: the image will be stored in the destination folder , only its name will be stored in the DataBase
        move_uploaded_file( $avatarTemp, "uploads\avatar\\" . $avatar);

        //when uploading the file from server there must be write permission to this folder
        // it can be (775) OR (777) 
            
        // check if the user exists in database
        $check = checkItem("Username" , "users" , $user);
        if($check == 1){
            $theMsg = '<div class= "alert alert-danger">the user with ' .$user . ' name already exists </div>';
            redirectHome($theMsg, 'back');
        }
        else{
              //insert the database   
              $stmt = $conn->prepare("INSERT INTO users
                              (`Username`, `Password`, `Email`, `FullName`, `GroupID`, `RegStatus` , `Date`, `Avatar`)
                              VALUES(:zuser, :zpass, :zemail, :zname, 2 , 1 , now() , :zavatar)
                              ");
              $stmt->execute(array(
                  'zuser'       => $user,
                  'zpass'       => $hashPass,
                  'zemail'      => $email,
                  'zname'       => $name,
                  'zavatar'     => $avatar
              ));
  
              //echo success message
              $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted </div> ' ;
              redirectHome($theMsg , 'back');
          } //end of else section. where this else will be executed if there is no match 
            //in the database of the user to be inserted
          
    }// end of empty errors sections
                 
          echo ' </div> ' ;
  
    }//end of checing if data is being sent via post 
    else{
        echo "<div class = 'container'>";
        $theMsg= '<div class="alert alert-danger">Sorry you can\'t browse this page directly</div>';
        redirectHome($theMsg);
        echo "</div>";
      }
    
    
    
  
  
  }//end of insert if else
  
  
  
    

    elseif($action == 'edit'){//edit page

               
        //chec if Get requestuserid is Numeric &Get the integer value of it 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // echo $userid;
        //select  all data depending on this id
        $stmt=$conn->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
        $stmt->execute(array($userid)); // execute the query
        $row = $stmt ->fetch(); //fetch the data
        $count = $stmt->rowCount(); 

        //if there is such id  do the following
        if($count > 0){
        ?>
    
    
    <h1 class="text-center"> Edit Member</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=update" method= "POST" enctype = "multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="myimg">
                    <?php
                    $imgsource = empty($row['Avatar'])? 'uploads/avatar/user-avatar-man.png' : "uploads/avatar/" . $row['Avatar'];
                    ?>
                    <img 
                            id="previewMyimg"
                            class="img-responsive rounded-circle" 
                            src="<?php echo $imgsource ?>" 
                            alt = " "
                            />

                    <!-- this will not upload the image if the user doesn't have avatar
                        * This happened when at first adding user avatar image wasnot required hence there is no
                        * image when fetching using $row['Avatar']
                        * OR if you add user directly from the Dtatbase without adding avatar
                        * So in any cases if no image in the database it will cause to not display the image
                        * So there is a variable it will hold the value of the image name the default value if no image
                        * & the user image if there is user image 
                     -->
                        <!-- <img 
                            id="previewMyimg"
                            class="img-responsive rounded-circle" 
                            src="<?php //echo 'uploads/avatar/' . $row['Avatar'] ?>" 
                            alt = " "
                            /> -->
                        <div class="avatarpic">
                        <input type="file" id="avatar" name="avatar" class="form-control-lg" >
                        </div>
                    </div>
                </div> <!---end of col-md-4  -->


                <div class="col-md-8">

                    <input type="hidden" name ="userid" value="<?php echo $userid?>">
                    <!-- Start Username Field -->
                    <div class= "mb-3 row">
                        <label for="username" class="col-sm-2 col-md-2 col-form-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control-lg"  id="username" name="username" value="<?php echo $row['Username']?>" autocomplete="off" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Username Field -->

                    <!-- Start Password Field -->
                    <div class= "mb-3 row">
                        <label for="newpassword" class="col-sm-2 col-md-2 col-form-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword"  value="<?php echo $row['Password']?>">
                            <input type="password" class="form-control-lg"  id="newpassword" name="newpassword" autocomplete="new-password"  placeholder="">
                        </div>
                    </div>
                    <!-- End Password Field -->

                    <!-- Start Email Field -->
                    <div class= "mb-3 row">
                        <label for="email" class="col-sm-2 col-md-2 col-form-label">Email</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input type="email" class="form-control-lg"  id="email" name="email" value="<?php echo $row['Email']?>" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Email Field -->

                    <!-- Start Fullname Field -->
                    <div class= " mb-3 row">
                        <label for="full" class="col-sm-2 col-md-2 col-form-label">Full Name</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input type="text" class="form-control-lg"  id="full" name="full" value="<?php echo $row['FullName']?>" required="required" placeholder="">
                        </div>
                    </div>
                    <!-- End Fullname Field -->

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

                </div> <!-- End col-md-8 Field -->
            </div> <!-- End row Field -->
        </form>
    </div> <!-- End container Field -->



<?php }//if we have id of member to be edited
else{
    echo "<div class = 'container'>";
    $theMsg= '<div class="alert alert-danger">no such id</div>';
    redirectHome($theMsg);
    echo "</div>";
    
}


}//else if  end


elseif($action == 'update'){//update page
  echo '<h1 class="text-center"> Update Member</h1>';
  echo ' <div class="container"> ' ;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
//get the variables from the form


//Upload Variables
$avatarName = $_FILES['avatar']['name'];
$avatarSize = $_FILES['avatar']['size'];
$avatarTemp = $_FILES['avatar']['tmp_name'];
$avatarType = $_FILES['avatar']['type'];

// List of allowed  file type to Upload
$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

// Get Avatar Extension
$avatarExtension = strtolower(end(explode('.', $avatarName)));
//we used strtolower b/c the allowed extensions are in lower case , it will cause a bug if the uploaded file extension is in upper case



$id           = $_POST['userid'];
$user         = $_POST['username'];
$email        = $_POST['email'];
$name         = $_POST['full'];

//password tric  
/*$pass = "";
if(empty($_POST['newpassword'])){
$pass = $_POST['oldpassword'];
}
else{
$pass = sha1($_POST['newpassword']);
}
*/

$pass = (empty($_POST['newpassword'])) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);




$formErrors = array();
if(strlen($user) < 4){
    $formErrors[] =  '<div class="alert alert-danger">username can\'t be <strong>less than 4 chartcters</strong>';
}
if(strlen($user) > 20){
    $formErrors[] =  '<div class="alert alert-danger">username can\'t be <strong>More than 20 chartcters</strong>';
}
    if(empty($user)){
    $formErrors[] = '<div class="alert alert-danger">username can\'t be <strong>Empty</strong>';
}
if(empty($name)){
    $formErrors[] = '<div class="alert alert-danger">fullname can\'t be <strong>Empty</strong>';
}
if(empty($email)){
    $formErrors[] = '<div class="alert alert-danger">email can\'t be <strong>Empty</strong>';
}
if (! empty($avatarName) && ! in_array($avatarExtension , $avatarAllowedExtension)){
    $formErrors[] = 'This Extension is Not<strong>Allowed</strong>';
}
if (empty($avatarName)){
    $formErrors[] = 'Avatar is <strong>Required</strong>';
}
if ($avatarSize > 4194304){
    $formErrors[] = 'Avatar Can\'t Be More Than <strong>4MB</strong>';
}


foreach($formErrors as $err){
    echo '<div class="alert alert-danger">' . $err . '</div>' ;
}


//chec if there is errors
if(empty($formErrors)){

    //using random number in front of image to guarantee no repition of image
    $avatar = rand(0, 100000) . '_' . $avatarName;

    /***move_uploaded_file(filename, destination);  ***/
    // filename => is the temporary file name saved in the  $avatarTemp variable
    //destination => the folder to store the image 
    //Note: the image will be stored in the destination folder , only its name will be stored in the DataBase
    move_uploaded_file( $avatarTemp, "uploads\avatar\\" . $avatar);


    $stmt2 =$conn->prepare("SELECT 
                                *
                            FROM
                                users
                            WHERE
                                Username = ?
                            AND
                                UserID != ?");
    $stmt2->execute(array($user , $id));
    $count = $stmt2->rowCount();
    if ($count == 1){
        $theMsg = '<div class= "alert alert-danger"> Sorry This User Exist</div>';
        redirectHome($theMsg ,'back');
    }else{
        //update the database with this info
        $stmt3 = $conn->prepare("UPDATE users SET `Username` = ? ,  `Email` = ? , `FullName` = ? , `Password` = ? , Avatar = ? WHERE UserID = ?");
        $stmt3->execute(array($user , $email , $name , $pass , $avatar , $id ));
        
        //echo success message
        $theMsg= "<div class='alert alert-success'>" . $stmt3->rowCount() . 'Record Updated </div> ' ;
        redirectHome($theMsg ,'back');
    }
}//empty errors

}//checing if data is being sent via post
else{
    
    $theMsg= "<div class='alert alert-danger'>" . $stmt3->rowCount() . 'Sorry you can\'t browse this page directly </div> ';
    redirectHome($theMsg);
}


echo ' </div> ';

}//end of update elseif 





elseif($action == 'delete'){//delete member  page
    echo "<h1 class='text-center'>Delete Member</h1>";
    echo  "<div class='container'>";
 
         $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
         //echo $userid;
     //select  all data depending on this id
        /* the code below is replaced with the checkItem() function.
            $stmt=$conn->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
            $stmt->execute(array($userid)); // execute the query
            $count = $stmt->rowCount();
         */
 
         $check = checkItem("UserID" , "users" , $userid);
 
 
     //if there is such id  do the following
         if($check > 0){
         $stmt = $conn->prepare("DELETE  FROM users WHERE UserID = :zuser  LIMIT 1");
         $stmt->bindParam(":zuser" , $userid);
         $stmt->execute();
         $row = $stmt->rowCount();
 
         $theMsg = "<div class='alert alert-success'>" . $row . "Record Deleted </div> " ;
         redirectHome($theMsg , 'back');
 
         }
         else{
             $theMsg= "<div class='alert alert-danger'>" . $row . "This id doesn\'t exist </div> ";
             redirectHome($theMsg);
             }
 
   echo '</div>';
       
 }
 elseif($action == 'activate'){//Activate member  page
    echo "<h1 class='text-center'>Activate Member</h1>";
    echo  "<div class='container'>";
 
         $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // echo $userid;
     //select  all data depending on this id
        /* $stmt=$conn->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
         $stmt->execute(array($userid)); // execute the query
         $count = $stmt->rowCount(); */
 
         $check = checkItem("UserID" , "users" , $userid);
 
 
     //if there is such id  do the following
         if($check > 0){
         $stmt = $conn->prepare("UPDATE users SET RegStatus =1 WHERE UserID =?");
         $stmt->execute(array($userid));
 
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Activated </div> ' ;
         redirectHome($theMsg );
 
         }
         else{
             $theMsg= "<div class='alert alert-danger'>" . $stmt->rowCount() . 'This id doesn\'t exist </div> ';
             redirectHome($theMsg);
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
