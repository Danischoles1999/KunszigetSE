<?php

require_once '../includes/dbhandler.inc.php';
require_once '../includes/functions.inc.php';
session_start();
$role = $_SESSION["userrole"];

if(isset($_POST['input'])){
    $input = $_POST['input'];
    $sql = "SELECT * FROM matches WHERE (matches.match_season = '{$input}');";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    showGamesTable($conn, $result, $resultCheck, $role);
}