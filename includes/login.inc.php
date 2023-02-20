<?php
if (isset($_POST["submit"])){ // gombra kattintva jutott e el ide
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';

    loginUser($conn, $username, $pwd);
} 
else {
    header("location: ../pages/login.php");
    exit();
}


