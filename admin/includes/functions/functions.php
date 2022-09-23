<?php


/**
 **video num 113
 **Get all functions v2.0
 **Function To Get All Records From any Database Table
 */

function getAllFrom($field, $table, $where = NULL, $and = NULL,  $orderField, $ordering = "DESC", $limit = 1){
   global $conn;
   $sql = $where == NULL ? '' : $where;
   $getAll = $conn->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering LIMIT $limit");
   $getAll->execute();
   $all = $getAll->fetchAll();
   return $all;
}



   function getTitle(){
      global  $pageTitle;

      if(isset( $pageTitle)) {
       echo  $pageTitle; }

      else{
    echo 'Default';}

   }



/*
** video num 32
** home redirect function v1.0
**[that accepts parameters]
** $erroMsg = echo the error message
**$seconds = seconds before redirecting
*/
/*
function redirectHome($errorMsg , $seconds = 3){
    echo "<div class='alert alert-danger'>$errorMsg</div>";
    echo "<div class='alert alert-info'>You will be redirected to Home Page after $seconds Seconds";
    header("refresh: $seconds; url=index.php");
    exit();
}
*/


/*
** video num 35
** home redirect function v2.0
**[that accepts parameters]
** $erroMsg = echo the error message
**$seconds = seconds before redirecting
*/


function redirectHome($Msg , $url = null , $seconds = 3){
   if($url === null){
      $url = 'index.php';
      $link = 'Home Page';
   } 
   else{
      
           if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
              $url = $_SERVER['HTTP_REFERER'];
              $link = 'Previous Page';
           }else{
              $url = 'index.php';
              $link = 'Home Page';
           }
            /***If Condition Short Notation****/
         //  $url =isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
  
   }
  
     echo $Msg;
     echo "<div class='alert alert-info'>You will be redirected to   $link   after $seconds Seconds";
     header("refresh: $seconds; url=$url");
     exit();
  }
  




/*
**video num 34
**check items function v1.0
**function that checks for an item in database[this function accepts parameters]
**$select = the item to select [Example : user , items , categories]
**$from = the table to select from [Example : users , items , categories]
**$value = the value of select [Example : petros , box , electronics ]
*/

function checkItem($select , $from , $value ){
   global $conn;
   $stmtFun = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
   $stmtFun -> execute(array($value));
   $count = $stmtFun->rowCount();
   //echo $count;  //this will print the value on the page ,
                   //there is situations where we don't want to print the value 
                   //but want to save the value to deal with it in another way.
    return $count; //thus there is a need for return
   }



   /*
**count number of items function v1.0
**function to count number of items rows
**$Item = the item to count
**$table = the tabl to choose from
*/

function countItem($item , $table){
   global $conn;
   $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
   $stmt2->execute();
   return $stmt2->fetchColumn();
}


/**
 **video num 45
 **Get Latest Records function v1.0
 **Function To Get The Latest Items From Database [Users, Items , Comments]
 **$select = field to select
 **$table  = The Table To Choose From
 **$order  = The Ordering 
 **$limit  = Number Of Records To Get
 */

 function getLatest($select, $table, $order, $limit = 5 ){
    global $conn;
    $getStmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
 }
?>