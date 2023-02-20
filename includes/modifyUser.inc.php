<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["modifyUser"])){ // gombra kattintva jutott e el ide
    $id=$_GET["id"];
    $userName = $_POST["userName"];
    $userEmail = $_POST["userEmail"];
    $userUid = $_POST["userUid"];
    $userRole = $_POST["userRole"];
    // ellenőrzés...

    modifyUser($conn,$id,$userName,$userEmail,$userUid,$userRole);
} 
else {
    header("location: ../pages/players.php?=sikertelenszerkesztés");
    exit();
}

