<?php
    require_once 'dbhandler.inc.php';
    require_once 'functions.inc.php';
if (isset($_POST["submit"])){ // gombra kattintva jutott e el ide
    $season = $_POST["season"];
    $date = $_POST["date"];
    $place = $_POST["place"];
    $team = $_POST["team"];
    $homeTeam = $_POST["homeTeam"];
    $homeTeamGoals = $_POST["homeTeamGoals"];
    $awayTeam = $_POST["awayTeam"];
    $awayTeamGoals = $_POST["awayTeamGoals"];
   
    //tömb inputok kinyerése
    $homePlayerName = $_POST["homePlayerName"];
    // $homePlayerID = $_POST["homePlayerID"];
    $homePlayerStart = $_POST["homePlayerStart"];
    $homePlayerGoal = $_POST["homePlayerGoal"];
    $homePlayerYellow = $_POST["homePlayerYellow"];
    $homePlayerRed = $_POST["homePlayerRed"];
    $awayPlayerName = $_POST["awayPlayerName"];
    // $awayPlayerID = $_POST["awayPlayerID"];
    $awayPlayerStart = $_POST["awayPlayerStart"];
    $awayPlayerGoal = $_POST["awayPlayerGoal"];
    $awayPlayerYellow = $_POST["awayPlayerYellow"];
    $awayPlayerRed = $_POST["awayPlayerRed"];

$homePlayers = array();
$awayPlayers = array();

class Player{
    public $playerName;
    public $start;
    public $goal;
    public $yellow;
    public $red;

}
     foreach($homePlayerName as $key => $name){
         if($name != ""){
            $homePlayers[$key] = new Player();
            $homePlayers[$key]->playerName = $homePlayerName[$key];
            $homePlayers[$key]->start = $homePlayerStart[$key];
            $homePlayers[$key]->goal = $homePlayerGoal[$key];
            $homePlayers[$key]->yellow = $homePlayerYellow[$key];
            $homePlayers[$key]->red = $homePlayerRed[$key];
         }
         if($awayPlayerName[$key] != ""){
            $awayPlayers[$key] = new Player();
            $awayPlayers[$key]->playerName = $awayPlayerName[$key];
            $awayPlayers[$key]->start = $awayPlayerStart[$key];
            $awayPlayers[$key]->goal = $awayPlayerGoal[$key];
            $awayPlayers[$key]->yellow = $awayPlayerYellow[$key];
            $awayPlayers[$key]->red = $awayPlayerRed[$key];
         }
     }
     
   addGame($conn,$season,$date,$place,$team,$homeTeam,$homeTeamGoals,$awayTeam,$awayTeamGoals,$homePlayers,$awayPlayers);
}