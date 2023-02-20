<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){ // gombra kattintva jutott e el ide
    $startDate = $_POST["inputStartDate"];
    $endDate= $_POST["inputEndDate"];
    if($_POST["inputOther"] != ""){
        $place = $_POST["inputOther"];
    }
    else{
        $place= $_POST["selectPlace"];
    }
    $coachID = $_GET["coachID"];
    // ellenőrzés...

    addTraining($conn,$startDate,$endDate,$place,$coachID);
    
} 
else {
    header("location: ../pages/trainings.php?=hiba");
    exit();
}

