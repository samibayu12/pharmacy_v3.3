 <?php
 session_start();
 $noNavBar =''; 
 $pageTitle='Login'; 
 if(isset($_SESSION['Username'])){
    header('Location: dashboard.php'); //redirect to dashboard page
 }
 include 'init.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD']== 'POST'){
$username =$_POST['user'];
$password =$_POST['pass'];
$hashedPass=sha1($password);

//chec if the user exists in database

$stmt=$conn->prepare("SELECT `UserID`,`Username` , `Password`
                      FROM
                         users 
                      WHERE
                           `Username` =?
                      AND 
                           `Password` = ? 
                      AND 
                            GroupID =1  
                      LIMIT 1");
$stmt->execute(array($username , $hashedPass));
$row = $stmt ->fetch();
$count = $stmt->rowCount();
echo $count;

//if count >0 this means the database contain record about this username

if ($count > 0){
$_SESSION['Username'] = $username;
$_SESSION['ID'] = $row['UserID'];
header('location: dashboard.php'); //redirect to dashboard page
exit();
} 

}

?>
 
<form  class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
<h4 class="text-center">Admin Login</h4>
<input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off"/>
<input  class="form-control input-lg" type="text" name="pass" placeholder="Password" autocomplete="new-password"/>
<input  class ="btn btn-lg btn-primary btn-block" type="submit" value="Login"/> 

</form>


<?php
//include "includes/template/footer.php";
 include $tpl . 'footer.php';
?>