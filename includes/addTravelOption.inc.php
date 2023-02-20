<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){ // gombra kattintva jutott e el ide
    $date = $_POST["dateOfTravel"];
    $place= $_POST["placeOfDepartment"];
    $coachID = $_GET["coachID"];
    addTravelOption($conn,$date,$place,$coachID);
} 
else {
    header("location: ../pages/trainings.php?=hiba");
    exit();
}

