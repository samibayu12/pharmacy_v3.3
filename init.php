<?php

//Error Reporting
ini_set('display_errors' , 'On');
error_reporting('E_All');


include 'admin/connect.php';

$sessionUser = '';
if (isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}


//Routes

$tpl= 'includes/template/'; //template directory
$lang = 'includes/languages/'; // language directory
$func = 'includes/functions/'; // functions directory

$css= 'layout/css/'; // css directory
$js= 'layout/js/'; //js directory



//include the important files
include $func . 'functions.php';
include $lang . 'english.php';
include  $tpl . 'header.php';//include "includes/template/header.php"; //this  contains the bootstrap ,css extensions
//include  $tpl . 'footer.php';//include "includes/template/footer.php"; //this contains the js, and jquery extensions

//no more need it, in the client side.all the pages will have a navBar
// if(!isset($noNavBar)){
//     include $tpl . 'navbar.php';} 
?>