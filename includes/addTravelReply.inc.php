<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_GET["travelID"])){ // gombra kattintva jutott e el ide
    $playerID = $_GET["playerID"];
    $travelID = $_GET["travelID"];
    addTravelReply($conn,$travelID,$playerID);
} 
else {
    header("location: ../pages/travels.php?=hiba");
    exit();
}