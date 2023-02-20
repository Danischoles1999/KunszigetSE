<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_GET["travelID"])){ // gombra kattintva jutott e el ide
    $travelID = $_GET["travelID"];
    deleteTravel($conn,$travelID);
} 
else {
    header("location: ../pages/travels.php?=hiba");
    exit();
}