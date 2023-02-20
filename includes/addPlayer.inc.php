<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submitPlayer"])){ // gombra kattintva jutott e el ide
    $id = $_POST["inputID"];
    // $firstName = $_POST["firstName"];
    // $lastName = $_POST["lastName"];
    $fullName = $_POST["inputFullName"];
    $birthdate = $_POST["birthdate"];
    $team = $_POST["team"];
    $position = $_POST["position"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $strongFoot = $_POST["strongFoot"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $contract_date= $_POST["contractDate"];
    $contract_expires= $_POST["contractExpires"];
    $last_medical= $_POST["lastMedical"];
    $image=$_POST["image"];
    // ellenőrzés...

    //addPlayer($conn,$id,$firstName,$lastName,$birthdate,$team,$position,$height,$weight,$strongFoot,$mobile,$email,$contract_date,$contract_expires,$last_medical,$image);
    addPlayer($conn,$id,$fullName,$birthdate,$team,$position,$height,$weight,$strongFoot,$mobile,$email,$contract_date,$contract_expires,$last_medical,$image);
    
} 
else {
    header("location: ../pages/players.php?=hiba");
    exit();
}

