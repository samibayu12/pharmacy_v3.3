<?php
$dsn='mysql:host=localhost;dbname=pharmacy';
$user ='root';
$password ='petdev';
$option = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET  NAMES utf8',);

try{
$conn = new PDO($dsn , $user , $password , $option);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo 'you are connected';
}

catch(PDOException $e){
echo 'failed to connect to database' . $e->getMessage();
}
?>

<?php 
/*
$servername = "localhost";
$username = "username";
$password = "password";

try {
  $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
*/
?>
