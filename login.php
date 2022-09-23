<?php
 ob_start();
 session_start();
 $pageTitle='Login'; 
 if(isset($_SESSION['user'])){
    header('Location: index.php'); //redirect to dashboard page
 }
 include 'init.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD']== 'POST'){
    if (isset($_POST['login'])){
        $user =$_POST['username'];
        $pass=$_POST['password'];
        $hashedPass=sha1($pass);

        //chec if the user exists in database

        $stmt=$conn->prepare("SELECT `UserID` ,`Username` , `Password`
                            FROM
                                users 
                            WHERE
                                `Username` =?
                            AND 
                                `Password` = ? ");
        $stmt->execute(array($user , $hashedPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();
        echo $count;

        //if count >0 this means the database contain record about this username

        if ($count > 0){
        $_SESSION['user'] = $user;
        $_SESSION['uid'] = $get['UserID'];
        header('location: index.php'); //redirect to dashboard page
        exit();
        } else{
            echo '<div class="text-center the-errors">There is no such user </div>';
        }
    } //end of $_POST['login']

    else if (isset($_POST['signup'])){
        $formerrors = array();
        $name               = $_POST['name'];
        $pass               = $_POST['pass'];
        $pass2              = $_POST['pass2'];
        $email              = $_POST['email'];   
        
        if (isset($name)){
            $filteredUser = filter_var($name, FILTER_SANITIZE_STRING);
            if (strlen($filteredUser) < 4){
                $formerrors[] = 'Username Can\'t Be Less Than $ Characters';
            }
        }

        if (isset($pass) && isset($pass2)){
            if (empty($pass)){
                $formerrors[] = 'Password Can\'t Be Empty';
            }
            // $pass1 = sha1($pass);
            // $pass2 = sha1($pass2);
            if (sha1($pass) !== sha1($pass2)){
                $formerrors[] = 'Password Doesn\'t Match';
            }
        }

        if (isset($email)){
            $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true){
                $formerrors[] = 'This Email is Not Valid';
            }
        }
        
        //chec if there is errors
        if (empty ($formerrors)){
        
            // check if the user exists in database
            $check = checkItem("Username" , "users" , $name);
          
            if ($check == 1){
                $formerrors[] =  "the user with  name already exists";
                
            }
            else{
                echo $check;
                  //insert the database   
                  $stmt9 = $conn->prepare("INSERT INTO users
                                  (`Username`, `Password`, `Email`, `RegStatus` , `Date`)
                                  VALUES(:zuser, :zpass, :zemail, 0 , now())
                                  ");
                  $stmt9->execute(array(
                      'zuser'  => $name,
                      'zpass'  => sha1($pass),
                      'zemail' => $email
                  ));

                $successMsg = ' Congrats you Are a Now Member In Our Humble Family ';

              } //end of else section. where this else will be executed if there is no match 
                //in the database of the user to be inserted
              
              }// end of empty errors sections
                 
    }  // end of $_POST['signup']
} //end of $_SERVER['REQUEST_METHOD']== 'POST'


?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> | 
        <span data-class="signup">Signup</span>
    </h1>
    <form  class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <div class="input-container">
            <input 
                class="form-control" 
                type="text" 
                name="username" 
                placeholder="Username"
                autocomplete="off"
                required="required"/>
        </div>
        <div class="input-container">
            <input  
                class="form-control" 
                type="password" 
                name="password" 
                placeholder="Password" 
                autocomplete="new-password"
                required="required"/>
        </div>
        <input  class ="btn btn-primary btn-block" name="login" type="submit" value="Login"/> 
        
    </form>
    <form  class="signup" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="input-container">
            <input 
                pattern=".{4,}"
                tittle="Username must Be more than 4 Characters"
                class="form-control" 
                type="text" 
                name="name" 
                placeholder="Username" 
                autocomplete="off"
                required="required"/>
        </div>
        <div class="input-container">
            <input  
                minlength="4"
                class="form-control" 
                type="password" 
                name="pass" 
                placeholder="Password" 
                autocomplete="new-password"
                required="required"/>
        </div>
        <div class="input-container">
            <input 
                minlength="4" 
                class="form-control" 
                type="password" 
                name="pass2" 
                placeholder="Re-inter Password" 
                autocomplete="new-password"
                required="required"/>
        </div>
        <div class="input-container">
            <input  
                class="form-control" 
                type="email" 
                name="email" 
                placeholder="type a valid email"
                required="required"/>
                <span class= "asterisk">*</span>
        </div>
        <input  class ="btn btn-success btn-block" name="signup" type="submit" value="signup"/> 

    </form>

    
    <?php 
        if (!empty($formerrors)){
            echo '<div class="text-center the-errors">';
                foreach ($formerrors as $error){
                    echo  $error ;
                }
            echo '</div>';
        }

        if (isset($successMsg)){
            echo '<div class="msg-success nice-message">' . $successMsg . '</div>';
        }
     ?>
   

</div>
<?php
//include "includes/template/footer.php";
 include $tpl . 'footer.php';
 ob_end_flash();
?>