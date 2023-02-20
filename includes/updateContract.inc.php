<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){
    $newDateOfExpire = $_POST["newDateOfExpire"];
    $playerID = $_GET["playerID"];
    $type = $_GET["type"];
    updateContract($conn, $playerID, $type, $newDateOfExpire);
}