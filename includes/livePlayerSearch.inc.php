<?php

require_once '../includes/dbhandler.inc.php';
require_once '../includes/functions.inc.php';
session_start();
$role = $_SESSION["userrole"];

if(isset($_POST['input'])){
    $input = $_POST['input'];
    $sql = "SELECT player_ID,player_fullName,player_birthdate,team_name,player_position,player_height,player_weight,player_strongFoot,player_mobile,player_email,coach_name FROM players, teams, coaches WHERE (players.team_ID = teams.team_ID AND teams.coach_ID = coaches.coach_ID AND (player_fullName LIKE '%{$input}%'));";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    showPlayersTable($conn, $result, $resultCheck, $role);
}