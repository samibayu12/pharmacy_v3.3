<?php

/*  categories => [manage | edit | update | add | Insert | Delete |]
*/ 

ob_start(); // Output Buffering Start
session_start();
$pageTitle="Members";
if(isset($_SESSION['Username'])){
    include 'init.php';
//condition ? true: false;
$action=isset($_GET['action']) ? $_GET['action'] : 'manage'; 



//if the page is main page
if($action == 'manage'){
echo 'welcome to manage page';

}
elseif($action == 'add'){
    echo 'welcome to add page';

}
elseif($action == 'insert'){
    echo 'welcome to insert page';

}

elseif($action == 'edit'){
    echo 'welcome to manage page';

}
elseif($action == 'update'){
    echo 'welcome to manage page';

}

elseif($action == 'delete'){
    echo 'welcome to manage page';

}


include $tpl . 'footer.php';

}//end of session


else{
    header('Location: index.php'); //redirect index page to login 
    exit();
}
ob_end_flush(); // Release the output
?>