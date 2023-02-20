<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["modifyGame"])){ // gombra kattintva jutott e el ide
    $id=$_GET["id"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $birthdate = $_POST["birthdate"];
    $team = $_POST["team"];
    $position = $_POST["position"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $strongFoot = $_POST["strongFoot"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $image = $_POST["image"];
    // ellenőrzés...

    modifyPlayer($conn,$id,$firstName,$lastName,$birthdate,$team,$position,$height,$weight,$strongFoot,$mobile,$email,$image);
    
} 
else {
    header("location: ../pages/players.php?=sikertelenszerkesztés");
    exit();
}

