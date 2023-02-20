<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){
    $start_date = $_POST["inputStartDate"];
    $end_date = $_POST["inputEndDate"];
    $reason = $_POST["selectReason"];
    $explanation = "";
    if($_POST["inputReason"]){
        $explanation = $_POST["inputReason"];
    }
    $playerID = $_GET["playerID"];
    $teamID = $_GET["teamID"];
    addAbsence($conn, $playerID, $teamID, $start_date, $end_date, $reason, $explanation);
}