<?php
include 'connect.php';
//include 'layout/css/backend.css';
//include 'layout/js/backend.js';

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


if(!isset($noNavBar)){
    include $tpl . 'navbar.php';} 
?>