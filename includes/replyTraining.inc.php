<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){
    $reply = $_POST["selectReplyTraining"];
    $trainingID = $_GET["trainingID"];
    $playerID = $_GET["playerID"];
    addReplyTraining($conn, $reply, $trainingID, $playerID);
}