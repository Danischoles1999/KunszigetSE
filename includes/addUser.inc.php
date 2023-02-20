<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submitUser"])){ // gombra kattintva jutott e el ide
    $userFullName = $_POST["inputUserFullName"];
    $userEmail = $_POST["inputUserEmail"];
    $userName= $_POST["inputUserName"];
    $userPassword= $_POST["inputUserPassword"];
    $userPasswordRe= $_POST["inputUserPasswordRe"];
    $userRole=$_POST["inputUserRole"];
    // ellenőrzés...
    
    addUser($conn,$userFullName,$userEmail,$userName,$userPassword,$userPasswordRe,$userRole);
    
} 
else {
    header("location: ../pages/players.php?=hiba");
    exit();
}

