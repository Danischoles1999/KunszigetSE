<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){
    $teamID;
    if($_GET["teamID"] == 6){
        $teamID = 6;
    }
    elseif($_GET["teamID"] == 5){
        $teamID = 5;
    }
    elseif($_GET["teamID"] == 4){
        $teamID = 4;
    }
    elseif($_GET["teamID"] == 3){
        $teamID = 3;
    }
    elseif($_GET["teamID"] == 2){
        $teamID = 2;
    }
    else{
        $teamID = 1;
    }
    $date = $_POST["inputDate"];
    $place = $_POST["inputPlace"];
    $player = $_POST["inputSquadPlayer"];
    $playerids = array();
    foreach($player as $key){
        array_push($playerids, $key);
    }
    addGameSquad($conn,$teamID,$date,$place,$playerids);
}